# Invoice Management API

This is a RESTful API built with Laravel for managing customers, items (inventory), and invoices.

---

## Features

- Customer management (CRUD)
- Item management with inventory tracking
- Invoice creation with multiple items
- Automatic total calculation
- Inventory protection (prevents overselling)
- User authentication (Laravel Sanctum)
- Role-based authorization (Admin / Staff)

---

## Tech Stack

- Laravel 12
- Sqlite
- Laravel Sanctum (API authentication)

---

## Setup Instructions

### 1. Install dependencies
Run 'composer install' to install dependencies (such as sanctum)

### 2. Setup database seeders
Run 'php artisan db:seed' to insert seeders

NB: Run 'php artisan migrate' if sqlite file is not added

### 3. Generate app key
Run 'php artisan key:generate' if APP_KEY is empty

### 4. Start application
Run 'php artisan serve' to start the application



## Side Note
Import the 'Built Interview Backend Postman Collection.postman_collection.json' Postman collection inside this directory to use as a guide

