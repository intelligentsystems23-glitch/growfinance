# ERP System

A custom PHP-based ERP application built for small business operations. It provides invoicing, customer and vendor management, products and inventory, purchase orders, expenses, banking, accounting reports, employee tracking, and general system settings.

## Overview

This application uses a lightweight MVC-style architecture with a front controller in `index.php`, controllers in `controllers/`, models in `models/`, and views in `views/`.

The project uses PDO for database access and supports TCPDF for PDF generation.

## Key Features

- Dashboard with business metrics and recent activity
- Invoicing and payment tracking
- Customer and vendor management
- Product inventory, stock tracking, and low stock alerts
- Expenses and purchase orders
- Banking transactions
- Financial and accounting reports
- Authentication and user management

## Project Structure

- `index.php`
  - Main entry point and router for the application
- `config/database.php`
  - Database connection settings and helper function `getConnection()`
- `core/Controller.php`
  - Base controller class with shared helpers for rendering views, loading models, request handling, sessions, and redirects
- `controllers/`
  - Application controllers for each domain
- `models/`
  - Data access classes for business entities
- `views/`
  - View templates grouped by feature
  - `layouts/header.php` and `layouts/footer.php` for common layout
- `assets/`
  - Static CSS, JS, fonts, images, and uploads
- `helpers/PDFHelper.php`
  - PDF generation helper using TCPDF
- `libraries/tcpdf/`
  - Local copy of TCPDF library
- `vendor/`
  - Composer dependencies

## Important Files

- `controllers/DashboardController.php`
  - Dashboard data aggregation and page rendering
- `controllers/ProductController.php`
  - Product list, stock values, and low stock items
- `models/Product.php`
  - Product queries, stock movements, and inventory calculations
- `views/dashboard/index.php`
  - Dashboard UI and metrics layout
- `views/products/index.php`
  - Product listing and inventory overview

## Requirements

- PHP 7.4+ (or compatible)
- MySQL / MariaDB
- XAMPP or similar local development environment
- Composer (for dependencies)

## Installation

1. Place the project in your web server root, for example `C:\xampp\htdocs\erp_system`.
2. Create a MySQL database named `erp_system`.
3. Update `config/database.php` if your database credentials are different.
4. Import the required database schema and seed data as needed.
5. Access the application at `http://localhost/erp_system`.

## Notes

- The app currently uses a simple router in `index.php` rather than a full PHP framework.
- Views can be extended or replaced with a templating system if desired.
- The database configuration is currently set for XAMPP defaults.

## License

This repository does not include a license file. Add one if you want to define usage terms.
