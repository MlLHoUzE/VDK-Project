# Hammond Motors - Vehicle Management System

A professional-grade automotive inventory management application built with **Laravel 11**, **Vue 3**, and **Inertia.js**. This project features a dual-interface system for public browsing and administrative management.

## Key Features

- **Inertia.js SPA Architecture**: Seamless page transitions without full reloads.
- **Dynamic View Toggle**: Admin dashboard supports both List and Gallery views with URL state persistence.
- **Responsive Management**: Mobile-first design that forces optimized layouts on small screens using **VueUse**.
- **Secure Authentication**: Complete admin suite with password recovery and profile management.
- **Modern UI**: Dark mode support, Glassmorphism headers, and Tailwind CSS animations.

---

## Prerequisites

- **PHP 8.3+**
- **Composer**
- **Node.js & NPM**
- **SQLite** (or your preferred database)

---

## Getting Started

Follow these steps to get the project running locally:

### 1. Clone & Install Dependencies

```bash
git clone <your-repo-url>
cd vdk-project
composer install
npm install
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Database Configuration

```bash
# For Windows (PowerShell)
New-Item -ItemType File database/database.sqlite

# For Mac/Linux
touch database/database.sqlite
```

Run the migrations and seed the database with sample inventory

```bash
php artisan migrate:fresh --seed
```

This creates a test user: test@example.com with password password.

### 4. Development Servers

You will need two terminal windows running

Terminal 1 (PHP)

```bash
php artisan serve
```

Terminal 2 (Frontend)

```bash
npm run dev
```

visit http://127.0.0.1:8000 to view the application

Testing Email (Password Reset)
This project is configured to use the log driver by default for local development.

- When you trigger a password reset, open storage/logs/laravel.log.
- The reset link will be written at the bottom of the log file.

Project Highlights for Reviewers

- Shared Components: Check resources/js/Components/VehicleCard.vue for a highly reusable component handling both Public and Admin states.
- State Management: See resources/js/Pages/Admin/Vehicles/Index.vue for advanced Inertia router logic and query parameter synchronization.
- Mobile Logic: View resources/js/Pages/Admin/Vehicles/Index.vue to see how VueUse Breakpoints are used to maintain a clean mobile experience.
- RESTful Design: Review app/Http/Controllers/Admin/VehicleController.php for clean, resourceful routing and atomic status toggling.
