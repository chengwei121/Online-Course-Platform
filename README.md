# 🎓 Online Course Platform with Payment Integration

A comprehensive online learning management system built with Laravel 11, featuring PayPal integration for secure payments, multi-role authentication, and a modern responsive design.

![Laravel](https://img.shields.io/badge/Laravel-v11.x-red?style=flat&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-v8.2+-blue?style=flat&logo=php)
![PayPal](https://img.shields.io/badge/PayPal-Integration-blue?style=flat&logo=paypal)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

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

For support, email support@example.com or create an issue in the GitHub repository.

## 🙏 Acknowledgments

- Laravel framework for the robust backend foundation
- PayPal for secure payment processing
- Tailwind CSS for the beautiful UI components
- All contributors who helped improve this platform

---

**Built with ❤️ using Laravel 11**
