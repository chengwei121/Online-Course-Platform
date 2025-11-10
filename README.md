# LearnHub - Online Course Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.3.0-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg?style=flat-square)](LICENSE)

A modern Learning Management System (LMS) built with Laravel 12, featuring PayPal payment integration, real-time progress tracking, and automated email notifications. Designed for students, instructors, and administrators.

---

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Demo Accounts](#demo-accounts)
- [User Guides](#user-guides)
- [Database Architecture](#database-architecture)
- [Performance](#performance)
- [Security](#security)
- [Recent Updates](#recent-updates)
- [Contributing](#contributing)
- [License](#license)

---

## Overview

LearnHub is a comprehensive online course platform built with Laravel 12, providing a seamless learning experience for students, powerful course creation tools for instructors, and complete management capabilities for administrators.

The platform includes integrated PayPal payment processing, real-time progress tracking, automated email notifications, and performance-optimized architecture suitable for production deployment.

---

## Key Features

### For Students
- Advanced course discovery with category and price filters
- Secure PayPal payment integration
- Real-time progress tracking with visual indicators
- HD video streaming with automatic progress save
- Assignment submission system
- Course rating and review system
- Automated enrollment confirmation emails
- Fully responsive mobile interface
- In-app notification system
- Personal learning dashboard

### For Instructors
- Intuitive course creation wizard
- Video upload with real-time progress indicators
- Multi-lesson course structure
- Student enrollment tracking
- Revenue analytics dashboard
- Automated new enrollment email alerts
- Assignment management system
- Course performance metrics
- Rich content editor

### For Administrators
- Comprehensive analytics dashboard
- User management (CRUD operations)
- Course approval and moderation
- Payment transaction monitoring
- Automated notification system
- Advanced search and filters
- Revenue reports and analytics
- Security monitoring tools
- System logs viewer
- Platform configuration settings

### Technical Highlights
- Queue-based email system for instant response
- Robust authentication and authorization
- Modern UI with Tailwind CSS
- Mobile-first responsive design
- Real-time data updates
- Optimized database queries
- Role-based access control (RBAC)
- CSRF and XSS protection
- SMTP email integration

---

## Tech Stack

**Backend**
- Laravel 12.3.0
- PHP 8.2+
- MySQL 8.0+
- Database-backed queue system

**Frontend**
- Blade Templates
- Tailwind CSS 3.4
- Alpine.js
- Vite

**Payment**
- PayPal REST API (Sandbox & Live modes)

**Tools**
- Composer 2.x
- NPM
- Git

---

## Installation

### Prerequisites

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 16.x & NPM
- MySQL >= 8.0 or MariaDB >= 10.3
- Apache/Nginx web server

### Quick Setup

```bash
# Clone repository
git clone https://github.com/chengwei121/Online-Course-Platform.git
cd Online-Course-Platform

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate:fresh --seed

# Build assets
npm run build

# Start server
php artisan serve
```

### Detailed Installation

**Step 1: Database Configuration**

Create a MySQL database:
```sql
CREATE DATABASE online_course_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_course_platform
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Step 2: Queue System Setup**

Configure queue in `.env`:
```env
QUEUE_CONNECTION=database

# Gmail SMTP Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Run queue migrations:
```bash
php artisan queue:table
php artisan migrate
```

**Step 3: PayPal Configuration**

Add to `.env`:
```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_secret
```

**Step 4: Start Services**

Terminal 1 - Laravel Server:
```bash
php artisan serve
```

Terminal 2 - Queue Worker:
```bash
php artisan queue:work --tries=3 --timeout=90
```

Or use the provided batch files (Windows):
- `start-server.bat` - Starts Laravel
- `start-queue.bat` - Starts queue worker

Access the application at: **http://localhost:8000**

---

## Demo Accounts

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| **Student** | student@example.com | password | Course browsing, enrollment, learning, reviews |
| **Teacher** | teacher@example.com | password | Course creation, content management, student tracking |
| **Admin** | admin@example.com | password | Full platform control, user management, analytics |

---

## User Guides

### Student Guide

**Registration & Enrollment**
1. Register or login with demo account
2. Browse courses using category/price filters
3. For free courses: Click "Enroll Now"
4. For premium courses: Click "Purchase Course" → Complete PayPal payment
5. Receive confirmation email

**Learning Experience**
1. Access "My Learning" dashboard
2. View enrolled courses with progress indicators
3. Click "Continue Learning" to resume
4. Mark lessons complete to track progress
5. Rate and review courses

### Teacher Guide

**Creating Courses**
1. Login to teacher dashboard
2. Click "Create New Course"
3. Fill course information (title, description, category, price)
4. Upload thumbnail image
5. Add lessons with video content
6. Publish course

**Managing Enrollments**
1. View enrolled students list
2. Track student progress
3. Receive email notifications for new enrollments
4. Monitor revenue in analytics dashboard

### Admin Guide

**Platform Management**
1. Access admin dashboard
2. Manage users (students, teachers, admins)
3. Approve/reject courses
4. Monitor payment transactions
5. View platform-wide analytics
6. Configure system settings

---

## Database Architecture

### User Management

The platform uses a multi-table approach for user management:

**users** table (primary)
- Stores all users with `role` column (student, instructor, admin)
- Common fields: id, name, email, password, role, timestamps

**students** table
- Extends users with `user_id` foreign key
- Additional fields: avatar, phone, bio, date_of_birth, status

**teachers** table
- Extends users with `user_id` foreign key
- Additional fields: qualification, department, profile_picture, hourly_rate

**Admins**
- Stored only in users table with `role = 'admin'`

### Course System

**courses**
- Links to teachers via `teacher_id`
- Fields: title, slug, description, price, learning_hours, level, status

**lessons**
- Links to courses via `course_id`
- Fields: title, video_path, duration, order

**lesson_progress**
- Tracks student progress per lesson
- Fields: user_id, lesson_id, is_completed, last_position

### Enrollment & Payments

**enrollments**
- Tracks student course enrollments
- Fields: user_id, course_id, payment_status, amount_paid, enrolled_at

**jobs** (queue system)
- Stores background jobs for email processing
- Processed by queue worker

---

## Performance

### Optimizations

- Database query optimization with eager loading
- Background email processing (zero wait time)
- Vite for optimized asset bundling
- Image lazy loading
- Session and route caching
- Production-ready compiled CSS

### Metrics

| Metric | Target | Status |
|--------|--------|--------|
| Page Load Time | < 2s | Achieved |
| Email Queue Response | < 500ms | Achieved |
| Video Upload Feedback | Real-time | Implemented |
| Database Queries | < 20 per page | Optimized |

---

## Security

### Features

- CSRF Protection (Laravel built-in tokens)
- Password Hashing (Bcrypt with automatic salting)
- SQL Injection Prevention (Eloquent ORM with prepared statements)
- XSS Protection (Blade template escaping)
- Role-Based Access Control (RBAC)
- Email Verification
- Secure Password Reset (token-based)
- PayPal Secure Checkout (PCI DSS compliant)

---

## Recent Updates

### Version 3.0.0 (November 2025)

**Email Notification System**
- Student enrollment confirmation emails
- Instructor new enrollment alerts
- Admin enrollment reports
- Queue-based background processing
- Professional responsive email templates

**Performance & UX**
- Smart loading indicators across all forms
- Real-time video upload progress bars
- Instant email response (< 500ms)
- Background job processing with retry logic

**UI Improvements**
- Skills overflow fix with text wrapping
- Enhanced course management visibility
- Duration display fix (learning_hours field)
- Forgot password page redesign
- Mobile responsiveness improvements

**Technical**
- Database queue system setup
- Video skip validation removed
- Laravel 12.3.0 upgrade
- Batch file helpers for easy startup

---

## Contributing

We welcome contributions! Here's how:

**Bug Reports**
- Create an issue with clear description
- Include steps to reproduce
- Add screenshots if applicable

**Feature Requests**
- Create an issue with detailed description
- Explain use case and benefits

**Pull Requests**
1. Fork the repository
2. Create feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Commit: `git commit -m "Add your feature"`
5. Push: `git push origin feature/your-feature`
6. Open a Pull Request

**Coding Standards**
- Follow PSR-12 PHP standards
- Use meaningful variable names
- Write comments for complex logic
- Keep functions focused

---

## Documentation

- [Email System Documentation](ENROLLMENT_EMAIL_SYSTEM.md) - Email notification setup
- [Queue Setup Guide](QUEUE_SETUP_GUIDE.md) - Background job configuration
- [Performance Optimizations](PERFORMANCE_OPTIMIZATIONS.md) - Speed improvements

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Support & Contact

**Maintainer:** chengwei121  
**Email:** chengweishia@gmail.com  
**GitHub:** [@chengwei121](https://github.com/chengwei121)  
**Repository:** [Online-Course-Platform](https://github.com/chengwei121/Online-Course-Platform)

For support, bug reports, or feature requests, please create an issue in the GitHub repository.

---

**Built with Laravel 12 & Tailwind CSS**  
**Version 3.0.0 | Last Updated: November 10, 2025**
