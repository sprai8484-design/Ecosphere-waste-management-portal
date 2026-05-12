# 🌿 Ecosphere Resell Module
## Complete XAMPP Setup Guide

---

## 📁 File Structure
```
resell-module/
├── config.php                    ← DB + shared helpers
├── resell.php                    ← Public marketplace + buy + payment + receipt
├── sell.php                      ← List a new item (with live preview)
├── my_listings.php               ← Seller dashboard (track own listings)
├── database.sql                  ← ⚡ Run this first!
├── uploads/                      ← Auto-created; stores product images
├── api/
│   ├── submit_product.php        ← POST: submit new listing
│   ├── fetch_products.php        ← GET:  approved products (search/filter/paginate)
│   ├── fetch_single_product.php  ← GET:  single product detail
│   ├── fetch_my_listings.php     ← GET:  seller's listings by email
│   ├── process_payment.php       ← POST: dummy payment + transaction record
│   ├── admin_product_action.php  ← POST: approve/reject/delete (auth required)
│   └── admin_fetch_products.php  ← GET:  all products for admin (auth required)
└── admin/
    ├── login.php                 ← Admin login
    └── dashboard.php             ← Full admin management panel
```

---

## ⚡ Quick Start (5 Steps)

### Step 1 — Place Files
```
C:\xampp\htdocs\resell-module\
```

### Step 2 — Start XAMPP
Open XAMPP Control Panel → Start **Apache** + **MySQL**

### Step 3 — Create Database
1. `http://localhost/phpmyadmin`
2. Click **SQL** tab
3. Paste the full contents of `database.sql`
4. Click **Go**

Creates `ecosphere_db` with 4 tables + 5 demo approved products + admin user.

### Step 4 — Open the Site
```
http://localhost/resell-module/resell.php
```

### Step 5 — Admin Panel
```
http://localhost/resell-module/admin/login.php
Username: admin
Password: admin123
```

---

## 🛒 User Flow

### Buying
1. Browse `resell.php` → filter by category, condition, price
2. Click "View Details" → read full description
3. Click "Buy Now" → payment modal opens
4. Enter buyer details + card/UPI/COD
5. Payment processes (dummy) → receipt modal with transaction ID
6. Click "Print Receipt" → opens print dialog

### Selling
1. Visit `sell.php` → fill in product form
2. **Live preview** updates in real time as you type
3. Submit → get a Product UID (ECO-RSL-XXXXX)
4. Admin reviews and approves → item goes live
5. Track at `my_listings.php` using your email

### Tracking Listings
1. Visit `my_listings.php`
2. Enter your seller email
3. See all submissions with status badges + admin notes

---

## 🗄️ Database Tables

### `resell_products`
| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| product_uid | VARCHAR(20) | UNIQUE, e.g. ECO-RSL-00001 |
| user_id | INT FK | Links to resell_users |
| seller_name/email/phone | VARCHAR | Contact info |
| product_name | VARCHAR(255) | |
| category | VARCHAR(100) | |
| condition | VARCHAR(50) | New/Like New/Used/Damaged |
| quantity | INT | |
| price | DECIMAL(10,2) | |
| image | VARCHAR(255) | Path under uploads/ |
| status | ENUM | Pending/Approved/Sold/Rejected |
| admin_note | TEXT | Admin's review message |
| reviewed_by | VARCHAR | Admin username |

### `resell_transactions`
| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| transaction_uid | VARCHAR(25) | UNIQUE, e.g. TXN-ECO-XXXXXXXXX |
| product_id | INT FK | |
| buyer_name/email/phone | VARCHAR | |
| seller_name/email | VARCHAR | Snapshotted at purchase |
| product_name/uid | VARCHAR | Snapshotted at purchase |
| quantity | INT | |
| unit_price | DECIMAL(10,2) | |
| total_price | DECIMAL(10,2) | |
| payment_method | VARCHAR | Card/UPI/COD |
| payment_status | ENUM | Success/Failed/Refunded |
| card_last4 | VARCHAR(4) | Last 4 digits (demo only) |
| transaction_date | TIMESTAMP | |

---

## 💳 Payment Gateway (Demo/Sandbox)

The payment system is a **fully functional dummy gateway**:

- Simulates real card validation (number format, expiry, CVV)
- Supports Card, UPI, and Cash on Delivery
- **To simulate a declined card:** use any card number starting with `0000`
- All other valid-format cards will succeed
- Transactions are recorded in `resell_transactions`
- Product quantity is decremented; marks as `Sold` when qty hits 0
- Receipt is generated and printable

**Test Card Numbers:**
| Card | Result |
|------|--------|
| `4111 1111 1111 1111` | ✅ Success |
| `5500 0055 5555 5599` | ✅ Success |
| `0000 0000 0000 0000` | ❌ Declined |

---

## 🔌 API Reference

| Endpoint | Method | Params | Auth |
|----------|--------|--------|------|
| `api/fetch_products.php` | GET | search, category, condition, sort, page, limit | None |
| `api/fetch_single_product.php` | GET | id or uid | None |
| `api/submit_product.php` | POST | multipart/form-data | None |
| `api/process_payment.php` | POST | JSON body | None |
| `api/fetch_my_listings.php` | GET | email | None |
| `api/admin_fetch_products.php` | GET | status, search, page | Admin session |
| `api/admin_product_action.php` | POST | JSON {product_id, action, admin_note} | Admin session |

---

## 🔒 Security Features
- PDO prepared statements (SQL injection prevention)
- `htmlspecialchars()` on all output (XSS prevention)
- `finfo_open()` MIME type validation (safe file uploads)
- File extension whitelisting (JPG, PNG, WEBP, GIF only)
- Admin session authentication on all protected endpoints
- Price/quantity server-side validation
- Email format validation with `filter_var()`

---

## 🎨 Design
- **Fonts:** Playfair Display (headings) + DM Sans (body) + DM Mono (codes/labels)
- **Colours:** Forest green system with gold/terra accents on warm cream backgrounds
- **Responsive:** Works on mobile, tablet, and desktop
- **Animations:** Staggered card-in animations, smooth modal transitions, scroll reveal

---

*Built for Ecosphere — giving items a second life 🌍*
