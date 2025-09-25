<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CheckTableColumns extends Command
{
    protected $signature = 'db:check-columns';
    protected $description = 'Check table columns for instructor references';

    public function handle()
    {
        $this->info('🔍 Checking for instructor_id columns...');
        
        $tables = ['courses', 'lessons', 'assignments', 'enrollments'];
        
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $columns = Schema::getColumnListing($table);
                $this->line("📋 {$table} table columns:");
                foreach ($columns as $column) {
                    if (str_contains($column, 'instructor')) {
                        $this->error("  ❌ {$column} (INSTRUCTOR REFERENCE FOUND)");
                    } else {
                        $this->line("  ✅ {$column}");
                    }
                }
                $this->line('');
            }
        }
        
        // Check foreign key constraints
        $this->info('🔗 Checking foreign key constraints...');
        $constraints = DB::select("
            SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
            WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
            AND (COLUMN_NAME LIKE '%instructor%' OR REFERENCED_TABLE_NAME LIKE '%instructor%')
        ");
        
        if (count($constraints) > 0) {
            foreach ($constraints as $constraint) {
                $this->error("❌ Foreign Key: {$constraint->TABLE_NAME}.{$constraint->COLUMN_NAME} -> {$constraint->REFERENCED_TABLE_NAME}.{$constraint->REFERENCED_COLUMN_NAME}");
            }
        } else {
            $this->info('✅ No instructor foreign key constraints found');
        }
        
        return 0;
    }
}
