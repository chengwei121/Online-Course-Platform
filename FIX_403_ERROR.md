# Fix 403 Unauthorized Error - Production Server Guide

## Problem
Getting "403 This action is unauthorized" error on production server.

## Step-by-Step Debug Process

### Step 1: Check User Authentication
Visit this URL on your production server:
```
https://your-domain.com/debug-auth
```

This will show you:
- If you're logged in
- Your user role
- Your permissions
- Session information

### Step 2: Common Causes & Solutions

#### Cause 1: User Role Not Set Correctly in Database
**Solution:**
```sql
-- Check user roles in database
SELECT id, name, email, role FROM users;

-- Fix admin role
UPDATE users SET role = 'admin' WHERE email = 'your-admin@email.com';

-- Fix teacher/instructor role
UPDATE users SET role = 'instructor' WHERE email = 'teacher@email.com';

-- Fix student role
UPDATE users SET role = 'student' WHERE email = 'student@email.com';
```

#### Cause 2: Missing Teacher/Student Record
If you have `role = 'instructor'` but no record in `teachers` table:

```sql
-- Check if teacher record exists
SELECT * FROM teachers WHERE user_id = YOUR_USER_ID;

-- Create teacher record if missing
INSERT INTO teachers (user_id, name, email, status, created_at, updated_at)
VALUES (YOUR_USER_ID, 'Teacher Name', 'teacher@email.com', 'active', NOW(), NOW());
```

#### Cause 3: Session Not Working on Production
**Check .env file on production:**
```env
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
```

**After changing .env, run:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```

#### Cause 4: File Permissions on Production
```bash
# Fix storage and cache permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 3: Quick Fixes

#### A. Clear All Caches
Visit: `https://your-domain.com/clear-cache`

Or run in terminal:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

#### B. Check Database Connection
```bash
php artisan tinker
```
Then type:
```php
\App\Models\User::count()
```
If this works, database is connected.

#### C. Verify User Can Login
1. Try logging in with your credentials
2. After login, visit `/debug-auth`
3. Check if `authenticated` is `true`
4. Check if `role` matches what you expect

### Step 4: Fix Production Environment

#### Update .env on Production Server
```env
APP_ENV=production
APP_DEBUG=false  # Set to true temporarily for debugging
APP_KEY=your-generated-key

DB_CONNECTION=mysql
DB_HOST=server621.iseencloud.com
DB_PORT=3306
DB_DATABASE=jomjomco_chengwei_Elearning_Platform
DB_USERNAME=jomjomco_chengwei
DB_PASSWORD=~@^TYK.AI,=e

QUEUE_CONNECTION=database
```

After updating .env:
```bash
php artisan config:cache
php artisan optimize
```

### Step 5: Test Specific Pages

#### Test Admin Access
1. Login as admin user
2. Visit: `/admin/dashboard`
3. If 403, check `/debug-auth` - role should be `admin`

#### Test Teacher Access
1. Login as teacher
2. Visit: `/teacher/dashboard`
3. If 403, check:
   - Role is `instructor` (not `teacher`)
   - Teacher record exists in `teachers` table

#### Test Student Access
1. Login as student
2. Visit: `/client/courses`
3. Should work without issues

### Step 6: SQL Scripts to Fix Data

```sql
-- Create admin user
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at)
VALUES ('Admin', 'admin@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW(), NOW());
-- Password is: password

-- Create teacher with teacher record
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at)
VALUES ('Teacher', 'teacher@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'instructor', NOW(), NOW(), NOW());

SET @teacher_user_id = LAST_INSERT_ID();

INSERT INTO teachers (user_id, name, email, status, created_at, updated_at)
VALUES (@teacher_user_id, 'Teacher', 'teacher@yourdomain.com', 'active', NOW(), NOW());

-- Create student with student record
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at)
VALUES ('Student', 'student@yourdomain.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW());

SET @student_user_id = LAST_INSERT_ID();

INSERT INTO students (user_id, name, email, status, created_at, updated_at)
VALUES (@student_user_id, 'Student', 'student@yourdomain.com', 'active', NOW(), NOW());
```

### Step 7: Remove Debug Route After Fixing

Once the issue is fixed, remove the debug route for security:

**In routes/web.php, delete these lines:**
```php
// Debug Authentication Route (Remove after debugging)
Route::get('/debug-auth', function() {
    // ... entire debug route ...
})->name('debug.auth');
```

## Quick Checklist

- [ ] Database connected successfully
- [ ] User can login
- [ ] User role is set correctly (`admin`, `instructor`, or `student`)
- [ ] If teacher: `teachers` table has record with correct `user_id`
- [ ] If student: `students` table has record with correct `user_id`
- [ ] Visited `/clear-cache` to clear all caches
- [ ] Visited `/debug-auth` to verify authentication
- [ ] File permissions correct (775 for storage)
- [ ] `.env` configured correctly
- [ ] Run `php artisan config:cache` after .env changes

## Still Having Issues?

1. Set `APP_DEBUG=true` in .env temporarily
2. Try to access the page that gives 403
3. You'll see detailed error message
4. Check Laravel logs: `storage/logs/laravel.log`

## Contact
If you still need help, provide:
- Output from `/debug-auth`
- Error message from `storage/logs/laravel.log`
- Which page gives 403 error
- What user role you're logged in as
