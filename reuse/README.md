# 🌿 Ecosphere — Reuse Module
## Complete XAMPP Setup Guide

---

## 📁 File Structure

```
reuse-module/
├── config.php              ← DB connection & shared helpers
├── reuse.php               ← Main explore/browse page
├── reuse_details.php       ← Single idea detail + comments + tracking
├── add_idea.php            ← Submit new reuse idea form
├── database.sql            ← ⚡ Run this first!
├── uploads/                ← Auto-created; stores uploaded images
└── api/
    ├── fetch_ideas.php     ← GET  all ideas (filter/search/sort/paginate)
    ├── fetch_single.php    ← GET  one idea + comments + saved status
    ├── add_idea.php        ← POST submit new idea (multipart)
    ├── like.php            ← POST increment likes
    ├── save.php            ← POST toggle save/bookmark (session-based)
    └── comment.php         ← POST add comment
```

---

## ⚡ 5-Step Quick Start

### Step 1 — Place files
```
C:\xampp\htdocs\reuse-module\
```

### Step 2 — Start XAMPP
Open XAMPP Control Panel → Start **Apache** + **MySQL**

### Step 3 — Create the database
1. Go to `http://localhost/phpmyadmin`
2. Click the **SQL** tab at the top
3. Paste the full contents of `database.sql`
4. Click **Go**

This creates `ecosphere_db` with all three tables and 7 seed ideas + sample comments.

### Step 4 — Open the site
```
http://localhost/reuse-module/reuse.php
```

### Step 5 — Test it!
- Browse and filter ideas on the main page
- Click any card → opens `reuse_details.php`
- Like, save, comment, track progress through steps
- Go to `add_idea.php` to submit a new idea

---

## 🗄️ Database Tables

### `reuse_ideas`
| Column       | Type         | Notes                           |
|--------------|--------------|---------------------------------|
| id           | INT PK AI    |                                 |
| title        | VARCHAR(200) |                                 |
| category     | VARCHAR(60)  | Plastic/Clothes/Paper/etc.      |
| difficulty   | ENUM         | Easy / Medium / Hard            |
| time_required| VARCHAR(60)  | e.g. "45 minutes"               |
| description  | TEXT         |                                 |
| materials    | TEXT         | Comma-separated string          |
| steps        | LONGTEXT     | JSON array of step strings      |
| image        | VARCHAR(255) | Path under uploads/             |
| author       | VARCHAR(100) |                                 |
| likes        | INT UNSIGNED | Default 0                       |
| is_approved  | TINYINT(1)   | 1=visible (default), 0=pending  |
| created_at   | TIMESTAMP    |                                 |

### `reuse_comments`
| Column     | Type        | Notes                   |
|------------|-------------|-------------------------|
| id         | INT PK AI   |                         |
| idea_id    | INT FK      | → reuse_ideas.id        |
| name       | VARCHAR(100)|                         |
| comment    | TEXT        |                         |
| created_at | TIMESTAMP   |                         |

### `reuse_saved`
| Column          | Type        | Notes                      |
|-----------------|-------------|----------------------------|
| id              | INT PK AI   |                            |
| idea_id         | INT FK      | → reuse_ideas.id           |
| user_identifier | VARCHAR(150)| PHP session ID             |
| saved_at        | TIMESTAMP   |                            |

---

## 🔌 API Endpoints

| Endpoint               | Method | Key Params                               |
|------------------------|--------|------------------------------------------|
| `api/fetch_ideas.php`  | GET    | `search`, `category`, `difficulty`, `sort` (newest/popular), `page`, `limit` |
| `api/fetch_single.php` | GET    | `id` (idea ID)                           |
| `api/add_idea.php`     | POST   | multipart FormData with all fields       |
| `api/like.php`         | POST   | JSON `{ "idea_id": 5 }`                  |
| `api/save.php`         | POST   | JSON `{ "idea_id": 5 }`                  |
| `api/comment.php`      | POST   | JSON `{ "idea_id":5, "name":"…", "comment":"…" }` |

All endpoints return `Content-Type: application/json`.

---

## 🎨 Features Checklist

**Main Page (reuse.php)**
- [x] Dynamic card grid loaded from database
- [x] Live search with debounce
- [x] Category filter pills
- [x] Difficulty filter pills
- [x] Sort by newest / most popular
- [x] Pagination (9 per page)
- [x] Quick like + save from cards
- [x] Responsive grid

**Detail Page (reuse_details.php)**
- [x] Full description
- [x] Materials list as tags
- [x] Numbered step-by-step guide
- [x] Interactive step progress tracker (✓ each step)
- [x] Like button (with live count)
- [x] Save/bookmark button (session-based)
- [x] Related ideas sidebar
- [x] Comment form + live comment list
- [x] Share via WhatsApp / Twitter / copy link

**Add Idea (add_idea.php)**
- [x] All required fields with validation
- [x] Dynamic step builder (add/remove steps)
- [x] Difficulty selector (visual buttons)
- [x] Image upload with drag & drop + preview
- [x] Success message with link to new idea

---

## 🔧 Customisation

### Change DB credentials
Edit `config.php`:
```php
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
```

### Require admin approval for new ideas
In `api/add_idea.php`, change `is_approved, 1` to `is_approved, 0`.
Then approve in phpMyAdmin: `UPDATE reuse_ideas SET is_approved=1 WHERE id=X`

### Combine with Recycle module
Both modules share `ecosphere_db`. You can place both folders at the same level:
```
htdocs/
├── ecosphere/          ← Recycle module
└── reuse-module/       ← This module
```

---

*Built for Ecosphere — turning waste into wonder 🌍*
