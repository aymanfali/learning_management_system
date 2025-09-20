# Learning Management System (LMS)

![License](https://img.shields.io/badge/license-MIT-green) ![Laravel](https://img.shields.io/badge/Laravel-12-red) ![Vue.js](https://img.shields.io/badge/Vue-3-brightgreen)

A modern **Learning Management System** built with **Laravel 12** and **Vue 3**, using **Blade templates** for server-rendered pages and **Vue components** for dynamic UI.

---

## Table of Contents

-   [Features](#features)
-   [Tech Stack](#tech-stack)
-   [Screenshots](#screenshots)
-   [Quick Start](#quick-start)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Contributing](#contributing)
-   [License](#license)

---

## Features

### Backend (Laravel 12)

-   Follows MVC architecture
-   Database: migrations, factories, seeders
-   CRUD operations with Eloquent ORM and relationships
-   Authentication & Authorization:
    -   Laravel Breeze
    -   Role-based access control (Gates & Policies)
    -   Routes protection using Middlewares
-   File uploads (images, documents)
-   Notifications:
    -   Stored in database
    -   Sent via email
    -   Shown as toasts in the frontend
-   Error handling, input validation, and logging
-   Security measures: CSRF, password hashing, validation

### Frontend (Vue.js + Tailwind CSS)

-   Dark/Light mode toggle
-   Fully responsive design (desktop & mobile)
-   Dynamic UI integrated with backend APIs for listing and deleting data in dashboard
-   Interactive forms with validation & error messages
-   Analytics dashboard with 4 charts (Monthly Registrations (Students vs Instructors), Daily Registrations (Last 7 Days), Weekly New Courses, Top 5 Courses by Enrollments)
-   Toast notifications for success/error feedback

### Database

-   6 entities in the schema (users, courses, lessons, notifications, enrollments, assignments)
-   Includes relationships: One-to-One, One-to-Many, Many-to-Many
-   Optimized queries with eager loading

---

## Tech Stack

-   **Backend:** Laravel 12
-   **Frontend:** Vue 3 components + Blade Templates
-   **Database:** MySQL 
-   **Authentication:** Laravel Breeze 
-   **Other Tools:** Axios, Tailwind CSS, Alpine.js

---

## Screenshots

**Dashboard**

**Course Management**



**Student View**

---

## Quick Start

Get started in 5 minutes:

1. Clone the repository:

```bash
https://github.com/aymanfali/learning_management_system.git

cd learning_management_system

```

2. Install backend dependencies:

```bash
composer install
```

3. Install frontend dependencies:

```bash
npm install

npm run dev
```

4. Copy `.env.example` to `.env` and set database credentials:

```bash
cp .env.example .env

php artisan key:generate
```

5. Run migrations and seeders:

```bash
php artisan migrate --seed
```

6. Start the development server:

```bash
php artisan serve
```

Access the app at `http://localhost:8000`.

---

## Configuration

-   Database credentials in `.env`
-   Mail settings in `.env` for notifications

---

## Usage

-   Admin panel: `/admin`
-   Instructor dashboard: `/instructor`
-   Student dashboard: `/student`
-   Vue components handle dynamic interactions 

---

## Contributing

1. Fork the repository
2. Create a branch: `git checkout -b feature/feature-name`
3. Commit your changes: `git commit -m 'Add some feature'`
4. Push: `git push origin feature/feature-name`
5. Open a Pull Request

---

## License

This project is licensed under the **MIT License**.
