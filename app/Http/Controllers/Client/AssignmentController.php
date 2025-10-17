<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function show(Assignment $assignment)
    {
        $submission = AssignmentSubmission::where('user_id', Auth::id())
            ->where('assignment_id', $assignment->id)
            ->first();

        return view('client.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $request->validate([
            'content' => 'required|string',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        // Get student ID from authenticated user
        $student = Auth::user()->student;

        $submission = AssignmentSubmission::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'student_id' => $student ? $student->id : null,
                'assignment_id' => $assignment->id,
            ],
            [
                'submission_text' => $request->content,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]
        );

        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('assignment-submissions', 'public');
            $submission->update(['submission_file' => $path]);
        }

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
} 