<div align="center">

# 🎓 LearnHub - Premium Online Course Platform

[![Laravel](https://img.shields.io/badge/Laravel-12.3.0-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![PayPal](https://img.shields.io/badge/PayPal-Integration-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

### *A modern, feature-rich Learning Management System (LMS) with seamless payment integration*

[🚀 Live Demo](#-demo-accounts) • [📖 Documentation](#-documentation) • [💡 Features](#-features) • [🤝 Contributing](#-contributing)

</div>

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Quick Start](#-quick-start)
- [Installation Guide](#-installation-guide)
- [Demo Accounts](#-demo-accounts)
- [User Guides](#-user-guides)
- [Architecture](#-architecture)
- [Performance](#-performance)
- [Security](#-security)
- [Recent Updates](#-recent-updates)
- [Roadmap](#-roadmap)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🌟 Overview

**LearnHub** is an enterprise-grade online course platform built with Laravel 12, designed to provide a seamless learning experience for students, powerful tools for instructors, and comprehensive management capabilities for administrators. 

With integrated PayPal payment processing, real-time progress tracking, automated email notifications, and performance-optimized architecture, LearnHub delivers a professional e-learning solution ready for production deployment.

---

## ✨ Key Features

### � Core Platform Features

<table>
<tr>
<td width="50%">

#### 👨‍🎓 **Student Experience**
- 🔍 Advanced course discovery with filters
- 💳 Secure PayPal payment integration
- 📊 Real-time progress tracking
- 🎥 HD video streaming with progress save
- 📝 Assignment submission system
- ⭐ Course rating & review system
- 📧 Automated enrollment confirmations
- 📱 Fully responsive mobile interface
- 🔔 In-app notification system
- 📈 Personal learning dashboard

</td>
<td width="50%">

#### 👨‍🏫 **Instructor Tools**
- 🎬 Easy course creation wizard
- 📹 Video upload with progress indicators
- 📚 Multi-lesson course structure
- 👥 Student enrollment tracking
- 💰 Revenue analytics dashboard
- 📧 New enrollment email alerts
- 📝 Assignment management
- 📊 Course performance metrics
- 🎨 Rich content editor
- 🔄 Bulk content operations

</td>
</tr>
<tr>
<td width="50%">

#### 👨‍💼 **Admin Panel**
- 📊 Comprehensive analytics dashboard
- 👥 User management (CRUD operations)
- 💼 Course approval & moderation
- 💳 Payment transaction monitoring
- 📧 Automated notification system
- 🔍 Advanced search & filters
- 📈 Revenue reports
- 🛡️ Security monitoring
- 📋 System logs viewer
- ⚙️ Platform configuration

</td>
<td width="50%">

#### � **Technical Excellence**
- ⚡ Queue-based email system
- 🔒 Robust authentication & authorization
- 🎨 Modern UI with Tailwind CSS
- 📱 Mobile-first responsive design
- 🔄 Real-time data updates
- 🗄️ Optimized database queries
- 📦 Laravel 12 architecture
- 🎭 Role-based access control (RBAC)
- 🔐 CSRF & XSS protection
- 📧 SMTP email integration

</td>
</tr>
</table>

### 💌 **Email Notification System**

**Comprehensive automated email notifications:**
- ✅ **Student Enrollment Confirmation** - Welcome email with course access
- ✅ **Instructor New Enrollment Alert** - Student enrollment notifications
- ✅ **Admin Enrollment Reports** - Complete enrollment overview
- ✅ **Payment Receipt** - Transaction confirmation emails
- ✅ **Password Reset** - Instant forgot password emails
- 🔄 **Background Queue Processing** - Zero wait time for users

### 🎨 **UI/UX Highlights**

- ⏳ **Smart Loading States** - Professional loading indicators for all operations
- 🎬 **Video Upload Progress** - Real-time upload progress with file size warnings
- ⚡ **Instant Form Feedback** - Loading overlays during form submissions
- 🎯 **Skill Management** - Clean, scrollable skill tags with proper wrapping
- 🌈 **Gradient Themes** - Modern gradient designs for better visual hierarchy
- 📊 **Progress Indicators** - Visual course completion tracking

---

## �️ Tech Stack

### Backend
- **Framework:** Laravel 12.3.0
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0+
- **Queue System:** Database-backed queues with worker processes
- **Authentication:** Laravel Sanctum & Session-based auth
- **Email:** SMTP (Gmail integration)

### Frontend
- **Template Engine:** Blade
- **CSS Framework:** Tailwind CSS 3.4
- **JavaScript:** Alpine.js, Vanilla JS
- **Build Tool:** Vite
- **Icons:** Heroicons, Font Awesome

### Payment Integration
- **Gateway:** PayPal REST API
- **Modes:** Sandbox (testing) & Live (production)
- **Supported:** PayPal accounts, Credit/Debit cards

### Development Tools
- **Dependency Manager:** Composer, NPM
- **Version Control:** Git
- **Server:** Apache (XAMPP) / Nginx
- **Package Manager:** Composer 2.x

---

## 🚀 Quick Start

### System Requirements

```
✅ PHP >= 8.2
✅ Composer >= 2.0
✅ Node.js >= 16.x & NPM
✅ MySQL >= 8.0 or MariaDB >= 10.3
✅ Apache/Nginx web server
✅ Git
```

### One-Command Setup

```bash
# Clone and setup everything
git clone https://github.com/chengwei121/Online-Course-Platform.git
cd Online-Course-Platform
composer install && npm install && cp .env.example .env
php artisan key:generate && php artisan migrate:fresh --seed
npm run build && php artisan serve
```

Access at: **http://localhost:8000** 🎉

---

## 📥 Installation Guide

### Step 1: Clone Repository

```bash
git clone https://github.com/chengwei121/Online-Course-Platform.git
cd Online-Course-Platform
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Database Setup

**1. Create MySQL Database:**
```sql
CREATE DATABASE online_course_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**2. Update `.env` file:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=online_course_platform
DB_USERNAME=root
DB_PASSWORD=your_password
```

**3. Run Migrations:**
```bash
# Run migrations and seed demo data
php artisan migrate:fresh --seed
```

### Step 5: Configure Queue System

**Update `.env` for email queues:**
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

**Run queue migrations:**
```bash
php artisan queue:table
php artisan migrate
```

### Step 6: PayPal Configuration

**Add to `.env`:**
```env
PAYPAL_MODE=sandbox
PAYPAL_SANDBOX_CLIENT_ID=your_sandbox_client_id
PAYPAL_SANDBOX_CLIENT_SECRET=your_sandbox_secret

# For production:
PAYPAL_LIVE_CLIENT_ID=your_live_client_id
PAYPAL_LIVE_CLIENT_SECRET=your_live_secret
```

### Step 7: Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### Step 8: Start Services

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:work --tries=3 --timeout=90
```

**Or use batch files (Windows):**
- Double-click `start-server.bat` - Starts Laravel
- Double-click `start-queue.bat` - Starts queue worker

### Step 9: Access Platform

🌐 **Application:** http://localhost:8000
👨‍💼 **Admin Panel:** http://localhost:8000/admin/dashboard
👨‍🏫 **Teacher Panel:** http://localhost:8000/teacher/dashboard

---

## � Demo Accounts

<table>
<tr>
<th>Role</th>
<th>Email</th>
<th>Password</th>
<th>Access Level</th>
</tr>
<tr>
<td>👨‍🎓 <strong>Student</strong></td>
<td><code>student@example.com</code></td>
<td><code>password</code></td>
<td>Course browsing, enrollment, learning, reviews</td>
</tr>
<tr>
<td>👨‍🏫 <strong>Teacher</strong></td>
<td><code>teacher@example.com</code></td>
<td><code>password</code></td>
<td>Course creation, content management, student tracking</td>
</tr>
<tr>
<td>👨‍💼 <strong>Admin</strong></td>
<td><code>admin@example.com</code></td>
<td><code>password</code></td>
<td>Full platform control, user management, analytics</td>
</tr>
</table>

> **💡 Tip:** Use these accounts to explore all platform features without creating new accounts!

---

## 📖 User Guides

### 🎓 For Students

<details>
<summary><strong>Click to expand Student Guide</strong></summary>

#### 1️⃣ Registration & Login
- Navigate to the homepage
- Click **"Register"** or use demo account: `student@example.com`
- Fill in your details and verify email (if enabled)

#### 2️⃣ Browsing Courses
- Click **"Courses"** in navigation
- Use filters: Category, Price (Free/Premium), Instructor
- Click any course card to view detailed information

#### 3️⃣ Enrolling in Courses

**Free Courses:**
- Click **"Enroll Now"** button
- Instant access granted
- Start learning immediately

**Premium Courses:**
- Click **"Purchase Course"** button
- Redirected to PayPal checkout
- Complete payment securely
- Automatic enrollment after payment
- Receive confirmation email

#### 4️⃣ Learning Experience
- Access **"My Learning"** dashboard
- View all enrolled courses with progress indicators
- Click **"Continue Learning"** to resume
- Navigate lessons via sidebar
- Mark lessons complete to track progress
- Download course materials

#### 5️⃣ Course Reviews
- Complete at least 50% of course content
- Rate course (1-5 stars)
- Write detailed review
- View your review history

</details>

### 👨‍🏫 For Teachers

<details>
<summary><strong>Click to expand Teacher Guide</strong></summary>

#### 1️⃣ Teacher Dashboard
- Login with teacher credentials
- Access dashboard at `/teacher/dashboard`
- View statistics: Students, Courses, Revenue

#### 2️⃣ Creating Courses
- Click **"Create New Course"**
- Fill course information:
  - Title & Description
  - Category selection
  - Price (Free or Premium)
  - Learning hours estimate
  - Prerequisites & skills
- Upload thumbnail image
- Click **"Create Course"** (loading indicator shows progress)

#### 3️⃣ Adding Lessons
- Go to course management page
- Click **"Add Lesson"**
- Enter lesson details:
  - Title & Description
  - Video upload (supports MP4, MOV, AVI, WMV)
  - Progress bar shows upload status
  - WMV file warning for large files
- Set lesson order
- Add lesson materials (PDFs, documents)

#### 4️⃣ Managing Enrollments
- View enrolled students list
- Track student progress
- Monitor completion rates
- Receive email notifications for new enrollments

#### 5️⃣ Revenue Tracking
- Access earnings dashboard
- View total revenue
- Export payment reports
- Track enrollment trends

</details>

### 👨‍💼 For Administrators

<details>
<summary><strong>Click to expand Admin Guide</strong></summary>

#### 1️⃣ Admin Dashboard
- Login with admin credentials
- Access at `/admin/dashboard`
- View platform-wide statistics

#### 2️⃣ User Management
- Navigate to **Users** section
- View all students and teachers
- Create/Edit/Delete user accounts
- Manage user roles and permissions
- Approve teacher applications

#### 3️⃣ Course Management
- Access **Courses** section
- View all courses (published & drafts)
- Edit course details
- Approve/Reject course submissions
- Monitor course quality
- View enrollment statistics

#### 4️⃣ Payment Monitoring
- Access **Payments** section
- View all transactions
- Filter by date, status, user
- Export payment reports
- Monitor revenue analytics
- Receive enrollment notification emails

#### 5️⃣ System Configuration
- Manage categories
- Configure email templates
- Set platform fees
- Manage PayPal settings
- Monitor system logs

</details>

---

## 🏗️ Architecture

### Database Schema

<details>
<summary><strong>View Complete Database Structure</strong></summary>

#### Core Tables

**👤 User Management**
```
users
├── id (PK)
├── name
├── email (Unique)
├── password (Hashed)
├── role (enum: student, instructor, admin)
├── email_verified_at
└── timestamps

students (extends users)
├── id (PK)
├── user_id (FK → users.id)
├── avatar
├── phone
├── bio
├── date_of_birth
└── status

teachers (extends users)
├── id (PK)
├── user_id (FK → users.id)
├── qualification
├── department
├── profile_picture
├── hourly_rate
└── status
```

**📚 Course System**
```
courses
├── id (PK)
├── teacher_id (FK → teachers.id)
├── category_id (FK → categories.id)
├── title
├── slug (Unique)
├── description
├── price
├── learning_hours
├── level (beginner, intermediate, advanced)
├── status (draft, published)
└── timestamps

lessons
├── id (PK)
├── course_id (FK → courses.id)
├── title
├── video_path
├── duration
├── order
└── timestamps

lesson_progress
├── id (PK)
├── user_id (FK → users.id)
├── lesson_id (FK → lessons.id)
├── is_completed
├── last_position (video timestamp)
└── timestamps
```

**💳 Payment & Enrollment**
```
enrollments
├── id (PK)
├── user_id (FK → users.id)
├── course_id (FK → courses.id)
├── payment_status (pending, completed, failed)
├── amount_paid
├── enrolled_at
└── status

payments (via PayPal)
├── Transaction ID
├── Amount
├── Status
├── PayPal Order ID
└── Timestamps
```

**📧 Notifications**
```
notifications
├── id (PK)
├── user_id (FK → users.id)
├── type
├── title
├── message
├── data (JSON)
├── read_at
└── timestamps

jobs (queue system)
├── id (PK)
├── queue
├── payload (JSON)
├── attempts
├── reserved_at
└── available_at
```

</details>

### User Type Relationships

```
┌─────────────────────────────────────────┐
│           users (Primary)               │
│  ┌───────────────────────────────────┐  │
│  │  role = 'student'                 │──────► students table
│  ├───────────────────────────────────┤  │
│  │  role = 'instructor'              │──────► teachers table
│  ├───────────────────────────────────┤  │
│  │  role = 'admin'                   │      (no additional table)
│  └───────────────────────────────────┘  │
└─────────────────────────────────────────┘
                    │
                    ▼
            ┌───────────────┐
            │    courses    │
            │ (teacher_id)  │
            └───────┬───────┘
                    │
                    ▼
            ┌───────────────┐
            │    lessons    │
            └───────────────┘
```

### Queue System Architecture

```
User Action (Payment/Password Reset)
        ↓
Controller adds job to queue
        ↓
Jobs stored in database (jobs table)
        ↓
Queue Worker processes jobs
        ↓
Email sent via SMTP
        ↓
User receives email (background)
```

---

## ⚡ Performance

### Optimizations Implemented

- ✅ **Database Query Optimization**: Eager loading, indexed columns
- ✅ **Queue System**: Background email processing (zero wait time)
- ✅ **Asset Compilation**: Vite for optimized CSS/JS bundles
- ✅ **Image Optimization**: Lazy loading, responsive images
- ✅ **Caching Strategy**: Session, database, and route caching
- ✅ **CDN-Free Tailwind**: Production-ready compiled CSS

### Performance Metrics

| Metric | Target | Status |
|--------|--------|--------|
| Page Load Time | < 2s | ✅ Achieved |
| Email Queue Response | < 500ms | ✅ Achieved |
| Video Upload Feedback | Real-time | ✅ Implemented |
| Database Queries | < 20 per page | ✅ Optimized |

### Loading Indicators

- 📤 **Video Upload Progress**: Real-time progress bar with file size detection
- ⏳ **Form Submission**: Loading overlays during processing
- 📧 **Email Queue**: Instant response, background sending
- 🔄 **Course Operations**: Professional loading states

---

## 🔒 Security

### Security Features

- 🛡️ **CSRF Protection**: Laravel's built-in token validation
- 🔐 **Password Hashing**: Bcrypt with automatic salting
- 🚫 **SQL Injection Prevention**: Eloquent ORM with prepared statements
- 🔒 **XSS Protection**: Blade template escaping
- 🎭 **Role-Based Access Control (RBAC)**: Granular permissions
- 📧 **Email Verification**: Account confirmation system
- 🔑 **Secure Password Reset**: Token-based reset flow
- 💳 **Payment Security**: PayPal secure checkout (PCI DSS compliant)

### Best Practices

```php
// CSRF Protection (automatic in forms)
@csrf

// XSS Prevention
{{ $userInput }}  // Auto-escaped
{!! $trustedHtml !!}  // Only for trusted content

// Authorization
@can('update', $course)
    // Only authorized users see this
@endcan

// Password Hashing
Hash::make($password);  // Never store plain text
```

---

## 🔄 Recent Updates

### 🚀 Version 3.0.0 (November 2025) - Current

#### 📧 **Comprehensive Email Notification System**
- ✅ **Student Enrollment Confirmation**: Automated welcome emails with course access details
- ✅ **Instructor Enrollment Alerts**: Real-time notifications when students enroll
- ✅ **Admin Enrollment Reports**: Complete overview of all platform enrollments
- ✅ **Queue-Based Processing**: Instant page response, emails sent in background
- ✅ **Professional HTML Templates**: Beautiful, responsive email designs
- 📄 **Documentation**: Complete guide in `ENROLLMENT_EMAIL_SYSTEM.md`

#### ⚡ **Performance & UX Enhancements**
- ✅ **Smart Loading Indicators**: Professional loading states across all forms
- ✅ **Video Upload Progress**: Real-time progress bars with file size warnings
- ✅ **Instant Email Response**: Forgot password now responds in < 500ms
- ✅ **Background Job Processing**: Database queue system with retry logic
- ✅ **Form Submission Overlays**: Clear feedback during long operations

#### 🎨 **UI/UX Improvements**
- ✅ **Skills Overflow Fix**: Proper text wrapping with scrollable containers
- ✅ **Course Management Visibility**: Enhanced green gradient design
- ✅ **Duration Display Fix**: Correctly shows `learning_hours` field
- ✅ **Forgot Password Redesign**: Professional grey background (no distracting gradients)
- ✅ **Mobile Responsiveness**: Optimized for all screen sizes

#### 🔧 **Technical Improvements**
- ✅ **Queue System Setup**: Database-backed queue with worker processes
- ✅ **Batch File Helpers**: `start-server.bat` and `start-queue.bat` for easy startup
- ✅ **Video Skip Validation Removed**: 100% completion now works correctly
- ✅ **Laravel 12.3.0 Upgrade**: Latest framework features and security patches

### 📋 Previous Versions

<details>
<summary><strong>View Version History</strong></summary>

#### Version 2.1.0 (September 2025)
- Header animation optimization
- PayPal integration enhancement
- Database relationship fixes
- Excel export functionality
- Glassmorphism UI design

#### Version 2.0.0 (August 2025)
- Laravel 11 to 12 migration
- Tailwind CSS 3.4 integration
- Multi-role authentication system
- Complete admin panel redesign

#### Version 1.0.0 (July 2025)
- Initial release
- Core LMS features
- Basic PayPal integration
- Student/Teacher dashboards

</details>

---

## 🗺️ Roadmap

### 📅 Q1 2026 - Enhanced Learning Features

- [ ] **Live Virtual Classrooms** - Zoom/Google Meet integration
- [ ] **Discussion Forums** - Course-specific Q&A boards
- [ ] **Certificate Generation** - Automated completion certificates
- [ ] **Mobile App Development** - React Native iOS/Android apps
- [ ] **Advanced Analytics** - Student learning behavior insights

### 📅 Q2 2026 - AI & Automation

- [ ] **AI Course Recommendations** - ML-powered personalized suggestions
- [ ] **Automated Grading** - AI-assisted assignment evaluation
- [ ] **Chatbot Support** - 24/7 AI customer service
- [ ] **Content Moderation** - Automated inappropriate content detection
- [ ] **Speech-to-Text** - Auto-generate lesson transcripts

### 📅 Q3 2026 - Platform Expansion

- [ ] **Multi-Language Support** - i18n for global reach
- [ ] **Cryptocurrency Payments** - Bitcoin/Ethereum integration
- [ ] **Affiliate Program** - Referral system for instructors
- [ ] **API Marketplace** - Public REST API for third-party integrations
- [ ] **White-Label Solution** - Customizable branding options

### 📅 Q4 2026 - Enterprise Features

- [ ] **SSO Integration** - SAML/OAuth for enterprise clients
- [ ] **LTI Compliance** - Integration with existing LMS platforms
- [ ] **Advanced Reporting** - Custom report builder
- [ ] **Multi-Tenancy** - Support for multiple institutions
- [ ] **Compliance Tools** - GDPR/COPPA/FERPA compliance features

---

## 🤝 Contributing

We welcome contributions from the community! Here's how you can help:

### 🐛 Bug Reports

Found a bug? Please create an issue with:
- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- Screenshots (if applicable)
- Environment details (PHP version, browser, etc.)

### ✨ Feature Requests

Have an idea? Create an issue with:
- Detailed feature description
- Use case/problem it solves
- Mockups or examples (if applicable)
- Potential implementation approach

### 💻 Pull Requests

1. **Fork the repository**
   ```bash
   git clone https://github.com/yourusername/Online-Course-Platform.git
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Make your changes**
   - Write clean, documented code
   - Follow PSR-12 coding standards
   - Add tests if applicable

4. **Commit your changes**
   ```bash
   git commit -m "Add: Amazing new feature"
   ```

5. **Push to your branch**
   ```bash
   git push origin feature/amazing-feature
   ```

6. **Open a Pull Request**
   - Describe your changes clearly
   - Reference any related issues
   - Ensure CI/CD checks pass

### 📝 Coding Standards

- Follow PSR-12 PHP coding standards
- Use meaningful variable and function names
- Write comments for complex logic
- Keep functions small and focused
- Write tests for new features

---

## 📚 Documentation

### Available Guides

- 📧 **[Email System Documentation](ENROLLMENT_EMAIL_SYSTEM.md)** - Complete email notification setup
- ⚡ **[Queue Setup Guide](QUEUE_SETUP_GUIDE.md)** - Background job processing configuration
- 🚀 **[Performance Optimizations](PERFORMANCE_OPTIMIZATIONS.md)** - Speed and efficiency improvements
- 🔧 **[Login System Fixes](LOGIN_FIX_SUMMARY.md)** - Authentication troubleshooting

### Additional Resources

- [Laravel Documentation](https://laravel.com/docs) - Framework reference
- [PayPal API Docs](https://developer.paypal.com/api/rest/) - Payment integration
- [Tailwind CSS Docs](https://tailwindcss.com/docs) - Styling framework

---

## 📄 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for details.

```
MIT License

Copyright (c) 2025 chengwei121

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 📞 Support & Contact

### 🆘 Get Help

- 📧 **Email**: chengweishia@gmail.com
- 🐛 **Bug Reports**: [GitHub Issues](https://github.com/chengwei121/Online-Course-Platform/issues)
- 💬 **Discussions**: [GitHub Discussions](https://github.com/chengwei121/Online-Course-Platform/discussions)

### 👨‍💻 Project Maintainer

**chengwei121**
- 🐙 GitHub: [@chengwei121](https://github.com/chengwei121)
- 📧 Email: chengweishia@gmail.com
- 🌐 Repository: [Online-Course-Platform](https://github.com/chengwei121/Online-Course-Platform)

### 🌟 Show Your Support

If you find this project helpful, please consider:
- ⭐ **Star** the repository
- 🍴 **Fork** for your own projects
- 📢 **Share** with others
- 🐛 **Report bugs** you find
- 💡 **Suggest features** you'd like

---

## 🙏 Acknowledgments

Special thanks to:

- **Laravel Team** - For the robust PHP framework
- **PayPal Developers** - For comprehensive payment API
- **Tailwind Labs** - For the amazing utility-first CSS framework
- **Alpine.js Team** - For lightweight reactivity
- **Open Source Community** - For inspiration and support
- **All Contributors** - Everyone who helped improve this platform

---

<div align="center">

### 🎓 **LearnHub** - Empowering Education Through Technology

**Built with ❤️ using Laravel 12 & Tailwind CSS**

[![GitHub Stars](https://img.shields.io/github/stars/chengwei121/Online-Course-Platform?style=social)](https://github.com/chengwei121/Online-Course-Platform)
[![GitHub Forks](https://img.shields.io/github/forks/chengwei121/Online-Course-Platform?style=social)](https://github.com/chengwei121/Online-Course-Platform/fork)
[![GitHub Issues](https://img.shields.io/github/issues/chengwei121/Online-Course-Platform)](https://github.com/chengwei121/Online-Course-Platform/issues)

**Last Updated: November 10, 2025** | **Version 3.0.0**

[⬆ Back to Top](#-learnhub---premium-online-course-platform)

</div>
