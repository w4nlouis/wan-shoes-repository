# 👟 Wan Shoes Inventory Management System

## Project Overview
A complete web-based inventory and e-commerce system for Wan Shoes shop. The system supports internal staff (Admin and Manager) as well as external customers. Built with PHP, MySQL, HTML, CSS, and JavaScript.

## Technologies
- PHP + MySQL
- HTML/CSS
- JavaScript (form validation, password strength)
- XAMPP (Local Development)
- GitHub (Version Control)

## Features
- Role-based login (Admin, Manager, Customer)
- Product management (CRUD operations)
- Employee management (Admin only - Hire/Fire Managers)
- Sales recording with revenue totals
- Stock management (Add Stock with audit trail)
- Low stock alerts (auto-detected for stock < 5)
- Customer registration and login
- Shopping cart (session-based)
- Checkout with order placement
- Order history with status tracking
- Admin order management (status updates)
- Customer profile management
- Sales charts (Last 7 days)
- Password hashing (bcrypt)
- Remember Me (cookies)
- Unauthorized access redirect
- Password strength checker
- Show/Hide password toggle

## Database Structure
- admins
- employees
- customers
- products
- sales
- stock_records
- orders
- order_items

## Installation
1. Copy folder to `C:\xampp\htdocs\wan_shoes`
2. Import `wan_shoes_db.sql` to phpMyAdmin
3. Update `includes/config.php` with your database credentials
4. Access at `http://localhost/wan_shoes/login.php`

## Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@wanshoes.com | admin123 |
| Manager | manager@wanshoes.com | manager123 |
| Customer (Demo) | karogo@gmail.com | karogo123 |

**Register New Customer:** `http://localhost/wan_shoes/customer/register.php`

## Author
Louis Wanyoike  
BSCCS/2024/52266

## Repository
https://github.com/w4nlouis/wan-shoes-repository

## Course Information
- **Unit:** BIT3208 - Advanced Web Design and Development
- **Lecturer:** Michael Nyoro