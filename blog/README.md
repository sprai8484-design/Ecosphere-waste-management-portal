# 🌿 Ecosphere Blog Module
## Complete XAMPP Setup Guide

---

## 📁 File Structure
```
blog-module/
├── config.php                  ← DB + shared helpers
├── blog.php                    ← Public blog listing page
├── blog_single.php             ← Individual article view
├── submit_blog.php             ← User blog submission form
├── user_dashboard.php          ← User tracks their submission status
├── database.sql                ← ⚡ Run this first!
├── uploads/                    ← Auto-created; stores cover images
├── api/
│   ├── submit_blog.php         ← POST: insert new blog (status=pending)
│   ├── fetch_blogs.php         ← GET:  approved public blogs
│   ├── fetch_single_blog.php   ← GET:  single approved blog by ID
│   ├── fetch_user_blogs.php    ← GET:  all blogs by email (any status)
│   ├── admin_fetch_blogs.php   ← GET:  all blogs for admin (auth required)
│   └── admin_blog_action.php   ← POST: approve/reject + note (auth required)
└── admin/
    ├── login.php               ← Admin login
    └── manage.php              ← Full admin management panel
```

---

## ⚡ Quick Start (5 Steps)

### Step 1 — Place Files
```
C:\xampp\htdocs\blog-module\
```

### Step 2 — Start XAMPP
Open XAMPP Control Panel → Start **Apache** + **MySQL**

### Step 3 — Create Database
1. Go to `http://localhost/phpmyadmin`
2. Click the **SQL** tab
3. Paste the entire contents of `database.sql`
4. Click **Go**

Creates `ecosphere_db` with the `blogs` and `blog_admin_users` tables,
plus 6 approved seed articles and the default admin user.

### Step 4 — Open the Site
```
http://localhost/blog-module/blog.php
```

### Step 5 — Admin Panel
```
http://localhost/blog-module/admin/login.php

Username: admin
Password: admin123
```

---

## 🔄 Complete User Journey

### Submitting a Blog
1. Visit `submit_blog.php`
2. Fill in author name, email, title, content
3. The system checks content is **waste/environment related** (keyword matching)
4. Blog is inserted with `status = pending`
5. User receives confirmation

### Tracking Status
1. Visit `user_dashboard.php`
2. Enter the email used during submission
3. See all submitted blogs with:
   - Status badge (Pending / Approved / Rejected)
   - Admin's note explaining the decision
   - Timestamp of review

### Admin Review
1. Login at `admin/login.php`
2. See all submissions with pending count in sidebar
3. Filter by status, search by title/author/email
4. Click **Approve** or **Reject** on any blog
5. Write a mandatory admin note
6. Blog status updates instantly; only approved blogs appear publicly

---

## 🗄️ Database

### `blogs` Table
| Column      | Type       | Notes                                    |
|-------------|------------|------------------------------------------|
| id          | INT PK AI  |                                          |
| title       | VARCHAR    |                                          |
| content     | LONGTEXT   | Supports basic HTML                      |
| image       | VARCHAR    | Path under uploads/                      |
| author_name | VARCHAR    |                                          |
| email       | VARCHAR    | Used for dashboard lookup, not public    |
| status      | ENUM       | pending / approved / rejected            |
| admin_note  | TEXT       | Admin's review message                   |
| reviewed_by | VARCHAR    | Admin username who actioned it           |
| reviewed_at | TIMESTAMP  | When the action was taken                |
| created_at  | TIMESTAMP  |                                          |
| updated_at  | TIMESTAMP  | Auto-updates                             |

### `blog_admin_users` Table
| Column    | Type    | Notes                    |
|-----------|---------|--------------------------|
| id        | INT     |                          |
| username  | VARCHAR |                          |
| password  | VARCHAR | SHA-256 hashed           |

---

## 🔌 API Endpoints

| File                         | Method | Auth    | Purpose                                  |
|------------------------------|--------|---------|------------------------------------------|
| `api/fetch_blogs.php`        | GET    | None    | Public: approved blogs (search/paginate) |
| `api/fetch_single_blog.php`  | GET    | None    | Public: one approved blog by `?id=`      |
| `api/submit_blog.php`        | POST   | None    | Submit new blog (multipart/form-data)    |
| `api/fetch_user_blogs.php`   | GET    | None    | User dashboard: blogs by `?email=`       |
| `api/admin_fetch_blogs.php`  | GET    | Session | Admin: all blogs with counts             |
| `api/admin_blog_action.php`  | POST   | Session | Admin: approve/reject + note             |

---

## 🎨 Key Features

### Content Validation
- Blogs must contain keywords related to: waste, recycle, composting,
  sustainability, environment, plastic, eco, circular economy, etc.
- Real-time relevance indicator on the submit form
- Minimum 100 characters of content required
- Title minimum 10 characters

### Admin Panel Features
- Dashboard stats (total / pending / approved / rejected)
- Filter by status tabs
- Full-text search across title, author, email
- Pagination (20 per page)
- Review modal with mandatory admin note
- Pre-filled note templates for approve/reject
- Pending count badge in sidebar updates live

### User Dashboard
- Email-only lookup (no account needed)
- Status badges with colour coding
- Full admin note displayed
- Reviewer name + timestamp shown
- Direct link to read approved articles

---

## 🔒 Security Notes (for production)
- Change admin password immediately
- Move `config.php` above the web root
- Add CSRF tokens to all forms
- Rate-limit the submit endpoint
- Use HTTPS
- Sanitise all user input (already done via `clean()`)

---

*Built for Ecosphere Environmental Journal 🌍*
