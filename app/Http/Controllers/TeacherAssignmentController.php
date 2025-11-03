<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherAssignmentController extends Controller
{
    /**
     * Display all assignments for the authenticated teacher
     */
    public function index()
    {
        // Get the authenticated user's teacher record
        $teacher = Auth::user()->teacher;
        
        if (!$teacher) {
            abort(403, 'Teacher profile not found.');
        }
        
        // Get all courses owned by this teacher
        $courses = $teacher->courses()->with('lessons.assignments.submissions')->get();
        
        // Get all assignments from teacher's courses
        $assignmentsQuery = Assignment::whereHas('lesson.course', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['lesson.course', 'submissions']);
        
        // Apply course filter
        if (request('course_id')) {
            $assignmentsQuery->whereHas('lesson', function ($query) {
                $query->where('course_id', request('course_id'));
            });
        }
        
        // Apply search filter
        if (request('search')) {
            $assignmentsQuery->where('title', 'like', '%' . request('search') . '%');
        }
        
        $assignments = $assignmentsQuery->get();
        
        // Group assignments by course
        $assignmentsByCourse = $assignments->groupBy(function ($assignment) {
            return $assignment->lesson->course->title;
        });
        
        // Calculate statistics
        $totalAssignments = $assignments->count();
        $totalSubmissions = $assignments->sum(function ($assignment) {
            return $assignment->submissions->count();
        });
        $pendingSubmissions = $assignments->sum(function ($assignment) {
            return $assignment->submissions->where('status', 'submitted')->whereNull('score')->count();
        });
        $gradedSubmissions = $assignments->sum(function ($assignment) {
            return $assignment->submissions->whereNotNull('score')->count();
        });
        
        return view('teacher.assignments.index', compact(
            'assignmentsByCourse',
            'courses',
            'totalAssignments',
            'totalSubmissions',
            'pendingSubmissions',
            'gradedSubmissions'
        ));
    }

    /**
     * Display all submissions for a specific assignment
     */
    public function submissions(Assignment $assignment)
    {
        // Get the course from the assignment's lesson
        $course = $assignment->lesson->course;
        
        // Authorization: Check if the authenticated teacher owns this course
        // Course belongs to Teacher, Teacher belongs to User
        if ($course->teacher->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to view these submissions.');
        }
        
        // Get all students enrolled in THIS course only
        $enrolledStudents = Student::whereHas('enrollments', function ($query) use ($course) {
            $query->where('course_id', $course->id)
                  ->whereIn('status', ['active', 'in_progress', 'completed']);
        })->with('user');

        // Apply search filter
        if (request('search')) {
            $enrolledStudents->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                      ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        }

        // Apply status filter
        if (request('status') === 'submitted') {
            $enrolledStudents->whereHas('submissions', function ($query) use ($assignment) {
                $query->where('assignment_id', $assignment->id)
                      ->where('status', 'submitted');
            });
        } elseif (request('status') === 'graded') {
            $enrolledStudents->whereHas('submissions', function ($query) use ($assignment) {
                $query->where('assignment_id', $assignment->id)
                      ->whereNotNull('score');
            });
        } elseif (request('status') === 'pending') {
            $enrolledStudents->whereDoesntHave('submissions', function ($query) use ($assignment) {
                $query->where('assignment_id', $assignment->id);
            });
        }

        $students = $enrolledStudents->paginate(15);

        // Get all submissions for this assignment from students in the paginated list
        $submissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id'); // Key by student_id for easy lookup

        // Calculate statistics for ALL students in the course (not just paginated)
        $totalStudents = Student::whereHas('enrollments', function ($query) use ($course) {
            $query->where('course_id', $course->id)
                  ->whereIn('status', ['active', 'in_progress', 'completed']);
        })->count();

        // Get all submissions for this assignment (for statistics)
        $allSubmissions = AssignmentSubmission::where('assignment_id', $assignment->id)
            ->whereHas('student', function ($query) use ($course) {
                $query->whereHas('enrollments', function ($q) use ($course) {
                    $q->where('course_id', $course->id)
                      ->whereIn('status', ['active', 'in_progress', 'completed']);
                });
            })
            ->get();
        
        $submittedCount = $allSubmissions->whereIn('status', ['submitted', 'graded'])->count();
        $pendingCount = $allSubmissions->where('status', 'submitted')
                                      ->whereNull('score')
                                      ->count();
        $gradedCount = $allSubmissions->whereNotNull('score')->count();
        $averageGrade = $gradedCount > 0 ? $allSubmissions->whereNotNull('score')->avg('score') : 0;

        return view('teacher.assignments.submissions', compact(
            'assignment',
            'students',
            'submissions',
            'totalStudents',
            'submittedCount',
            'pendingCount',
            'gradedCount',
            'averageGrade'
        ));
    }

    /**
     * Show the grading form for a specific submission
     */
    public function gradeForm(AssignmentSubmission $submission)
    {
        // Load relationships
        $submission->load(['student.user', 'assignment.lesson.course.teacher']);
        
        // Authorization: Check if the authenticated teacher owns this course
        if ($submission->assignment->lesson->course->teacher->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to grade this submission.');
        }

        return view('teacher.submissions.grade', compact('submission'));
    }

    /**
     * Update the grade for a submission
     */
    public function updateGrade(Request $request, AssignmentSubmission $submission)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string|min:10',
            'private_notes' => 'nullable|string',
        ]);

        // Update submission with grade
        $submission->update([
            'score' => $validated['score'],
            'feedback' => $validated['feedback'],
            'private_notes' => $validated['private_notes'] ?? null,
            'status' => 'graded',
            'graded_by' => Auth::id(),
            'graded_at' => now(),
        ]);

        // Check if all assignments in this lesson are now graded
        $lesson = $submission->assignment->lesson;
        $course = $lesson->course;
        $student = \App\Models\User::find($submission->user_id);
        
        if ($lesson && $course && $student) {
            // Get all assignment IDs for this lesson
            $assignmentIds = $lesson->assignments()->pluck('id');
            
            // Check if all assignments are graded for this student
            $allGradedCount = \App\Models\AssignmentSubmission::where('user_id', $student->id)
                ->whereIn('assignment_id', $assignmentIds)
                ->where('status', 'graded')
                ->count();
            
            if ($allGradedCount === $assignmentIds->count()) {
                // All assignments graded - update enrollment to completed
                $enrollment = $student->enrollments()->where('course_id', $course->id)->first();
                
                if ($enrollment && $enrollment->course_status === 'pending_approval') {
                    $enrollment->course_status = 'completed';
                    $enrollment->completed_at = now();
                    $enrollment->save();
                    
                    // Notify student that course is completed
                    Notification::create([
                        'user_id' => $student->id,
                        'sender_id' => Auth::id(),
                        'type' => 'course_completion',
                        'title' => 'Course Completed!',
                        'message' => 'Congratulations! You have successfully completed the course "' . $course->title . '". All your assignments have been graded and approved.',
                        'action_url' => route('client.courses.show', $course->slug),
                        'data' => [
                            'course_id' => $course->id,
                            'course_title' => $course->title,
                        ],
                        'priority' => 'high',
                        'is_read' => false,
                    ]);
                }
            }
        }

        // Create notification for the student about the grade
        Notification::create([
            'user_id' => $submission->user_id,
            'sender_id' => Auth::id(),
            'type' => 'grade',
            'title' => 'Assignment Graded',
            'message' => 'Your assignment "' . $submission->assignment->title . '" has been graded. You scored ' . $validated['score'] . '/100.',
            'action_url' => route('client.assignments.show', $submission->assignment_id),
            'data' => [
                'assignment_id' => $submission->assignment_id,
                'submission_id' => $submission->id,
                'score' => $validated['score'],
                'assignment_title' => $submission->assignment->title,
            ],
            'priority' => 'normal',
            'is_read' => false,
        ]);

        return redirect()
            ->route('teacher.assignments.submissions', $submission->assignment)
            ->with('success', 'Submission graded successfully!');
    }

    /**
     * Download a submission file
     */
    public function downloadSubmission($submissionId)
    {
        $submission = AssignmentSubmission::with('assignment.lesson.course.teacher', 'student.user')
            ->findOrFail($submissionId);
        
        // Authorization: Check if the authenticated teacher owns this course
        if ($submission->assignment->lesson->course->teacher->user_id !== Auth::id()) {
            abort(403, 'You are not authorized to download this file.');
        }

        if (!$submission->submission_file) {
            abort(404, 'File not found');
        }

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($submission->submission_file)) {
            abort(404, 'File not found in storage');
        }

        // Get student name and clean it for filename
        $studentName = $submission->student->user->name ?? 'student';
        $studentName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $studentName);
        
        // Get extension from the stored file
        $extension = pathinfo($submission->submission_file, PATHINFO_EXTENSION);
        
        // Create clean filename
        $assignmentTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $submission->assignment->title);
        $downloadName = $studentName . '_' . $assignmentTitle . '.' . $extension;
        
        // Use Storage facade for proper file download
        return Storage::disk('public')->download($submission->submission_file, $downloadName);
    }
}
