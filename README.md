# Task 2 — Admin: Restaurant & Menu Item Management
**Student ID:** 23-51164-1 | **Course:** Web Technologies | Online Food Blog

---

## 🚀 Quick Start

### Step 1 — Import the Database
Open **phpMyAdmin** → Import tab → select `foodBlog.sql` → click Go.

### Step 2 — Fix Password (Important!)
Open in browser: `http://localhost/task2/generate_hash.php`
This sets the correct bcrypt hash for password `12345678` on your PHP version.
**Delete `generate_hash.php` after running it.**

### Step 3 — Place in XAMPP
```
Copy task2/ folder → C:\xampp\htdocs\task2\
Make sure public/uploads/menu/ is writable (chmod 755)
```

### Step 4 — Open in Browser
```
http://localhost/task2/
```

---

## 🔑 Admin Login
| Field | Value |
|-------|-------|
| Email | tanjil@gmail.com |
| Password | 12345678 |
| Access Code | **23599** (mandatory) |

---

## 📁 Project Structure (MVC)
```
task2/
├── index.php                         ← Login page (entry point)
├── generate_hash.php                 ← Run once to fix password, then delete
├── foodBlog.sql                      ← Full DB schema + admin seed
├── config/
│   └── db.php                        ← DB connection (mysqli)
├── controller/
│   ├── authController.php            ← Login / Logout
│   ├── restaurantController.php      ← Create/Update/Delete restaurants
│   └── menuItemController.php        ← Create/Update/Delete menu items + image upload
├── model/
│   ├── restaurantModel.php           ← All restaurant DB functions
│   └── menuItemModel.php             ← All menu item DB functions + counts
├── view/
│   ├── partials/
│   │   ├── header.php                ← Shared navbar + CSS (for view/)
│   │   ├── header_plain.php          ← Navbar for root index.php
│   │   └── footer.php
│   ├── admin/
│   │   ├── dashboard.php             ← Summary stats (AJAX live refresh)
│   │   ├── restaurants.php           ← Restaurant list + delete modal
│   │   ├── restaurant_form.php       ← Add/Edit restaurant
│   │   ├── menu_items.php            ← Menu items per restaurant
│   │   ├── menu_item_form.php        ← Add/Edit menu item + image upload
│   │   └── login_redirect.php        ← Redirect unauthorized users
│   ├── restaurant/
│   │   ├── list.php                  ← Public restaurant listing
│   │   └── detail.php                ← Restaurant page + menu grid
│   └── menu/
│       └── detail.php                ← Menu item detail + review section slot
├── api/
│   ├── delete_item.php               ← AJAX JSON — delete menu item
│   └── dashboard_stats.php           ← AJAX JSON — live stat counts
└── public/uploads/menu/              ← Uploaded food images
```

---

## ✅ Grading Criteria Checklist
| # | Criterion | Implementation |
|---|-----------|----------------|
| 1 | **Security** | Prepared statements, `htmlspecialchars()`, `finfo` MIME check, access code gate |
| 2 | **UI** | Responsive CSS, card layout, modals, image previews, back buttons on every page |
| 3 | **Feature Completeness** | Full CRUD restaurants & menu items, cascade delete, public pages |
| 4 | **DB** | Shared `foodBlog` schema, FK relationships, CASCADE on delete |
| 5 | **Auth (Session)** | Every admin page checks `$_SESSION['role'] === 'admin'`; authController handles login |
| 6 | **MVC** | Controllers → logic, Models → DB, Views → HTML |
| 7 | **JS Validation** | Client-side validation on login, restaurant, and menu item forms |
| 8 | **PHP Validation** | Server-side validation in all controllers before every DB write |
| 9 | **AJAX/JSON** | `api/delete_item.php` and `api/dashboard_stats.php` return `Content-Type: application/json` |
| 10 | **Git** | Branch: `feature/task2-23-51164-1` → PR → merge to `main` (≥3 commits) |

---

## 🌐 Page Map
```
http://localhost/task2/                         ← Login page (ACCESS CODE: 23599)
    └── [after login as admin]
        ├── view/admin/dashboard.php           ← Stats + quick actions
        ├── view/admin/restaurants.php         ← List/Delete restaurants
        ├── view/admin/restaurant_form.php     ← Add/Edit restaurant
        ├── view/admin/menu_items.php          ← List/Delete menu items
        ├── view/admin/menu_item_form.php      ← Add/Edit menu item
        ├── view/restaurant/list.php           ← Public restaurant list
        ├── view/restaurant/detail.php         ← Restaurant + its menu
        └── view/menu/detail.php               ← Food item detail + review slot
```

---

## Git Instructions
```bash
git checkout -b feature/task2-23-51164-1
git add .
git commit -m "feat: add DB schema, models, MVC structure"
git commit -m "feat: add admin login with access code gate"
git commit -m "feat: add full CRUD for restaurants and menu items"
git commit -m "feat: add back buttons, public views, AJAX endpoints"
git push origin feature/task2-23-51164-1
# Open Pull Request into main on GitHub
```
