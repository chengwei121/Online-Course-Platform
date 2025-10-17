<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'score' => 'required|numeric|min:0|max:100',
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

        return redirect()
            ->route('teacher.assignments.submissions', $submission->assignment)
            ->with('success', 'Submission graded successfully!');
    }
}
