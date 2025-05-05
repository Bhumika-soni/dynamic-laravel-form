# Dynamic Laravel Form - Setup Guide

This document provides step-by-step instructions to get this Laravel project up and running from scratch.

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js and npm
- MySQL or another database system
- XAMPP (or similar local development environment)

## Installation Steps

### 1. Clone the Repository

```bash
git clone <repository-url>
cd dynamic-laravel-form
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Setup

```bash
cp .env.example .env
```

Then, edit the `.env` file to configure your database connection:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dynamic_laravel_form
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Create Storage Symbolic Link

For file uploads to work properly, create a symbolic link from public/storage to storage/app/public:

```bash
php artisan storage:link
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Install JavaScript Dependencies

```bash
npm install
```

### 8. Build Frontend Assets

For development:
```bash
npm run dev
```

For production:
```bash
npm run build
```

### 9. Start the Local Development Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

## Running with XAMPP

Since you're using XAMPP:

1. Make sure your project is in the `htdocs` directory (It appears you already have it at `D:/xampp/htdocs/dynamic-laravel-form`)
2. Start Apache and MySQL from the XAMPP control panel
3. Access your project at `http://localhost/dynamic-laravel-form/public`

## All-in-One Development Environment

Laravel provides a convenient script to run multiple services at once:

```bash
composer run dev
```

This will start:
- Laravel server
- Queue worker
- Log watcher
- Vite development server

## Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Check your database credentials in the `.env` file
   - Make sure your database server is running

2. **Storage Permission Issues**:
   - Run `php artisan storage:link`
   - Ensure the `storage` directory has appropriate write permissions

3. **Composer Dependencies Fail**:
   - Try `composer update` instead of `composer install`
   - Check PHP version compatibility

4. **Frontend Assets Not Loading**:
   - Clear the browser cache
   - Verify that Vite is running with `npm run dev`

5. **File Uploads Not Working**:
   - Make sure storage:link has been created 
   - Check that the storage directory is writable
   - Verify that the `enctype="multipart/form-data"` attribute is on the form

For more help, refer to the [Laravel documentation](https://laravel.com/docs) or create an issue in the project repository. 