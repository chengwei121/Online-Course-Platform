<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add performance indexes for authentication
        $this->createIndexSafely('users', ['email'], 'idx_users_email_performance');
        $this->createIndexSafely('users', ['email', 'password'], 'idx_users_auth_lookup');
        $this->createIndexSafely('users', ['role'], 'idx_users_role');
        $this->createIndexSafely('users', ['email', 'role'], 'idx_users_email_role');
        $this->createIndexSafely('users', ['created_at'], 'idx_users_created_at');
        $this->createIndexSafely('users', ['updated_at'], 'idx_users_updated_at');
        
        // Optimize sessions table for faster session lookups
        $this->createIndexSafely('sessions', ['user_id', 'last_activity'], 'idx_sessions_user_activity');
        $this->createIndexSafely('sessions', ['last_activity'], 'idx_sessions_last_activity');
        
        // Optimize password reset tokens
        $this->createIndexSafely('password_reset_tokens', ['created_at'], 'idx_password_reset_created_at');
        
        // Add role-based indexes for faster dashboard queries
        $this->createIndexSafely('users', ['role', 'created_at'], 'idx_users_role_created');
        $this->createIndexSafely('users', ['role', 'updated_at'], 'idx_users_role_updated');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the indexes we created
        $indexes = [
            'users' => [
                'idx_users_email_performance',
                'idx_users_auth_lookup',
                'idx_users_role',
                'idx_users_email_role',
                'idx_users_created_at',
                'idx_users_updated_at',
                'idx_users_role_created',
                'idx_users_role_updated'
            ],
            'sessions' => [
                'idx_sessions_user_activity',
                'idx_sessions_last_activity'
            ],
            'password_reset_tokens' => [
                'idx_password_reset_created_at'
            ]
        ];

        foreach ($indexes as $table => $tableIndexes) {
            foreach ($tableIndexes as $index) {
                $this->dropIndexSafely($table, $index);
            }
        }
    }

    /**
     * Safely create an index if it doesn't exist
     */
    private function createIndexSafely(string $table, array $columns, string $indexName): void
    {
        try {
            $columnList = implode(',', $columns);
            
            // Check if index already exists
            $exists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.statistics 
                WHERE table_schema = DATABASE() 
                AND table_name = ? 
                AND index_name = ?
            ", [$table, $indexName]);
            
            if ($exists[0]->count == 0) {
                DB::statement("CREATE INDEX {$indexName} ON {$table}({$columnList})");
                echo "✓ Created index {$indexName} on {$table}\n";
            } else {
                echo "- Index {$indexName} already exists on {$table}\n";
            }
        } catch (\Exception $e) {
            echo "✗ Failed to create index {$indexName} on {$table}: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Safely drop an index if it exists
     */
    private function dropIndexSafely(string $table, string $indexName): void
    {
        try {
            // Check if index exists
            $exists = DB::select("
                SELECT COUNT(*) as count 
                FROM information_schema.statistics 
                WHERE table_schema = DATABASE() 
                AND table_name = ? 
                AND index_name = ?
            ", [$table, $indexName]);
            
            if ($exists[0]->count > 0) {
                DB::statement("DROP INDEX {$indexName} ON {$table}");
                echo "✓ Dropped index {$indexName} from {$table}\n";
            }
        } catch (\Exception $e) {
            echo "✗ Failed to drop index {$indexName} from {$table}: " . $e->getMessage() . "\n";
        }
    }
};