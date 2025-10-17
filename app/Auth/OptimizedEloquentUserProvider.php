<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class OptimizedEloquentUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by their unique identifier with caching.
     */
    public function retrieveById($identifier)
    {
        $model = $this->createModel();

        // Cache user data for 30 minutes to avoid repeated DB queries
        return Cache::remember(
            "user_auth_{$identifier}",
            now()->addMinutes(30),
            function () use ($model, $identifier) {
                return $this->newModelQuery($model)
                    ->where($model->getAuthIdentifierName(), $identifier)
                    ->first();
            }
        );
    }

    /**
     * Retrieve a user by credentials with optimized query.
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             array_key_exists('password', $credentials))) {
            return;
        }

        // Clone the query to prevent multiple database hits
        $query = $this->newModelQuery();

        foreach ($credentials as $key => $value) {
            if (!str_contains($key, 'password')) {
                $query->where($key, $value);
            }
        }

        // Only select necessary columns for faster query
        $query->select([
            'id',
            'name',
            'email',
            'password',
            'role',
            'remember_token',
            'email_verified_at',
            'created_at',
            'updated_at'
        ]);

        return $query->first();
    }

    /**
     * Validate a user against the given credentials with optimized hashing.
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        if (is_null($plain = $credentials['password'])) {
            return false;
        }

        // Use Hash::check with optimized algorithm check
        return Hash::check($plain, $user->getAuthPassword());
    }

    /**
     * Clear cached user data when needed.
     */
    public function clearUserCache($identifier)
    {
        Cache::forget("user_auth_{$identifier}");
    }
}
