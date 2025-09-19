<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Lesson;

class FixVideoUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:video-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix video URLs to use relative paths instead of full URLs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing video URLs...');

        $lessons = Lesson::whereNotNull('video_url')
            ->where('video_url', 'like', '/storage/%')
            ->get();

        $count = 0;
        foreach ($lessons as $lesson) {
            // Convert /storage/videos/... to videos/...
            $newUrl = str_replace('/storage/', '', $lesson->video_url);
            $lesson->update(['video_url' => $newUrl]);
            $count++;
            $this->line("Fixed lesson: {$lesson->title}");
        }

        $this->info("Fixed {$count} video URLs successfully!");
        return 0;
    }
}
