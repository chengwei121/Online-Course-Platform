<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:optimize {--create-indexes : Create database indexes for better performance}';

    /**
     * The console command description.
     */
    protected $description = 'Optimize database performance by creating indexes and updating statistics';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database optimization...');

        if ($this->option('create-indexes')) {
            $this->createIndexes();
        }

        $this->updateDatabaseStatistics();
        $this->optimizeTables();

        $this->info('Database optimization completed successfully!');
    }

    /**
     * Create database indexes for better query performance
     */
    private function createIndexes()
    {
        $this->info('Creating database indexes...');

        $indexes = [
            // Courses table indexes
            'courses' => [
                ['status'],
                ['category_id'],
                ['teacher_id'],
                ['is_free'],
                ['level'],
                ['average_rating'],
                ['created_at'],
                ['status', 'category_id'],
                ['status', 'is_free'],
                ['status', 'level'],
                ['status', 'average_rating']
            ],
            
            // Enrollments table indexes
            'enrollments' => [
                ['student_id'],
                ['course_id'],
                ['payment_status'],
                ['student_id', 'course_id'],
                ['course_id', 'payment_status']
            ],
            
            // Lessons table indexes
            'lessons' => [
                ['course_id'],
                ['order_index'],
                ['course_id', 'order_index']
            ],
            
            // Lesson progress table indexes
            'lesson_progress' => [
                ['user_id'],
                ['lesson_id'],
                ['completed'],
                ['user_id', 'lesson_id'],
                ['lesson_id', 'completed']
            ],
            
            // Course reviews table indexes
            'course_reviews' => [
                ['course_id'],
                ['user_id'],
                ['rating'],
                ['course_id', 'rating'],
                ['course_id', 'user_id']
            ],
            
            // Categories table indexes
            'categories' => [
                ['name'],
                ['slug']
            ],
            
            // Users table indexes (if not already exists)
            'users' => [
                ['email'],
                ['role'],
                ['created_at']
            ]
        ];

        foreach ($indexes as $table => $tableIndexes) {
            if (!Schema::hasTable($table)) {
                $this->warn("Table {$table} does not exist, skipping...");
                continue;
            }

            foreach ($tableIndexes as $columns) {
                $indexName = $table . '_' . implode('_', $columns) . '_index';
                
                try {
                    if (!$this->indexExists($table, $indexName)) {
                        Schema::table($table, function ($table) use ($columns, $indexName) {
                            $table->index($columns, $indexName);
                        });
                        $this->line("Created index: {$indexName}");
                    } else {
                        $this->line("Index already exists: {$indexName}");
                    }
                } catch (\Exception $e) {
                    $this->error("Failed to create index {$indexName}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Check if index exists
     */
    private function indexExists($table, $indexName)
    {
        $connection = Schema::getConnection();
        $driverName = $connection->getDriverName();

        if ($driverName === 'mysql') {
            $result = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
            return !empty($result);
        }

        return false;
    }

    /**
     * Update database statistics
     */
    private function updateDatabaseStatistics()
    {
        $this->info('Updating database statistics...');

        try {
            $connection = Schema::getConnection();
            $driverName = $connection->getDriverName();

            if ($driverName === 'mysql') {
                $tables = ['courses', 'enrollments', 'lessons', 'lesson_progress', 'course_reviews', 'categories', 'users'];
                
                foreach ($tables as $table) {
                    if (Schema::hasTable($table)) {
                        DB::statement("ANALYZE TABLE {$table}");
                        $this->line("Analyzed table: {$table}");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('Failed to update statistics: ' . $e->getMessage());
        }
    }

    /**
     * Optimize database tables
     */
    private function optimizeTables()
    {
        $this->info('Optimizing database tables...');

        try {
            $connection = Schema::getConnection();
            $driverName = $connection->getDriverName();

            if ($driverName === 'mysql') {
                $tables = ['courses', 'enrollments', 'lessons', 'lesson_progress', 'course_reviews', 'categories', 'users'];
                
                foreach ($tables as $table) {
                    if (Schema::hasTable($table)) {
                        DB::statement("OPTIMIZE TABLE {$table}");
                        $this->line("Optimized table: {$table}");
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('Failed to optimize tables: ' . $e->getMessage());
        }
    }
}