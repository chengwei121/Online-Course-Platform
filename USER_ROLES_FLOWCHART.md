# 👥 LearnHub User Roles - Simplified Flowcharts

## 🎯 **Three Main User Roles Overview**

This document provides clear, focused flowcharts for each user role in your LearnHub platform.

---

## 👨‍🎓 **1. STUDENT Journey Flowchart**

```mermaid
graph TD
    A[👨‍🎓 Student Logs In] --> B[🏠 Student Dashboard]
    
    B --> C{What to do?}
    C -->|Browse Courses| D[🔍 Course Catalog]
    C -->|View My Courses| E[📚 My Learning]
    C -->|Check Progress| F[📊 My Progress]
    
    %% Browse Courses Path
    D --> G[📋 Filter Courses]
    G --> H[📖 Course Details]
    H --> I{Course Type?}
    I -->|Free| J[✅ Enroll Instantly]
    I -->|Paid| K[💳 PayPal Payment]
    
    K --> L{Payment Success?}
    L -->|Yes| M[🎓 Enrollment Complete]
    L -->|No| N[❌ Payment Failed]
    N --> H
    
    J --> M
    M --> O[📚 Access Course Content]
    
    %% My Learning Path
    E --> P[📋 Enrolled Courses List]
    P --> Q[▶️ Continue Learning]
    Q --> R[📖 Watch Lessons]
    R --> S[📝 Complete Assignments]
    S --> T[📊 Track Progress]
    T --> U{Course Complete?}
    U -->|No| R
    U -->|Yes| V[🎖️ Get Certificate]
    V --> W[⭐ Rate Course]
    
    %% Progress Path
    F --> X[📈 Completion Rates]
    X --> Y[🏆 Achievements]
    Y --> Z[📊 Learning Statistics]
    
    %% Learning Content
    O --> AA[🎥 Video Lessons]
    O --> BB[📄 Reading Materials]
    O --> CC[📝 Assignments]
    O --> DD[📋 Quizzes]
    
    AA --> EE[✅ Mark Complete]
    BB --> EE
    CC --> FF[📤 Submit Work]
    DD --> GG[📊 View Results]
    
    FF --> HH[⏳ Wait for Grading]
    HH --> II[📧 Grade Notification]
    
    EE --> JJ[📈 Update Progress]
    GG --> JJ
    II --> JJ
```

---

## 👨‍🏫 **2. TEACHER Journey Flowchart**

```mermaid
graph TD
    A[👨‍🏫 Teacher Logs In] --> B[🏠 Teacher Dashboard]
    
    B --> C{What to do?}
    C -->|Create Course| D[➕ New Course]
    C -->|Manage Courses| E[📚 My Courses]
    C -->|View Students| F[👥 Student Management]
    C -->|Check Earnings| G[💰 Revenue Analytics]
    
    %% Create Course Path
    D --> H[📝 Course Information]
    H --> I[Course Title & Description]
    I --> J[💰 Set Price (Free/Paid)]
    J --> K[📂 Select Category]
    K --> L[🖼️ Upload Thumbnail]
    L --> M[📚 Add Course Content]
    
    M --> N[➕ Create Lessons]
    N --> O{Content Type?}
    O -->|Video| P[🎥 Upload Video]
    O -->|Text| Q[📄 Write Content]
    O -->|Assignment| R[📝 Create Assignment]
    O -->|Quiz| S[❓ Build Quiz]
    
    P --> T[💾 Save Lesson]
    Q --> T
    R --> U[📋 Set Due Date & Points]
    U --> T
    S --> V[📊 Set Questions & Answers]
    V --> T
    
    T --> W{Add More Content?}
    W -->|Yes| N
    W -->|No| X[📤 Submit for Review]
    X --> Y[⏳ Wait for Admin Approval]
    Y --> Z{Admin Decision?}
    Z -->|Approved| AA[✅ Course Published]
    Z -->|Rejected| BB[❌ Needs Revision]
    BB --> M
    
    %% Manage Courses Path
    E --> CC[📋 Course List]
    CC --> DD{Course Action?}
    DD -->|Edit| EE[✏️ Edit Course]
    DD -->|View Stats| FF[📊 Course Analytics]
    DD -->|Manage Content| GG[📚 Content Manager]
    
    EE --> HH[📝 Update Information]
    HH --> II[💾 Save Changes]
    
    FF --> JJ[📈 Enrollment Numbers]
    JJ --> KK[⭐ Student Ratings]
    KK --> LL[💰 Revenue Data]
    
    GG --> MM[➕ Add New Lessons]
    GG --> NN[✏️ Edit Existing]
    GG --> OO[🗑️ Delete Content]
    
    %% Student Management Path
    F --> PP[👥 Enrolled Students]
    PP --> QQ[📊 Student Progress]
    QQ --> RR[📝 Grade Assignments]
    RR --> SS[💬 Provide Feedback]
    SS --> TT[📧 Send Notifications]
    
    %% Revenue Path
    G --> UU[💰 Total Earnings]
    UU --> VV[📊 Payment History]
    VV --> WW[📈 Monthly Reports]
```

---

## 👨‍💼 **3. ADMIN Journey Flowchart**

```mermaid
graph TD
    A[👨‍💼 Admin Logs In] --> B[🏠 Admin Dashboard]
    
    B --> C{Admin Tasks?}
    C -->|Review Courses| D[📋 Course Approvals]
    C -->|Manage Users| E[👥 User Management]
    C -->|Platform Stats| F[📊 Analytics]
    C -->|System Settings| G[⚙️ Configuration]
    
    %% Course Approval Path
    D --> H[📋 Pending Courses Queue]
    H --> I[📖 Review Course Content]
    I --> J[👨‍🏫 Check Teacher Profile]
    J --> K[💰 Verify Pricing]
    K --> L[📝 Quality Assessment]
    L --> M{Approval Decision?}
    
    M -->|Approve| N[✅ Publish Course]
    M -->|Reject| O[❌ Reject with Feedback]
    M -->|Need Changes| P[🔄 Request Revisions]
    
    N --> Q[📧 Notify Teacher - Approved]
    Q --> R[🌐 Course Goes Live]
    
    O --> S[📝 Write Rejection Reason]
    S --> T[📧 Send Feedback to Teacher]
    
    P --> U[📝 List Required Changes]
    U --> V[📧 Request Revision Email]
    
    %% User Management Path
    E --> W{User Type?}
    W -->|Students| X[👨‍🎓 Student Accounts]
    W -->|Teachers| Y[👨‍🏫 Teacher Accounts]
    W -->|Admins| Z[👨‍💼 Admin Accounts]
    
    X --> AA[📊 Student Statistics]
    AA --> BB[🚫 Suspend/Activate]
    BB --> CC[📧 User Notifications]
    
    Y --> DD[📚 Teacher Course Stats]
    DD --> EE[💰 Revenue Tracking]
    EE --> FF[✅ Verify Credentials]
    FF --> GG[🚫 Account Actions]
    
    Z --> HH[👥 Admin Permissions]
    HH --> II[➕ Add New Admin]
    II --> JJ[🔑 Role Management]
    
    %% Platform Analytics Path
    F --> KK[📈 Platform Overview]
    KK --> LL[📊 Total Courses]
    LL --> MM[👥 Total Users]
    MM --> NN[💰 Total Revenue]
    NN --> OO[⭐ Platform Rating]
    OO --> PP[📈 Growth Metrics]
    
    PP --> QQ[📋 Detailed Reports]
    QQ --> RR[📊 Course Performance]
    QQ --> SS[👨‍🏫 Teacher Performance]
    QQ --> TT[👨‍🎓 Student Engagement]
    
    %% System Settings Path
    G --> UU[⚙️ Platform Configuration]
    UU --> VV[💳 Payment Settings]
    VV --> WW[📧 Email Templates]
    WW --> XX[🔐 Security Settings]
    XX --> YY[📱 System Maintenance]
    
    %% Active Course Management
    D --> ZZ[📚 Active Courses]
    ZZ --> AAA{Course Action?}
    AAA -->|Edit| BBB[✏️ Admin Edit Course]
    AAA -->|Suspend| CCC[⏸️ Suspend Course]
    AAA -->|Delete| DDD[🗑️ Delete Course]
    
    BBB --> EEE[💾 Save Admin Changes]
    CCC --> FFF[📧 Suspension Notice]
    DDD --> GGG[⚠️ Confirm Deletion]
    GGG --> HHH[🗑️ Permanent Delete]
```

---

## 🔄 **4. Role Interaction Flowchart**

```mermaid
graph TD
    A[🌐 LearnHub Platform] --> B{User Login}
    
    B -->|Student Login| C[👨‍🎓 Student Dashboard]
    B -->|Teacher Login| D[👨‍🏫 Teacher Dashboard]
    B -->|Admin Login| E[👨‍💼 Admin Dashboard]
    
    %% Student Actions
    C --> F[🔍 Browse Courses]
    F --> G[📖 View Course by Teacher]
    G --> H[💳 Purchase Course]
    H --> I[📚 Learn Content]
    I --> J[⭐ Rate & Review]
    
    %% Teacher Actions
    D --> K[➕ Create Course]
    K --> L[📤 Submit for Approval]
    L --> M[👨‍💼 Admin Reviews]
    M --> N{Approved?}
    N -->|Yes| O[✅ Course Published]
    N -->|No| P[❌ Back to Teacher]
    P --> K
    
    O --> Q[👨‍🎓 Students Can Enroll]
    Q --> R[📊 Teacher Sees Analytics]
    
    %% Admin Actions
    E --> S[📋 Review Courses]
    S --> L
    E --> T[👥 Manage Users]
    T --> U[🚫 User Actions]
    U --> V[📧 Notify Users]
    
    E --> W[📊 Platform Analytics]
    W --> X[📈 Monitor Growth]
    
    %% Interactions
    J --> Y[📊 Update Course Rating]
    Y --> Z[👨‍🏫 Teacher Notification]
    Y --> AA[👨‍💼 Admin Analytics]
    
    H --> BB[💰 Payment Processing]
    BB --> CC[👨‍🏫 Teacher Earnings]
    CC --> DD[👨‍💼 Admin Revenue Tracking]
    
    I --> EE[📝 Submit Assignment]
    EE --> FF[👨‍🏫 Teacher Grades]
    FF --> GG[📧 Student Notification]
```

---

## 🎯 **Role Permissions Summary**

### 👨‍🎓 **STUDENT Can:**
- ✅ Browse and search courses
- ✅ Enroll in free courses instantly
- ✅ Purchase paid courses via PayPal
- ✅ Access enrolled course content
- ✅ Track learning progress
- ✅ Submit assignments and take quizzes
- ✅ Rate and review completed courses
- ✅ View certificates and achievements

### 👨‍🏫 **TEACHER Can:**
- ✅ Create and edit courses
- ✅ Upload content (videos, documents, assignments)
- ✅ Set course pricing (free or paid)
- ✅ Manage enrolled students
- ✅ Grade assignments and provide feedback
- ✅ View course analytics and earnings
- ✅ Communicate with students
- ❌ Cannot approve own courses (needs admin)

### 👨‍💼 **ADMIN Can:**
- ✅ Review and approve/reject courses
- ✅ Manage all users (students, teachers, admins)
- ✅ Access platform-wide analytics
- ✅ Configure system settings
- ✅ Suspend or delete courses/accounts
- ✅ Monitor payments and revenue
- ✅ Send platform-wide notifications
- ✅ Full system control and oversight

---

## 📊 **User Workflow Statistics**

- **👨‍🎓 Student Journey**: 8 main steps from registration to certification
- **👨‍🏫 Teacher Journey**: 12 main steps from course creation to earnings
- **👨‍💼 Admin Journey**: 15 main oversight and management functions
- **🔄 Role Interactions**: 10+ cross-role communication points

**🎯 Each role has distinct responsibilities that work together to create a comprehensive online learning ecosystem!**