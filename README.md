# Laravel E‑Commerce API

## 🧾 Overview

This project is a RESTful API built with Laravel for an e‑commerce
platform. It provides full backend functionality including user
authentication, product management, order workflows, and administration
panels.

## 🚀 Features

-   User registration & login (JWT authentication)
-   User profile retrieval & update
-   Role‑based access (regular user vs admin)
-   CRUD operations for brands, categories, products
-   Order creation by users, and order management by admins
-   Location management (create, update, delete)
-   Fully structured routes, controllers, and middleware
-   Designed for deployment and production readiness

## 📦 Tech Stack

-   **Backend framework**: Laravel
-   **Authentication**: JWT
-   **Database**: MySQL / MariaDB (or any supported by Laravel)
-   **ORM**: Eloquent (Laravel)
-   **Code style & patterns**: RESTful API, controllers, middleware
-   **Extras**: .env configuration, migrations

## 🔧 Getting Started

1.  Clone the repository:

    ``` bash
    git clone https://github.com/3laaElsadany/Laravel_E-Commerce_API.git
    cd Laravel_E-Commerce_API
    ```

2.  Install dependencies:

    ``` bash
    composer install
    ```

3.  Start the server:

    ``` bash
    php artisan serve
    ```

    The API will be accessible at `http://localhost:8000`.

## 🧩 API Routes Summary

### Authentication

-   `POST /register` → register new user
-   `POST /login` → login and receive JWT
-   (JWT protected) `GET /user`, `PUT /user`, `POST /logout`

### Admin‑only (via middleware or controllers)

-   `apiResource /brands` → full brand CRUD
-   `apiResource /categories` → full category CRUD
-   Orders (admin only):
    -   `GET /orders/getOrderItems/{order}`\
    -   `GET /orders/getUserOrders/{userId}`\
    -   `PUT /orders/updateOrderStatus/{order}`\
    -   In `OrderController`, only `index` and `show` require admin
        access

### Protected by JWT (users & admins)

-   `apiResource /locations` → only `store`, `update`, `destroy`
-   `apiResource /products` → full CRUD, with admin protection except
    `index` & `show`
-   Orders (user access): `apiResource /orders` → only `index`, `show`,
    `store`
