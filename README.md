# 📚 Bookstore: Custom PHP MVC

A full-featured online bookstore built **from scratch with native PHP (no framework)** on top of a hand-written MVC core: router, middleware pipeline, request validation, JSON responses, and a PDO database layer.

The app has two roles, **Admin** and **Customer**, each with its own dashboard, permissions, and AJAX-driven workflow (cart, orders, pagination, filtering, and moderation).

> 🔗 **Live demo:** http://bookstore-semicode-mvc.atwebpages.com/

---

## 📑 Table of Contents

- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Project Architecture](#-project-architecture)
- [Folder Structure](#-folder-structure)
- [Request Lifecycle](#-request-lifecycle)
- [Database Schema](#-database-schema)
- [Routes](#-routes)
- [Validation Engine](#-validation-engine)
- [Order Flow](#-order-flow)
- [Installation](#-installation)
- [Known Issues and Roadmap](#-known-issues-and-roadmap)
- [Live Demo](#-live-demo)

---

## ✨ Features

### 👤 Authentication and Accounts
- Register (customer) and login with **hashed passwords** (`password_hash` / `password_verify`)
- Session handling with `session_regenerate_id` on login and logout
- Guest / Auth / Register **middlewares** protect routes by role
- **Ban system**: banned users cannot log in
- Edit profile data inline (name, email, phone, gender, password) from a popup modal
- Server-side validation with old-input and error flashing

### 🛠️ Admin Dashboard
- Statistics: total admins, customers, authors, books, and orders (ordered / canceled / done)
- Browse **Admins**, **Customers**, **Authors**, **Books**, and **Orders** with AJAX pagination
- Create new admins
- Add / edit **authors**; add **books** (with image upload) directly from an author card
- **Ban / Unban** users (an older admin cannot be banned by a newer one)
- Manage orders: mark as **Done** or **Cancel** (with a cancel reason)

### 🛒 Customer Dashboard
- Statistics: total books, books bought, and his own orders (ordered / canceled / done)
- Browse books with **search and filter** (title, author, min / max price, stock, ASC / DESC sorting)
- **Shopping cart**: add, increase, decrease, and delete items with live totals
- Place an order, which decreases book stock automatically
- Track his own orders (ordered / canceled / done) and view order details

### ⚙️ Technical Highlights
- Custom **Router** with dynamic URL params (`/path/{id}`), HTTP-method matching, and middleware with arguments (`AuthMiddlewares:admin`)
- Custom **Validation** class (required, email, Egyptian phone, min length, unique, exists, in, image, number ranges)
- Unified JSON API response helper (`Response::json`)
- Reusable PHP view components + a JS component layer that mirrors them for AJAX updates
- Pagination and filtering without page reloads

---

## 🧰 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.x (native, OOP, MVC) |
| Database | MySQL / MariaDB via **PDO** |
| Frontend | HTML5, CSS3 / **SCSS**, Bootstrap 5.3 |
| JavaScript | jQuery 4, AJAX, SweetAlert2 |
| Icons / Fonts | Font Awesome 7, Oswald |
| Hosting | AwardSpace (free hosting) |

---

## 🏗️ Project Architecture

The project follows the **MVC** pattern with a small custom core:

```
Browser ──► public/index.php ──► core/App ──► core/Route ──► Middleware ──► Controller ──► Model ──► DB
                                                                              │
                                                                              └──► View (HTML) or Response::json (AJAX)
```

| Part | Responsibility |
|---|---|
| **core/** | Framework layer: `App`, `Route`, `Request`, `Response`, `Validation`, `Database` |
| **Controllers** | Validate input, call models, return a view or JSON |
| **Models** | All SQL / data logic (`BookModel`, `CartModel`, `OrderModel`, ...) |
| **Views** | PHP templates and reusable components (cards, tables, pagination) |
| **Middlewares** | Access control by auth state and role |
| **Helpers** | Global functions: `asset()`, `route()`, `auth()`, `isAuth()`, `back()`, `old()`, ... |

---

## 📂 Folder Structure

```
bookstore/
├── app/
│   ├── controllers/
│   │   ├── Controller.php              # Base controller (view rendering)
│   │   ├── api/                        # API controllers (planned)
│   │   └── web/
│   │       ├── Auth/                   # LoginController, RegisterController
│   │       ├── admin/                  # AdminController (ban users, manage orders)
│   │       ├── author/                 # AuthorController
│   │       ├── book/                   # BookController (filter, add book)
│   │       ├── customer/               # CustomerController (cart and orders)
│   │       ├── profile/                # ProfileController (dashboard and pagination)
│   │       ├── user/                   # UserController (edit profile)
│   │       └── HomeController.php
│   ├── helpers/Helpers.php             # Global helper functions
│   ├── middlewares/                    # Auth, Guest, Register middlewares
│   ├── models/                         # Admin, Auth, Author, Book, Cart, DB, Order, Search, User
│   └── views/
│       ├── auth/                       # login, register
│       ├── home/, errors/, shared/     # home page, 404, navbar, shared assets
│       └── profile/                    # dashboard + components/ (cards, tables, stats)
├── core/                               # App, Route, Request, Response, Validation, Database
├── database/                           # Migrations (up.php / down.php + table files)
├── public/
│   ├── index.php                       # Front controller (entry point)
│   └── assets/                         # css, scss, js, images, uploads
└── routes/
    ├── web.php                         # Web routes
    └── api.php                         # API routes (planned)
```

---

## 🔄 Request Lifecycle

1. `public/index.php` defines `baseUrl` and calls `App::run()`.
2. `App::run()` starts the session, detects **web** vs **api** from the URL, loads helpers, `Request`, `Response`, `Validation`, and the matching routes file, and defines `NUMBER_OF_CARDS`.
3. `Route::dispatch()` matches URL and method, runs the route's **middlewares**, then instantiates the controller and calls the action with the URL params.
4. The controller validates the input with `Request::validate()`. On failure it redirects back with errors (forms) or returns **422 JSON** (AJAX).
5. The model runs the SQL and the controller returns a **view** or `Response::json()`.

---

## 🗄️ Database Schema

```
users ──< orders ──< orders_items >── books >── authors
  │
  └──< api_tokens
```

| Table | Main columns |
|---|---|
| `users` | id, name, email (unique), password (hashed), phone (unique), image, gender, role (`admin`/`customer`), is_banned |
| `authors` | id, name, bio |
| `books` | id, author_id (FK), title, image, description, price, stock |
| `orders` | id, customer_id (FK), status (`pending`/`ordered`/`canceled`/`done`), cancel_reason, total_price |
| `orders_items` | id, order_id (FK), book_id (FK), quantity, unit_price, subtotal |
| `api_tokens` | id, user_id (FK), token, expires_at |

All tables include `created_at` / `updated_at` timestamps.

---

## 🛣️ Routes

### Public / Auth
| Method | URL | Action | Middleware |
|---|---|---|---|
| GET | `/` | Home page | n/a |
| GET / POST | `/auth/register` | Register form / submit | Register |
| GET | `/auth/createNewUser` | Create-admin form | Register |
| GET / POST | `/auth/login` | Login form / submit | Guest |
| GET | `/auth/logout` | Logout | Auth |

### Shared (any logged-in user)
| Method | URL | Action |
|---|---|---|
| GET | `/profile` | Role-based dashboard |
| POST | `/profile/paginationData` | AJAX pagination |
| POST | `/profile/editUserData` | Edit own profile |
| POST | `/profile/filterBooks` | Filter / search / sort books |
| POST | `/profile/getItemsToAddIntoCart` | Cart / order items |

### Admin only (`AuthMiddlewares:admin`)
| Method | URL | Action |
|---|---|---|
| POST | `/profile/addAuthor` | Add author |
| POST | `/profile/editAuthor` | Edit author |
| POST | `/profile/addBook` | Add book (with image) |
| POST | `/profile/banUser` | Ban / unban user |
| POST | `/profile/mangeOrder` | Mark order done / canceled |

### Customer only (`AuthMiddlewares:customer`)
| Method | URL | Action |
|---|---|---|
| POST | `/profile/addBookQuantity` | Add book to cart |
| POST | `/profile/increaseOrderItem` | +1 quantity |
| POST | `/profile/decreaseOrderItem` | −1 quantity |
| POST | `/profile/deleteOrderItem` | Remove item |
| POST | `/profile/orderCustomerOrder` | Confirm the order |

---

## ✅ Validation Engine

Rules are declared per field, similar to Laravel:

```php
$errors = Request::validate([
    "email"    => ["required", "email", ["unique", "users"]],
    "password" => ["required", ["min", 8]],
    "phone"    => ["required", "egPhone", ["unique", "users"]],
    "gender"   => [["required", "male,female"]],
    "page"     => [["minNumber", 1]],
]);
```

| Rule | Description |
|---|---|
| `required` / `["required", "a,b"]` | Not empty, optionally limited to listed values |
| `email`, `name`, `number`, `egPhone` | Format checks (Egyptian phone: `(02)?01[0125]xxxxxxxx`) |
| `["min", n]` | Minimum length |
| `["minNumber", n]` / `["maxNumber", n]` | Numeric range |
| `["unique", table, exceptId]` | Value must not already exist |
| `["exists", table, column]` | Value must exist |
| `["in", "a,b"]` | Value in a list |
| `["image", "jpg,png,..."]` | Allowed image extensions |

Errors and old input are flashed to the session for forms and returned as `422` JSON for AJAX.

---

## 🔁 Order Flow

```
pending ──(customer confirms)──► ordered ──(admin)──► done
                                     └─────(admin + reason)──► canceled
```

1. Adding the first book creates a **pending** order (the cart).
2. Items are added, increased, decreased, or removed. Totals are recalculated server-side.
3. The customer confirms and the order becomes **ordered**, and book **stock is decreased**.
4. The admin marks it **done** or **canceled** (with a cancel reason).

---

## 🚀 Installation

### Requirements
- PHP 8.0+
- MySQL / MariaDB
- Apache (XAMPP / WAMP / Laragon) with `mod_rewrite`

### Steps

```bash
# 1. Clone the repo into your web root (htdocs)
git clone <your-repo-url> bookstore-test

# 2. Create the database
mysql -u root -e "CREATE DATABASE bookstore CHARACTER SET utf8mb4;"
```

**3. Configure the database** in `core/Database.php`:
```php
private const DSN = "mysql:host=localhost;dbname=bookstore";
private const username = "root";
private const password = "";
```

**4. Set the base URL** in `public/index.php` to match your folder:
```php
const baseUrl = "/bookstore-test/public";
```

**5. Run the migrations:**
```bash
php database/up.php      # create tables
php database/down.php    # drop tables (optional)
```

**6. Create the first admin.** Admin registration is restricted to logged-in admins, so insert the first one manually:
```php
<?php // generate a hash, then insert the row
echo password_hash("your-password", PASSWORD_DEFAULT);
```
```sql
INSERT INTO users (name, email, password, phone, gender, role)
VALUES ('Admin', 'admin@gmail.com', '<hash>', '01012345678', 'male', 'admin');
```

**7. Open** `http://localhost/bookstore-test/public/`

> Make sure `public/assets/images/uploads/` exists and is writable for image uploads.

---

## 🧭 Known Issues and Roadmap

**Known issues (planned fixes)**
- [ ] Convert raw SQL string concatenation to **prepared statements** everywhere (login, user creation, filters, `prepareWhereArray`)
- [ ] Whitelist editable columns in `UserModel::editUser`
- [ ] Qualify ambiguous `id` columns in JOIN queries (`ORDER BY books.id`, `orders.id`)
- [ ] Align the migration column name with the code (`is_banned`)
- [ ] Fix upload-directory creation check (`!is_dir`) and the image path handling
- [ ] Escape output in views (XSS hardening) and add CSRF tokens

**Roadmap**
- [ ] REST API (`/api/v1/...`) with token authentication (`api_tokens` table is ready)
- [ ] Delete old image on book / profile update
- [ ] Book details page and customer-side search outside the dashboard
- [ ] Email notifications for order status changes
- [ ] Unit / feature tests

---

## 🌐 Live Demo

👉 **http://bookstore-semicode-mvc.atwebpages.com/**
