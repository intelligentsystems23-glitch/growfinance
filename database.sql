-- ERP System Database Schema
-- Target: MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS `erp_system` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `erp_system`;

-- Disable foreign key checks to prevent drop/create issues
SET FOREIGN_KEY_CHECKS=0;

-- Drop existing tables
DROP TABLE IF EXISTS `customer_activities`;
DROP TABLE IF EXISTS `customer_notes`;
DROP TABLE IF EXISTS `customer_contacts`;
DROP TABLE IF EXISTS `user_roles`;
DROP TABLE IF EXISTS `role_permissions`;
DROP TABLE IF EXISTS `roles`;
DROP TABLE IF EXISTS `permissions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `vendor_transactions`;
DROP TABLE IF EXISTS `vendor_payments`;
DROP TABLE IF EXISTS `goods_receipt_items`;
DROP TABLE IF EXISTS `goods_receipts`;
DROP TABLE IF EXISTS `purchase_order_items`;
DROP TABLE IF EXISTS `purchase_orders`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `invoice_items`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `stock_movements`;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `vendor_categories`;
DROP TABLE IF EXISTS `contacts`;
DROP TABLE IF EXISTS `expenses`;
DROP TABLE IF EXISTS `expense_categories`;
DROP TABLE IF EXISTS `bank_transfers`;
DROP TABLE IF EXISTS `bank_transactions`;
DROP TABLE IF EXISTS `bank_accounts`;
DROP TABLE IF EXISTS `journal_items`;
DROP TABLE IF EXISTS `journal_entries`;
DROP TABLE IF EXISTS `chart_of_accounts`;
DROP TABLE IF EXISTS `leave_requests`;
DROP TABLE IF EXISTS `leave_types`;
DROP TABLE IF EXISTS `employees`;
DROP TABLE IF EXISTS `designations`;
DROP TABLE IF EXISTS `departments`;
DROP TABLE IF EXISTS `estimates`;
DROP TABLE IF EXISTS `estimate_items`;
DROP TABLE IF EXISTS `settings`;

SET FOREIGN_KEY_CHECKS=1;

-- 1. Users
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `full_name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NULL,
  `role` VARCHAR(50) DEFAULT 'employee',
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login` DATETIME NULL,
  `last_login_ip` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Roles
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `display_name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Permissions
CREATE TABLE `permissions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL UNIQUE,
  `display_name` VARCHAR(100) NOT NULL,
  `module` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. User Roles
CREATE TABLE `user_roles` (
  `user_id` INT NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`user_id`, `role_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Role Permissions
CREATE TABLE `role_permissions` (
  `role_id` INT NOT NULL,
  `permission_id` INT NOT NULL,
  PRIMARY KEY (`role_id`, `permission_id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Vendor Categories
CREATE TABLE `vendor_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Contacts (Customers and Vendors)
CREATE TABLE `contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `type` ENUM('customer', 'vendor', 'both') NOT NULL,
  `customer_code` VARCHAR(50) NULL UNIQUE,
  `vendor_code` VARCHAR(50) NULL UNIQUE,
  `vendor_category_id` INT NULL,
  `company_name` VARCHAR(150) NOT NULL,
  `contact_person` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(30) NULL,
  `mobile` VARCHAR(30) NULL,
  `website` VARCHAR(150) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(100) NULL,
  `state` VARCHAR(100) NULL,
  `postal_code` VARCHAR(20) NULL,
  `country` VARCHAR(100) DEFAULT 'USA',
  `currency` VARCHAR(10) NULL,
  `payment_terms` VARCHAR(50) DEFAULT 'Net 30',
  `credit_limit` DECIMAL(15, 2) DEFAULT 0.00,
  `tax_number` VARCHAR(50) NULL,
  `tax_id` VARCHAR(50) NULL,
  `notes` TEXT NULL,
  `customer_since` DATE NULL,
  `bank_name` VARCHAR(100) NULL,
  `bank_account_number` VARCHAR(50) NULL,
  `bank_routing_number` VARCHAR(50) NULL,
  `status` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Vendor Transactions
CREATE TABLE `vendor_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `vendor_id` INT NOT NULL,
  `transaction_date` DATE NOT NULL,
  `transaction_type` ENUM('purchase', 'payment') NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `balance` DECIMAL(15, 2) NOT NULL,
  `reference_no` VARCHAR(50) NULL,
  `description` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendor_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Product Categories
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Items (Products and Services)
CREATE TABLE `items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_code` VARCHAR(50) NOT NULL UNIQUE,
  `sku` VARCHAR(50) NULL UNIQUE,
  `barcode` VARCHAR(50) NULL UNIQUE,
  `item_name` VARCHAR(150) NOT NULL,
  `type` ENUM('product', 'service') DEFAULT 'product',
  `category_id` INT NULL,
  `description` TEXT NULL,
  `unit` VARCHAR(20) DEFAULT 'pcs',
  `sale_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `purchase_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `cost_method` VARCHAR(50) DEFAULT 'Average',
  `current_stock` DECIMAL(12, 2) DEFAULT 0.00,
  `opening_stock` DECIMAL(12, 2) DEFAULT 0.00,
  `reorder_level` DECIMAL(12, 2) DEFAULT 10.00,
  `min_stock` DECIMAL(12, 2) DEFAULT 0.00,
  `max_stock` DECIMAL(12, 2) DEFAULT 0.00,
  `location` VARCHAR(100) NULL,
  `image` VARCHAR(255) NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Stock Movements
CREATE TABLE `stock_movements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `item_id` INT NOT NULL,
  `movement_type` ENUM('purchase', 'sale', 'adjustment') NOT NULL,
  `quantity` DECIMAL(12, 2) NOT NULL,
  `unit_price` DECIMAL(15, 2) NOT NULL,
  `reference_type` VARCHAR(50) NULL,
  `reference_id` INT NULL,
  `notes` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Invoices
CREATE TABLE `invoices` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT NOT NULL,
  `invoice_date` DATE NOT NULL,
  `due_date` DATE NOT NULL,
  `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `discount_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('draft', 'sent', 'paid', 'overdue', 'cancelled') DEFAULT 'draft',
  `notes` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Invoice Items
CREATE TABLE `invoice_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice_id` INT NOT NULL,
  `item_id` INT NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Customer Payments
CREATE TABLE `payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `payment_number` VARCHAR(50) NOT NULL UNIQUE,
  `invoice_id` INT NOT NULL,
  `customer_id` INT NOT NULL,
  `payment_date` DATE NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `payment_method` VARCHAR(50) NULL,
  `reference_number` VARCHAR(50) NULL,
  `notes` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Purchase Orders
CREATE TABLE `purchase_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_number` VARCHAR(50) NOT NULL UNIQUE,
  `vendor_id` INT NOT NULL,
  `po_date` DATE NOT NULL,
  `expected_date` DATE NULL,
  `shipping_address` TEXT NULL,
  `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `shipping_cost` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `discount_type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
  `discount_value` DECIMAL(15, 2) DEFAULT 0.00,
  `discount_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('draft', 'sent', 'received', 'cancelled') DEFAULT 'draft',
  `payment_status` ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
  `notes` TEXT NULL,
  `approved_by` INT NULL,
  `approved_at` DATETIME NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendor_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Purchase Order Items
CREATE TABLE `purchase_order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `po_id` INT NOT NULL,
  `item_id` INT NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
  `received_quantity` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(6, 4) DEFAULT 0.0000,
  `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Goods Receipts
CREATE TABLE `goods_receipts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `grn_number` VARCHAR(50) NOT NULL UNIQUE,
  `po_id` INT NOT NULL,
  `receipt_date` DATE NOT NULL,
  `received_by` INT NOT NULL,
  `notes` TEXT NULL,
  `status` VARCHAR(20) DEFAULT 'completed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`received_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Goods Receipt Items
CREATE TABLE `goods_receipt_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `grn_id` INT NOT NULL,
  `po_item_id` INT NOT NULL,
  `quantity_received` DECIMAL(12, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`grn_id`) REFERENCES `goods_receipts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`po_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Vendor Payments
CREATE TABLE `vendor_payments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `payment_number` VARCHAR(50) NOT NULL UNIQUE,
  `vendor_id` INT NOT NULL,
  `po_id` INT NOT NULL,
  `payment_date` DATE NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `reference_number` VARCHAR(50) NULL,
  `notes` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`vendor_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Expense Categories
CREATE TABLE `expense_categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. Expenses
CREATE TABLE `expenses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `expense_number` VARCHAR(50) NOT NULL UNIQUE,
  `expense_date` DATE NOT NULL,
  `category_id` INT NOT NULL,
  `vendor_id` INT NULL,
  `description` TEXT NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `payment_method` VARCHAR(50) NULL,
  `reference_number` VARCHAR(50) NULL,
  `payment_status` ENUM('pending', 'paid', 'approved') DEFAULT 'pending',
  `notes` TEXT NULL,
  `approved_by` INT NULL,
  `approved_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`vendor_id`) REFERENCES `contacts` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. Bank Accounts
CREATE TABLE `bank_accounts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `account_number` VARCHAR(50) NOT NULL UNIQUE,
  `account_name` VARCHAR(100) NOT NULL,
  `bank_name` VARCHAR(100) NOT NULL,
  `branch_name` VARCHAR(100) NULL,
  `account_type` VARCHAR(50) NOT NULL DEFAULT 'checking',
  `currency` VARCHAR(10) DEFAULT 'USD',
  `opening_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `current_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `as_of_date` DATE NOT NULL,
  `account_holder` VARCHAR(100) NULL,
  `swift_code` VARCHAR(50) NULL,
  `routing_number` VARCHAR(50) NULL,
  `iban` VARCHAR(100) NULL,
  `notes` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. Bank Transactions
CREATE TABLE `bank_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transaction_number` VARCHAR(50) NOT NULL UNIQUE,
  `bank_account_id` INT NOT NULL,
  `transaction_date` DATE NOT NULL,
  `transaction_type` ENUM('deposit', 'withdrawal', 'transfer', 'receipt', 'payment', 'interest', 'fee') NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `running_balance` DECIMAL(15, 2) NOT NULL,
  `reference_type` VARCHAR(50) NULL,
  `reference_id` INT NULL,
  `payee_payer` VARCHAR(100) NULL,
  `description` TEXT NULL,
  `check_number` VARCHAR(50) NULL,
  `reconciled` TINYINT(1) DEFAULT 0,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`bank_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. Bank Transfers
CREATE TABLE `bank_transfers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `transfer_number` VARCHAR(50) NOT NULL UNIQUE,
  `from_account_id` INT NOT NULL,
  `to_account_id` INT NOT NULL,
  `transfer_date` DATE NOT NULL,
  `amount` DECIMAL(15, 2) NOT NULL,
  `exchange_rate` DECIMAL(12, 6) DEFAULT 1.000000,
  `fee` DECIMAL(15, 2) DEFAULT 0.00,
  `description` TEXT NULL,
  `reference_number` VARCHAR(50) NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`from_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`to_account_id`) REFERENCES `bank_accounts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. Chart of Accounts
CREATE TABLE `chart_of_accounts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `account_code` VARCHAR(20) NOT NULL UNIQUE,
  `account_name` VARCHAR(100) NOT NULL,
  `account_type` VARCHAR(50) NOT NULL,
  `parent_id` INT NULL,
  `description` TEXT NULL,
  `opening_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `current_balance` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `is_active` TINYINT(1) DEFAULT 1,
  `is_system` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`parent_id`) REFERENCES `chart_of_accounts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. Journal Entries
CREATE TABLE `journal_entries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `journal_date` DATE NOT NULL,
  `journal_number` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `reference_no` VARCHAR(50) NULL,
  `status` VARCHAR(20) DEFAULT 'draft',
  `created_by` INT NOT NULL,
  `posted_by` INT NULL,
  `posted_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  FOREIGN KEY (`posted_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. Journal Items
CREATE TABLE `journal_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `journal_id` INT NOT NULL,
  `account_id` INT NOT NULL,
  `debit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `credit` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`journal_id`) REFERENCES `journal_entries` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`account_id`) REFERENCES `chart_of_accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 28. Departments
CREATE TABLE `departments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `department_name` VARCHAR(100) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 29. Designations
CREATE TABLE `designations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `department_id` INT NOT NULL,
  `designation_name` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 30. Employees
CREATE TABLE `employees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `employee_code` VARCHAR(50) NOT NULL UNIQUE,
  `first_name` VARCHAR(50) NOT NULL,
  `last_name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `phone` VARCHAR(30) NULL,
  `mobile` VARCHAR(30) NULL,
  `date_of_birth` DATE NULL,
  `gender` VARCHAR(10) NULL,
  `marital_status` VARCHAR(20) NULL,
  `nationality` VARCHAR(50) NULL,
  `address` TEXT NULL,
  `city` VARCHAR(50) NULL,
  `state` VARCHAR(50) NULL,
  `postal_code` VARCHAR(20) NULL,
  `country` VARCHAR(50) DEFAULT 'USA',
  `department_id` INT NULL,
  `designation_id` INT NULL,
  `employment_type` VARCHAR(30) DEFAULT 'full_time',
  `joining_date` DATE NULL,
  `reporting_to` INT NULL,
  `bank_name` VARCHAR(100) NULL,
  `bank_account_number` VARCHAR(50) NULL,
  `bank_routing_number` VARCHAR(50) NULL,
  `tax_id` VARCHAR(50) NULL,
  `basic_salary` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `housing_allowance` DECIMAL(15, 2) DEFAULT 0.00,
  `transport_allowance` DECIMAL(15, 2) DEFAULT 0.00,
  `other_allowances` DECIMAL(15, 2) DEFAULT 0.00,
  `emergency_contact_name` VARCHAR(100) NULL,
  `emergency_contact_phone` VARCHAR(30) NULL,
  `emergency_contact_relation` VARCHAR(50) NULL,
  `status` VARCHAR(20) DEFAULT 'active',
  `notes` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`designation_id`) REFERENCES `designations` (`id`) ON DELETE SET NULL,
  FOREIGN KEY (`reporting_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 31. Leave Types
CREATE TABLE `leave_types` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `leave_type` VARCHAR(50) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 32. Leave Requests
CREATE TABLE `leave_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `leave_number` VARCHAR(50) NOT NULL UNIQUE,
  `employee_id` INT NOT NULL,
  `leave_type_id` INT NOT NULL,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `total_days` INT NOT NULL,
  `reason` TEXT NULL,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `approved_by` INT NULL,
  `approved_at` DATETIME NULL,
  `rejection_reason` TEXT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 33. Estimates
CREATE TABLE `estimates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `estimate_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT NOT NULL,
  `estimate_date` DATE NOT NULL,
  `expiry_date` DATE NULL,
  `subtotal` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `tax_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `discount_type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
  `discount_value` DECIMAL(15, 2) DEFAULT 0.00,
  `discount_amount` DECIMAL(15, 2) DEFAULT 0.00,
  `total_amount` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `status` ENUM('draft', 'sent', 'accepted', 'declined', 'invoiced') DEFAULT 'draft',
  `notes` TEXT NULL,
  `terms` TEXT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 34. Estimate Items
CREATE TABLE `estimate_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `estimate_id` INT NOT NULL,
  `item_id` INT NULL,
  `description` TEXT NOT NULL,
  `quantity` DECIMAL(12, 2) NOT NULL DEFAULT 1.00,
  `unit_price` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  `tax_rate` DECIMAL(6, 4) DEFAULT 0.0000,
  `discount_percent` DECIMAL(5, 2) DEFAULT 0.00,
  `total` DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
  FOREIGN KEY (`estimate_id`) REFERENCES `estimates` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 35. Settings
CREATE TABLE `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(80) NOT NULL UNIQUE,
  `setting_value` TEXT NULL,
  `group_name` VARCHAR(50) NULL,
  `label` VARCHAR(100) NULL,
  `setting_type` VARCHAR(30) DEFAULT 'text',
  `description` TEXT NULL,
  `options` TEXT NULL,
  `sort_order` INT DEFAULT 0,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 36. Customer Contacts
CREATE TABLE `customer_contacts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `contact_name` VARCHAR(100) NOT NULL,
  `position` VARCHAR(100) NULL,
  `email` VARCHAR(100) NULL,
  `phone` VARCHAR(30) NULL,
  `mobile` VARCHAR(30) NULL,
  `is_primary` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 37. Customer Notes
CREATE TABLE `customer_notes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `note` TEXT NOT NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 38. Customer Activities
CREATE TABLE `customer_activities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `activity_type` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  `reference_id` INT NULL,
  `reference_type` VARCHAR(50) NULL,
  `created_by` INT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ==========================================
-- SEED INITIAL DATA
-- ==========================================

-- Seed Roles
INSERT INTO `roles` (`id`, `name`, `display_name`) VALUES
(1, 'admin', 'Administrator'),
(2, 'manager', 'Manager'),
(3, 'employee', 'Employee');

-- Seed Permissions
INSERT INTO `permissions` (`id`, `name`, `display_name`, `module`) VALUES
(1, 'dashboard.view', 'View Dashboard', 'dashboard'),
(2, 'users.manage', 'Manage Users & Access', 'users'),
(3, 'users.view', 'View Users', 'users'),
(4, 'users.create', 'Create Users', 'users'),
(5, 'users.edit', 'Edit Users', 'users'),
(6, 'users.delete', 'Delete Users', 'users'),
(7, 'roles.manage', 'Manage Roles & Permissions', 'users'),
(8, 'settings.manage', 'Manage System Settings', 'settings'),
(9, 'invoices.view', 'View Invoices', 'invoices'),
(10, 'invoices.create', 'Create Invoices', 'invoices'),
(11, 'invoices.edit', 'Edit Invoices', 'invoices'),
(12, 'invoices.delete', 'Delete Invoices', 'invoices'),
(13, 'invoices.payment', 'Record Invoice Payments', 'invoices'),
(14, 'estimates.view', 'View Estimates', 'estimates'),
(15, 'estimates.create', 'Create Estimates', 'estimates'),
(16, 'estimates.edit', 'Edit Estimates', 'estimates'),
(17, 'customers.manage', 'Manage Customers', 'customers'),
(18, 'customers.view', 'View Customers', 'customers'),
(19, 'customers.create', 'Create Customers', 'customers'),
(20, 'customers.edit', 'Edit Customers', 'customers'),
(21, 'vendors.manage', 'Manage Vendors', 'vendors'),
(22, 'vendors.view', 'View Vendors', 'vendors'),
(23, 'vendors.create', 'Create Vendors', 'vendors'),
(24, 'vendors.edit', 'Edit Vendors', 'vendors'),
(25, 'products.manage', 'Manage Products & Stock', 'products'),
(26, 'products.view', 'View Products & Inventory', 'products'),
(27, 'products.create', 'Create Products', 'products'),
(28, 'products.edit', 'Edit Products', 'products'),
(29, 'purchase_orders.manage', 'Manage Purchase Orders', 'purchase_orders'),
(30, 'purchase_orders.view', 'View Purchase Orders', 'purchase_orders'),
(31, 'purchase_orders.create', 'Create Purchase Orders', 'purchase_orders'),
(32, 'expenses.manage', 'Manage Expenses', 'expenses'),
(33, 'expenses.view', 'View Expenses', 'expenses'),
(34, 'expenses.create', 'Create Expenses', 'expenses'),
(35, 'expenses.approve', 'Approve Expenses', 'expenses'),
(36, 'banking.manage', 'Manage Banking Accounts & Transfers', 'banking'),
(37, 'banking.view', 'View Banking Transactions', 'banking'),
(38, 'accounting.manage', 'Manage Chart of Accounts & Journals', 'accounting'),
(39, 'accounting.view', 'View Financial Reports & Ledger', 'accounting'),
(40, 'employees.manage', 'Manage Employees & Leaves', 'employees'),
(41, 'employees.view', 'View Employee Records', 'employees'),
(42, 'reports.view', 'View & Export System Reports', 'reports');

-- Seed Role Permissions
INSERT INTO `role_permissions` (`role_id`, `permission_id`) VALUES
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6), (1, 7), (1, 8), (1, 9), (1, 10), (1, 11), (1, 12);

-- Seed Default Admin User: admin / admin123
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `is_active`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$3YmGq/26gW9D545.XFqEAuXyR93oTjIeH/44qJ65E.H1j98R/bC.a', 'Admin User', 'admin', 1);

-- Link Admin to Role
INSERT INTO `user_roles` (`user_id`, `role_id`) VALUES
(1, 1);

-- Seed Default Settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `group_name`, `label`, `setting_type`, `description`, `options`, `sort_order`) VALUES
-- General Settings
('site_title', 'My Business ERP', 'general', 'Application Title', 'text', 'The name displayed in browser title and headers', NULL, 1),
('currency', 'USD', 'general', 'Default Currency Code', 'select', 'Primary currency for transactions and financial statements', '{"USD":"USD ($)","EUR":"EUR (€)","GBP":"GBP (£)","UGX":"UGX (USh)","KES":"KES (KSh)","INR":"INR (₹)","CAD":"CAD (CA$)"}', 2),
('currency_symbol', '$', 'general', 'Currency Symbol', 'text', 'Symbol used when displaying monetary amounts', NULL, 3),
('date_format', 'Y-m-d', 'general', 'Date Format', 'select', 'Format for displaying dates system-wide', '{"Y-m-d":"YYYY-MM-DD (2026-07-20)","d/m/Y":"DD/MM/YYYY (20/07/2026)","m/d/Y":"MM/DD/YYYY (07/20/2026)","d-M-Y":"DD-MMM-YYYY (20-Jul-2026)"}', 4),
('timezone', 'UTC', 'general', 'System Timezone', 'select', 'Default timezone for transaction logging and timestamps', '{"UTC":"UTC","Africa/Kampala":"Africa/Kampala","America/New_York":"America/New_York","Europe/London":"Europe/London","Asia/Kolkata":"Asia/Kolkata"}', 5),

-- Company Settings
('company_name', 'My Business Ltd', 'company', 'Company Name', 'text', 'Legal name of your business', NULL, 1),
('company_email', 'info@mybusiness.com', 'company', 'Company Email', 'email', 'Primary business contact email address', NULL, 2),
('company_phone', '+1 555-0199', 'company', 'Company Phone', 'text', 'Primary business contact telephone number', NULL, 3),
('company_address', '123 Enterprise Way, Tech City', 'company', 'Street Address', 'textarea', 'Physical business address printed on invoices', NULL, 4),
('company_city', 'Tech City', 'company', 'City / State', 'text', 'City, state or region', NULL, 5),
('company_country', 'United States', 'company', 'Country', 'text', 'Country where business is registered', NULL, 6),
('company_tax_id', 'TAX-99887766', 'company', 'Tax ID / VAT Registration', 'text', 'Tax registration number displayed on tax invoices', NULL, 7),
('company_logo', '', 'company', 'Company Logo', 'file', 'Upload business logo for invoices and document headers', NULL, 8),

-- Invoice & Finance Settings
('invoice_prefix', 'INV-', 'invoice', 'Invoice Number Prefix', 'text', 'Prefix prepended to generated invoice numbers', NULL, 1),
('estimate_prefix', 'EST-', 'invoice', 'Estimate Number Prefix', 'text', 'Prefix prepended to generated estimate numbers', NULL, 2),
('po_prefix', 'PO-', 'invoice', 'Purchase Order Prefix', 'text', 'Prefix prepended to purchase order numbers', NULL, 3),
('expense_prefix', 'EXP-', 'invoice', 'Expense Prefix', 'text', 'Prefix prepended to expense tracking numbers', NULL, 4),
('invoice_due_days', '30', 'invoice', 'Default Payment Due (Days)', 'number', 'Number of days before an invoice becomes overdue', NULL, 5),
('tax_rate', '16', 'invoice', 'Default VAT / Tax Rate (%)', 'number', 'Default percentage rate applied to taxable sales items', NULL, 6),
('invoice_terms', 'Payment is due within 30 days of invoice date. Thank you for your business!', 'invoice', 'Default Terms & Conditions', 'textarea', 'Terms printed at the bottom of all sales invoices', NULL, 7),
('invoice_footer', 'If you have any questions concerning this invoice, please contact accounting.', 'invoice', 'Default Invoice Footer Note', 'textarea', 'Footer message displayed on printed documents', NULL, 8),

-- Email / SMTP Settings
('mail_driver', 'smtp', 'email', 'Mail Delivery Protocol', 'select', 'Method used for sending system notifications', '{"smtp":"SMTP Server","mail":"PHP mail()","sendmail":"Sendmail"}', 1),
('smtp_host', 'smtp.gmail.com', 'email', 'SMTP Server Host', 'text', 'Hostname of your outgoing mail server', NULL, 2),
('smtp_port', '587', 'email', 'SMTP Server Port', 'number', 'Server port (e.g. 587 for TLS, 465 for SSL)', NULL, 3),
('smtp_user', '', 'email', 'SMTP Username / Email', 'email', 'Username or email address for SMTP authentication', NULL, 4),
('smtp_pass', '', 'email', 'SMTP Password', 'text', 'Password for SMTP authentication', NULL, 5),
('smtp_encryption', 'tls', 'email', 'Security Encryption', 'select', 'Encryption protocol for mail connection', '{"tls":"TLS","ssl":"SSL","none":"None"}', 6),
('mail_from_address', 'noreply@mybusiness.com', 'email', 'Sender Email Address', 'email', 'Default address displayed in "From" field of outgoing emails', NULL, 7),
('mail_from_name', 'My Business ERP', 'email', 'Sender Name', 'text', 'Default display name for outgoing emails', NULL, 8);

-- Seed Leave Types
INSERT INTO `leave_types` (`id`, `leave_type`, `description`, `is_active`) VALUES
(1, 'Annual Leave', 'Paid annual vacation leave', 1),
(2, 'Sick Leave', 'Medical leave for illness', 1),
(3, 'Maternity Leave', 'Parental leave for mothers', 1),
(4, 'Paternity Leave', 'Parental leave for fathers', 1);

-- Seed Chart of Accounts
INSERT INTO `chart_of_accounts` (`id`, `account_code`, `account_name`, `account_type`, `opening_balance`, `current_balance`, `is_active`, `is_system`) VALUES
(1, '1000', 'Petty Cash', 'asset', 0.00, 0.00, 1, 1),
(2, '1010', 'Main Bank Account', 'asset', 0.00, 0.00, 1, 1),
(3, '1200', 'Accounts Receivable', 'asset', 0.00, 0.00, 1, 1),
(4, '1300', 'Inventory Account', 'asset', 0.00, 0.00, 1, 1),
(5, '2000', 'Accounts Payable', 'liability', 0.00, 0.00, 1, 1),
(6, '2200', 'Sales Tax Payable', 'liability', 0.00, 0.00, 1, 1),
(7, '3000', 'Retained Earnings', 'equity', 0.00, 0.00, 1, 1),
(8, '4000', 'Sales Revenue', 'income', 0.00, 0.00, 1, 1),
(9, '5000', 'Cost of Goods Sold', 'expense', 0.00, 0.00, 1, 1),
(10, '5100', 'Rent Expense', 'expense', 0.00, 0.00, 1, 1),
(11, '5200', 'Salaries & Wages Expense', 'expense', 0.00, 0.00, 1, 1);
