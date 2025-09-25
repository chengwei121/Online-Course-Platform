# 🎓 LearnHub - Online Course Platform

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=for-the-badge&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0-orange?style=for-the-badge&logo=mysql)
![PayPal](https://img.shields.io/badge/PayPal-Integration-blue?style=for-the-badge&logo=paypal)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap)
![Tailwind](https://img.shields.io/badge/Tailwind-3.0-cyan?style=for-the-badge&logo=tailwindcss)

A comprehensive, modern online course platform built with Laravel 11, featuring PayPal payment integration, multi-role user management, and a professional learning management system with comprehensive loading states and optimized performance.

## 🌟 Features

### 👨‍🎓 Student Features
- **Course Browsing & Enrollment**: Browse courses by category, instructor, and pricing
- **Secure Payment Processing**: PayPal integration for premium course purchases
- **Learning Dashboard**: Track progress, view enrolled courses, and manage learning
- **Progress Tracking**: Real-time lesson completion tracking with visual progress indicators
- **Course Reviews**: Rate and review completed courses
- **Payment History**: View all payment transactions and course purchases
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices

### 👨‍🏫 Teacher Features
- **Course Creation**: Create comprehensive courses with lessons, assignments, and media
- **Content Management**: Upload videos, documents, and course materials
- **Student Management**: Monitor student progress and engagement
- **Assignment Grading**: Review and grade student submissions
- **Revenue Tracking**: View earnings from course sales

### 👨‍💼 Admin Features
- **User Management**: Manage students, teachers, and administrators
- **Course Oversight**: Approve, edit, and manage all courses
- **Payment Management**: Monitor all transactions and revenue
- **Analytics Dashboard**: View platform statistics and performance metrics
- **Content Moderation**: Review and moderate user-generated content

## 🚀 Quick Start Guide

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL/MariaDB
- Node.js & NPM
- XAMPP or similar local server environment

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/chengwei121/Online-Course-Platform.git
   cd Online-Course-Platform
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build assets**
   ```bash
   npm run build
   ```

6. **Start the server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the platform.

## 🔐 Demo Accounts

Use these pre-configured accounts to explore different platform features:

### 🎓 Student Account
- **Email**: `student@example.com`
- **Password**: `password`
- **Features**: Browse courses, make payments, track progress, write reviews

### 👨‍🏫 Teacher Account
- **Email**: `teacher@example.com`
- **Password**: `password`
- **Features**: Create courses, manage content, track student progress

### 👨‍💼 Admin Account
- **Email**: `admin@example.com`
- **Password**: `password`
- **Features**: Full platform management, user oversight, payment monitoring

## 📱 How to Use the Platform

### For Students

1. **Registration & Login**
   - Visit the homepage and click "Register"
   - Fill in your details or use the demo student account
   - Verify your email (if email verification is enabled)

2. **Browsing Courses**
   - Navigate to "Courses" to view all available courses
   - Filter by category, price (Free/Premium), or instructor
   - Click on any course to view detailed information

3. **Enrolling in Courses**
   - **Free Courses**: Click "Enroll Now" to immediately access
   - **Premium Courses**: Click "Purchase Course" to proceed with PayPal payment
   - After payment, you'll be automatically enrolled

4. **Learning Dashboard**
   - Access "My Learning" to view all enrolled courses
   - Track your progress with visual indicators
   - Filter courses by completion status
   - Continue where you left off

5. **Taking Courses**
   - Click "Start Learning" or "Continue Learning" on any enrolled course
   - Navigate through lessons using the sidebar
   - Mark lessons as complete to track progress
   - Download course materials if available

6. **Reviews & Feedback**
   - Rate and review courses after completion
   - View your review history in "My Reviews"
   - Help other students by sharing your experience

### For Teachers

1. **Teacher Dashboard**
   - Login with teacher credentials
   - Access the teacher dashboard to manage your courses
   - View student enrollment statistics

2. **Creating Courses**
   - Click "Create New Course"
   - Add course title, description, category, and pricing
   - Upload course thumbnail and materials
   - Create lessons with video content and assignments

3. **Managing Students**
   - Monitor student progress in your courses
   - Review assignment submissions
   - Provide feedback and grades

### For Administrators

1. **Admin Panel**
   - Access comprehensive platform management tools
   - Monitor all users, courses, and transactions
   - Review platform analytics and performance metrics

2. **User Management**
   - Approve teacher applications
   - Manage user accounts and permissions
   - Handle user support requests

## 💳 Payment Integration

### PayPal Setup
The platform uses PayPal for secure payment processing:

1. **Sandbox Testing** (Default)
   - Uses PayPal sandbox environment for testing
   - No real money transactions
   - Perfect for development and demonstration

2. **Production Setup**
   - Update `.env` with live PayPal credentials
   - Change `PAYPAL_MODE` to `live`
   - Ensure SSL certificate is installed

### Supported Payment Methods
- PayPal Account
- Credit/Debit Cards (via PayPal)
- PayPal Credit (where available)

## 🛠️ Technical Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL/MariaDB
- **Payment**: PayPal REST API
- **Authentication**: Laravel Sanctum
- **File Storage**: Local/Cloud storage support
- **Build Tools**: Vite, NPM

## 🗄️ Database Schema - User Management

### 👤 User Types & Storage Locations

Your platform uses a **multi-table approach** for user management with role-based access control:

#### 📊 **Primary Users Table**: `users`
**Location**: All users are stored here with role identification
```sql
users table columns:
├── id (Primary Key)
├── name
├── email (Unique)
├── password (Hashed)
├── role (enum: 'student', 'instructor', 'admin') ← **KEY COLUMN**
├── email_verified_at
├── remember_token
└── timestamps (created_at, updated_at)
```

#### 🎓 **Students**: `users` table + `students` table
- **Primary Storage**: `users.role = 'student'`
- **Extended Info**: `students` table (linked via user_id foreign key)
```sql
students table columns:
├── id (Primary Key)
├── user_id (Foreign Key → users.id) ← **MAIN LINK**
├── name
├── email
├── avatar
├── phone
├── bio
├── date_of_birth
├── address
├── status (enum: 'active', 'inactive')
└── timestamps
```

#### 👨‍🏫 **Teachers/Instructors**: `users` table + `teachers` table
- **Primary Storage**: `users.role = 'instructor'`
- **Extended Info**: `teachers` table (linked via user_id foreign key)

```sql
teachers table columns:
├── id (Primary Key)
├── user_id (Foreign Key → users.id) ← **MAIN LINK**
├── name
├── email (Unique)
├── phone
├── qualification
├── bio
├── profile_picture
├── department
├── date_of_birth  
├── address
├── hourly_rate
├── status (enum: 'active', 'inactive')
└── timestamps
```

#### 👨‍💼 **Admins**: `users` table only
- **Storage**: `users.role = 'admin'`
- **No additional table** - admin info stored directly in users table

### 🔍 **How to Query Each User Type**

#### Find Students:
```php
// Method 1: From users table
$students = User::where('role', 'student')->get();

// Method 2: Using relationship
$user = User::find(1);
$studentInfo = $user->student; // Gets from students table

// Method 3: Direct from students table
$student = Student::with('user')->find(1);
```

#### Find Teachers/Instructors:
```php
// Method 1: From users table
$teachers = User::where('role', 'instructor')->get();

// Method 2: Using relationship 
$user = User::find(1);
$teacherInfo = $user->teacher; // Gets from teachers table

// Method 3: Direct from teachers table
$teacher = Teacher::with('user')->find(1);
```

#### Find Admins:
```php
$admins = User::where('role', 'admin')->get();
```

### 🎯 **Key Relationships**

```
users (Primary)
├── role = 'student' → students table (by user_id FK)
├── role = 'instructor' → teachers table (by user_id FK)
└── role = 'admin' → (no additional table)

courses table
└── teacher_id → teachers table (by teacher_id FK)
```

### 📋 **Summary Table**

| User Type | Primary Location | Extended Data | Key Column | Relationship |
|-----------|------------------|---------------|------------|--------------|
| **👨‍🎓 Students** | `users.role = 'student'` | `students` table | `students.user_id` | Foreign Key |
| **👨‍🏫 Teachers** | `users.role = 'instructor'` | `teachers` table | `teachers.user_id` | Foreign Key |
| **👨‍💼 Admins** | `users.role = 'admin'` | None | `users.role` | Direct storage |

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # Application controllers
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   └── Policies/            # Authorization policies
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── public/
    ├── images/             # Course images and assets
    └── storage/            # Uploaded files
```

## 🔧 Configuration

### Environment Variables
Key settings in `.env`:

```env
APP_NAME="Online Course Platform"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_course_platform
DB_USERNAME=root
DB_PASSWORD=

PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_client_secret
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -am 'Add feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For support, email chengweishia@gmail.com or create an issue in the GitHub repository.

## � Recent Updates & Changelog

### 🚀 Version 2.1.0 (Latest - September 2025)
- ✅ **Comprehensive Loading System**: Professional loading states across all pages
- ✅ **Header Animation Removal**: Optimized header for better performance
- ✅ **PayPal Integration Enhancement**: Improved payment confirmation flow
- ✅ **Database Optimization**: Fixed teacher_id column relationships
- ✅ **Excel Package Integration**: Added maatwebsite/excel for data export
- ✅ **Mobile Responsiveness**: Enhanced mobile experience
- ✅ **UI/UX Improvements**: Glassmorphism design with professional aesthetics

### 📊 Key Metrics
- **Database Tables**: 12 core tables with optimized relationships
- **User Roles**: 3 distinct roles (Student, Teacher, Admin)
- **Payment Methods**: PayPal integration with sandbox/live modes
- **Responsive Design**: Mobile-first approach with 3 breakpoints
- **Loading States**: 4 different loading components for various use cases

## 🎯 Roadmap & Future Enhancements

### 🔜 Upcoming Features (v2.2.0)
- 📱 **Progressive Web App**: PWA capabilities for mobile installation
- 🤖 **AI Course Recommendations**: ML-powered course suggestions
- 📊 **Advanced Analytics**: Detailed learning analytics dashboard
- 💬 **Real-time Chat**: Student-teacher communication system
- 🌍 **Multi-language Support**: Internationalization (i18n)

### 🎮 Long-term Vision (v3.0.0)
- 📹 **Live Streaming**: Real-time virtual classrooms
- 🏆 **Gamification**: Achievement badges and leaderboards
- 🔗 **API Development**: RESTful API for third-party integrations
- 📱 **Mobile App**: React Native mobile application
- 🔐 **Advanced Security**: OAuth2 and multi-factor authentication

## 📈 Performance Metrics

### ⚡ Loading Performance
- **Page Load Time**: < 2 seconds average
- **Loading States**: Comprehensive system with 4 component types
- **Animation Optimization**: Header animations removed for better performance
- **Responsive Design**: Optimized for all screen sizes

### 🔒 Security Features
- **CSRF Protection**: Built-in Laravel CSRF tokens
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **Password Security**: bcrypt hashing with salt
- **Email Verification**: Account verification system
- **Role-based Access**: Granular permission system

## �🙏 Acknowledgments

- Laravel framework for the robust backend foundation
- PayPal for secure payment processing
- Tailwind CSS and Bootstrap for beautiful UI components
- Alpine.js for lightweight frontend reactivity
- All contributors who helped improve this platform

## 📞 Contact & Support

**Project Maintainer**: chengwei121
- 📧 **Email**: chengweishia@gmail.com
- 🐙 **GitHub**: [@chengwei121](https://github.com/chengwei121)
- 🌐 **Repository**: [Online-Course-Platform](https://github.com/chengwei121/Online-Course-Platform)

For support, bug reports, or feature requests, please create an issue in the GitHub repository.

---

**⭐ Star this repository if you find it helpful!**

**Built with ❤️ using Laravel 11 | Last Updated: September 2025**
