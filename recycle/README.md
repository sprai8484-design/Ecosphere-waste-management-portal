# 🌿 Ecosphere — Recycle Module
## XAMPP Setup Guide

---

## 📁 Folder Structure

```
ecosphere/
├── config.php              ← DB connection & helpers
├── recycle.php             ← Main user-facing page
├── fetch_centers.php       ← API: load recycling centers (JSON)
├── send_request.php        ← API: submit recycling request (JSON)
├── track.php               ← API: track by ID (JSON)
├── database.sql            ← SQL setup (run this first!)
├── uploads/                ← Auto-created; stores uploaded images
└── admin/
    ├── login.php           ← Admin login
    ├── index.php           ← Dashboard
    ├── requests.php        ← View & update requests
    ├── centers.php         ← Add/edit/delete recycling centers
    ├── auth.php            ← Session guard (auto-included)
    ├── _layout.php         ← Shared sidebar + topbar
    └── _layout_end.php     ← Closing tags
```

---

## ⚡ Quick Start (5 steps)

### Step 1 — Place files
Copy the `ecosphere/` folder to:
```
C:\xampp\htdocs\ecosphere\
```

### Step 2 — Start XAMPP
- Open **XAMPP Control Panel**
- Click **Start** for both **Apache** and **MySQL**

### Step 3 — Create the database
1. Open your browser → go to `http://localhost/phpmyadmin`
2. Click **SQL** (top menu)
3. Paste the **entire contents** of `database.sql`
4. Click **Go**

This creates the `ecosphere_db` database, all tables, and seeds demo data.

### Step 4 — Open the site
```
http://localhost/ecosphere/recycle.php
```

### Step 5 — Admin panel
```
http://localhost/ecosphere/admin/login.php

Username: admin
Password: admin123
```

---

## 🧪 Testing the Tracker

Three demo requests are seeded with these IDs:
| Tracking ID    | User         | Waste    | Status           |
|----------------|--------------|----------|------------------|
| ECO-2025-0001  | Priya Sharma | Plastic  | Processing (3)   |
| ECO-2025-0002  | Rahul Mehta  | E-Waste  | Product Ready (5)|
| ECO-2025-0003  | Sneha K.     | Fabric   | Submitted (1)    |

Enter any of these in the **Track** section of `recycle.php`.

---

## 🔧 Customisation

### Change DB password (if yours is not empty)
Edit `config.php`:
```php
define('DB_PASS', 'your_password_here');
```

### Add a new admin user
In phpMyAdmin → SQL:
```sql
USE ecosphere_db;
INSERT INTO admin_users (username, password)
VALUES ('newadmin', SHA2('mypassword', 256));
```

### Uploads folder permissions (Linux/Mac)
```bash
chmod 755 ecosphere/uploads/
```

---

## 📋 API Reference

| Endpoint           | Method | Description                        |
|--------------------|--------|------------------------------------|
| `fetch_centers.php?location=Mumbai` | GET | Get centers by city/pincode |
| `fetch_centers.php?waste_types=Plastic,E-Waste` | GET | Filter by waste type |
| `send_request.php` | POST   | Submit a recycling request (FormData) |
| `track.php?id=ECO-2025-0001` | GET | Get status by tracking ID |

---

## 🗄️ Database Tables

### `recycle_centers`
| Column       | Type        | Notes                        |
|--------------|-------------|------------------------------|
| id           | INT PK AI   |                              |
| name         | VARCHAR(150)|                              |
| address      | VARCHAR(300)|                              |
| city         | VARCHAR(100)|                              |
| pincode      | VARCHAR(10) |                              |
| waste_types  | VARCHAR(300)| Comma-separated              |
| contact      | VARCHAR(50) |                              |
| email        | VARCHAR(100)| Optional                     |
| timings      | VARCHAR(100)|                              |
| is_active    | TINYINT(1)  | 1=Active, 0=Deactivated      |

### `recycle_requests`
| Column         | Type         | Notes                              |
|----------------|--------------|------------------------------------|
| id             | INT PK AI    |                                    |
| tracking_id    | VARCHAR(20)  | UNIQUE, e.g. ECO-2025-0001         |
| user_name      | VARCHAR(100) |                                    |
| email          | VARCHAR(100) |                                    |
| phone          | VARCHAR(15)  |                                    |
| address        | TEXT         |                                    |
| city           | VARCHAR(100) |                                    |
| pincode        | VARCHAR(10)  |                                    |
| waste_type     | VARCHAR(50)  |                                    |
| description    | TEXT         |                                    |
| custom_request | TEXT         | Optional                           |
| image          | VARCHAR(255) | Path to uploads/                   |
| method         | ENUM         | 'pickup' or 'drop'                 |
| center_id      | INT FK       | Links to recycle_centers           |
| status         | TINYINT(1)   | 1–6 (see below)                    |
| admin_notes    | TEXT         | Internal notes                     |
| created_at     | TIMESTAMP    |                                    |
| updated_at     | TIMESTAMP    | Auto-updates on change             |

**Status codes:**
```
1 = Request Submitted
2 = Item Picked Up
3 = Processing Started
4 = Recycling Completed
5 = Product Ready
6 = Delivered Back
```

---

## 🛡️ Security Notes (for production)
- Change the admin password immediately
- Move `config.php` above the web root
- Add CSRF tokens to forms
- Use HTTPS
- Rate-limit the `send_request.php` endpoint

---

*Built for Ecosphere — turning waste into wonder 🌍*
