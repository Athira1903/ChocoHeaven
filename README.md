🍫 ChocoHeaven - Premium Chocolate E-Commerce Platform
A full-stack M-Commerce web application built with PHP OOP, MySQL, and modern web technologies. This project demonstrates advanced web programming concepts with a complete online chocolate store.

📋 Table of Contents
Project Overview

Features

Technology Stack

Installation

Admin Panel

Database Schema

OOP Implementation

API Endpoints

Deployment

Support

🎯 Project Overview
ChocoHeaven is a sophisticated e-commerce platform specializing in premium chocolates with Indian-inspired flavors. The application features user authentication, product catalog, shopping cart, secure payments, and order management.

Key Highlights
Full OOP PHP Implementation with MVC architecture

Responsive Design with Bootstrap 5

Razorpay Payment Integration for secure transactions

Admin Panel for complete store management

AWS EC2 Deployment ready

✨ Features
🛒 Customer Features
User Registration & Login - Secure authentication system

Product Catalog - Browse with search and category filtering

Shopping Cart - Persistent cart with localStorage

Secure Checkout - Razorpay payment integration

Order History - Complete order tracking

User Profile - Account management

⚙️ Admin Features
Dashboard - Store analytics and statistics

Product Management - Add, edit, delete products

Order Management - View and update order status

User Management - Customer account oversight

Inventory Control - Stock management

🛠️ Technology Stack
Backend
PHP 7.4+ - Object-Oriented Programming

MySQL - Database management

PDO - Database abstraction layer

Razorpay API - Payment gateway integration

Frontend
HTML5 - Semantic markup

CSS3 - Custom brown theme

JavaScript - Dynamic interactions

Bootstrap 5 - Responsive framework

Architecture
MVC Pattern - Model-View-Controller architecture

OOP Principles - Encapsulation, Inheritance, Polymorphism, Abstraction

🚀 Installation
Prerequisites
XAMPP/WAMP/MAMP stack

PHP 7.4 or higher

MySQL 5.7 or higher

Web browser with JavaScript enabled

Step 1: Environment Setup
Install XAMPP from Apache Friends

Start Apache and MySQL services

Clone project to htdocs/chocoheaven/

Step 2: Database Configuration
sql
CREATE DATABASE chocoheaven;
USE chocoheaven;

-- Import the complete schema from database/schema.sql
-- Or run individual table creation scripts
Step 3: Configuration
Update config/database.php with your credentials:

php
private $host = "localhost";
private $db_name = "chocoheaven";
private $username = "root";
private $password = ""; // Your MySQL password
Step 4: Access Application
Open your browser and navigate to:

text
http://localhost/chocoheaven
👨‍💼 Admin Panel
Access Admin
Navigate to: http://localhost/chocoheaven/admin-login.php

Login with credentials

Admin Features
Dashboard - Store overview and analytics

Products - Add, edit, delete chocolate products

Orders - View and manage customer orders

Users - Customer account management

🗃️ Database Schema
Core Tables
users - Customer accounts and authentication

products - Chocolate product catalog

orders - Customer order records

order_items - Individual order line items

payments - Payment transaction records

Sample Data
The database includes sample chocolate products with Indian-inspired flavors:

Belgian Dark Chocolate

Masala Chai Spice Chocolate

Diwali Special Gift Box

Gulab Jamun Chocolate Bar

Mango Lassi White Chocolate

🏗️ OOP Implementation
Class Hierarchy
text
Model (Abstract)
├── Product
│   ├── create(), read(), update(), delete()
│   ├── readByCategory(), searchProducts()
├── User
│   ├── login(), register(), validatePassword()
├── Order
│   ├── createOrderWithItems(), getOrdersByUser()
└── OrderItem
    ├── getOrderItems()
Key OOP Concepts
Encapsulation - Private properties with public getters/setters

Inheritance - All models extend base Model class

Abstraction - Abstract methods in base class

Polymorphism - Different CRUD implementations

🌐 API Endpoints
Payment Processing
create_razorpay_order.php - Creates Razorpay order

process_payment.php - Handles payment verification

Example Usage
javascript
// Create Razorpay order
fetch('create_razorpay_order.php', {
    method: 'POST',
    body: JSON.stringify({ amount: 50000, currency: 'INR' })
})
☁️ Deployment
AWS EC2 Deployment
Launch EC2 Instance - t2.micro (Free Tier)

Install LAMP Stack - Apache, PHP, MySQL

Upload Application - SCP or Git clone

Configure Database - Import schema and data

Set Permissions - Proper file permissions

Test Application - Verify all functionality

Deployment Steps
bash
# Update system
sudo yum update -y

# Install Apache, PHP, MySQL
sudo yum install -y httpd php php-mysqli mysql-server

# Start services
sudo systemctl start httpd
sudo systemctl start mysqld
🎓 Educational Value
This project demonstrates:

Advanced PHP OOP concepts and patterns

Full-stack web development skills

E-commerce architecture and payment integration

Database design and management

Cloud deployment on AWS EC2

Version control with GitHub



👥 Development Team
Developer: Athira

Course: MCA Integrated

Institution: Amal Jyothi College of Engineering

Subject: Advanced Web Programming,Advanced Software Engineering,M-Commerce 

🔄 Version History
v1.0 - Basic e-commerce functionality

v1.1 - OOP implementation and Razorpay integration

v1.2 - Admin panel and enhanced UI/UX

v1.3 - AWS deployment and documentation

📄 License
This project is developed for educational purposes as part of the Advanced Web Programming course.

🍫 Enjoy exploring ChocoHeaven!

For any issues, refer to the troubleshooting section or contact support.
