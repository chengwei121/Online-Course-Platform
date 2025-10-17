<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateAssignmentSubmissionsStudentId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'assignments:update-student-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing assignment submissions with student IDs based on user relationships';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating assignment submissions with student IDs...');
        
        $submissions = \App\Models\AssignmentSubmission::whereNull('student_id')->get();
        
        if ($submissions->count() === 0) {
            $this->info('No submissions to update!');
            return;
        }
        
        $updated = 0;
        $bar = $this->output->createProgressBar($submissions->count());
        
        foreach ($submissions as $submission) {
            if ($submission->user && $submission->user->student) {
                $submission->student_id = $submission->user->student->id;
                
                // Also set default status if not set
                if (!$submission->status) {
                    $submission->status = $submission->score !== null ? 'graded' : 'submitted';
                }
                
                $submission->save();
                $updated++;
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info("Successfully updated {$updated} submissions!");
    }
}
