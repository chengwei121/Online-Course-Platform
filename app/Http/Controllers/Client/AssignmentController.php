<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class AssignmentController extends Controller
{
    private $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function show(Assignment $assignment)
    {
        $submission = AssignmentSubmission::where('user_id', Auth::id())
            ->where('assignment_id', $assignment->id)
            ->first();

        return view('client.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $startTime = microtime(true);
        
        // Enable query logging to track database performance
        DB::enableQueryLog();
        
        Log::info('Assignment submission started', [
            'assignment_id' => $assignment->id,
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $validationTime = microtime(true);
        Log::info('Validation completed', ['time' => ($validationTime - $startTime) . 's']);

        // Prepare submission data
        $submissionData = [
            'submission_text' => $request->content,
            'status' => 'submitted',
            'submitted_at' => now(),
        ];

        // Handle file upload if present
        if ($request->hasFile('file')) {
            $fileStartTime = microtime(true);
            $path = $request->file('file')->store('assignment-submissions', 'public');
            $submissionData['submission_file'] = $path;
            $fileEndTime = microtime(true);
            Log::info('File uploaded', ['time' => ($fileEndTime - $fileStartTime) . 's', 'path' => $path]);
        }

        // Get student ID from authenticated user (eager load to avoid N+1)
        $user = Auth::user();
        $student = $user->student;

        // Single database operation - updateOrCreate with all data at once
        $dbStartTime = microtime(true);
        $submission = AssignmentSubmission::updateOrCreate(
            [
                'user_id' => $user->id,
                'student_id' => $student ? $student->id : null,
                'assignment_id' => $assignment->id,
            ],
            $submissionData
        );
        $dbEndTime = microtime(true);
        
        Log::info('Database operation completed', [
            'time' => ($dbEndTime - $dbStartTime) . 's',
            'submission_id' => $submission->id
        ]);

        // Notify the teacher about the new submission
        try {
            $course = $assignment->lesson->course;
            if ($course && $course->teacher && $course->teacher->user) {
                $teacher = $course->teacher->user;
                
                // Create custom notification
                $this->notificationService->createNotification(
                    $teacher,
                    'assignment_submission',
                    'New Assignment Submission',
                    "{$user->name} has submitted the assignment: {$assignment->title}",
                    null,
                    [
                        'student_id' => $user->id,
                        'student_name' => $user->name,
                        'student_email' => $user->email,
                        'assignment_id' => $assignment->id,
                        'assignment_title' => $assignment->title,
                        'course_id' => $course->id,
                        'course_title' => $course->title,
                        'submission_id' => $submission->id,
                        'submitted_at' => $submission->submitted_at->toDateTimeString(),
                    ],
                    route('teacher.submissions.grade', $submission->id),
                    'high'
                );
                
                // Send email to teacher
                Mail::to($teacher->email)->queue(new \App\Mail\TeacherAssignmentSubmissionNotification($user, $assignment, $submission, $course));
                
                Log::info("Teacher notified about assignment submission: {$assignment->title}");
            }
        } catch (\Exception $notifyError) {
            Log::error("Failed to notify teacher about assignment submission: " . $notifyError->getMessage());
        }

        // Log all queries executed
        $queries = DB::getQueryLog();
        Log::info('Total queries executed: ' . count($queries));
        foreach ($queries as $i => $query) {
            Log::info("Query #" . ($i + 1), [
                'sql' => $query['query'],
                'bindings' => $query['bindings'],
                'time' => $query['time'] . 'ms'
            ]);
        }

        $totalTime = microtime(true) - $startTime;
        Log::info('Assignment submission completed', ['total_time' => $totalTime . 's']);

        return redirect()->back()->with('success', 'Assignment submitted successfully!');
    }

    public function submissions(Assignment $assignment)
    {
        $submissions = AssignmentSubmission::with('user')
            ->where('assignment_id', $assignment->id)
            ->latest()
            ->paginate(10);

        return view('client.assignments.submissions', compact('assignment', 'submissions'));
    }

    public function download($submissionId)
    {
        $submission = AssignmentSubmission::where('id', $submissionId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!$submission->submission_file) {
            abort(404, 'File not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($submission->submission_file)) {
            abort(404, 'File not found in storage');
        }

        // Get original filename from the path
        $storedFileName = basename($submission->submission_file);
        $extension = pathinfo($storedFileName, PATHINFO_EXTENSION);
        
        // Create a clean download filename
        $downloadName = 'assignment_submission_' . $submission->id . '.' . $extension;
        
        // Use Storage facade for proper file download
        return Storage::disk('public')->download($submission->submission_file, $downloadName);
    }
} 