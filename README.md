# 🌾 Agricultural Bidding System

A comprehensive web-based agricultural auction platform that connects farmers with bidders. Farmers can list their agricultural products, and bidders can place competitive bids in real-time.

---

## 📋 Table of Contents

- [Features](#features)
- [Project Structure](#project-structure)
- [Tech Stack](#tech-stack)
- [Installation & Setup](#installation--setup)
- [Database Schema](#database-schema)
- [User Roles & Workflows](#user-roles--workflows)
- [Configuration](#configuration)
- [Multi-Language Support](#multi-language-support)
- [Security Features](#security-features)
- [API Endpoints](#api-endpoints)
- [Troubleshooting](#troubleshooting)
- [Contact & Support](#contact--support)

---

## 🎯 Features

### Core Features
- ✅ **Role-Based Access Control** - Farmers, Bidders, and Admin roles with specific dashboards
- ✅ **Real-Time Bidding System** - Live auction platform with time validation
- ✅ **Product Listing** - Farmers can add and manage agricultural products
- ✅ **Bid Management** - Track bids, view bid history, and finalize sales
- ✅ **Responsive Design** - Mobile, tablet, and desktop optimized interfaces
- ✅ **Multi-Language Support** - 13 Indian languages via Google Translate API

### Farmer Features
- 🚜 Add/manage agricultural products with images
- 📊 View received bids and bid history
- 📈 Track highest bidder information
- 🔔 Monitor auction status and time remaining

### Bidder Features
- 🏆 Browse available products for auction
- 💰 Place competitive bids in real-time
- ⏱️ View countdown timers for active auctions
- 📄 Access bill details and purchase history

### Admin Features
- ✅ Verify/reject pending product listings
- 📋 Manage product catalog
- 👥 View user registration data
- 🔍 Monitor auction completion

---

## 📁 Project Structure

```
kandu3_project1/self/
│
├── 📄 README.md                          # Project documentation
├── 📄 a.php                               # Home page / landing page
├── 📄 db_connection.php                   # Database connection configuration
│
├── 🚜 FARMER PAGES
│   ├── farmer_login.php                  # Farmer login page
│   ├── farmer_sign.html                  # Farmer registration form
│   ├── farmer_data_adding.php            # Process farmer registration
│   ├── farmer_dashboard.php              # Farmer main dashboard
│   ├── ad_product.php                    # Add/manage products
│   ├── view_bill_farm.php                # View sold product details
│
├── 🏆 BIDDER PAGES
│   ├── bidder_login.php                  # Bidder login page
│   ├── bidder_dashboard.php              # Bidder main dashboard
│   ├── biding_page.php                   # Browse products for bidding
│   ├── place_bid.php                     # Process bid placement
│   ├── 21.php                            # Alternative bidding page
│   ├── account_details.php               # View purchase history
│   ├── view_bill.php                     # View bill/invoice
│
├── 👨‍💼 ADMIN PAGES
│   ├── admin_login.php                   # Admin login page
│   ├── admin_dashboard.php               # Admin dashboard
│   ├── admin_checking.php                # Product verification page
│   ├── verify_product.php                # Process product verification
│   ├── account_details_admin.php         # Admin account details
│
├── 🔧 UTILITY PAGES
│   ├── logout.php                        # User logout handler
│   ├── finalize_bids.php                 # Process auction completion
│   ├── auto_bid_update.php               # Batch process for auction closure
│   ├── product_added.php                 # Confirmation page
│   ├── bidder_data_addded.php            # Registration confirmation
│
├── 📦 includes/
│   └── lang_selector.php                 # Multi-language selector component
│
├── 📁 uploads/                           # Product images directory
│   └── [product images]
│
├── 🎨 STYLESHEETS
│   ├── b.css                             # Main stylesheet
│
└── 📜 JAVASCRIPT
    └── c.js                              # Main JavaScript file
```

---

## 🛠️ Tech Stack

| Layer | Technologies |
|-------|--------------|
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Backend** | PHP 7.0+ |
| **Database** | MySQL / MariaDB |
| **Server** | Apache (via XAMPP) |
| **Translation** | Google Translate API |
| **Session Management** | PHP Sessions |
| **Authentication** | Session-based login |

---

## 📦 Installation & Setup

### Prerequisites
- XAMPP (or similar Apache + PHP + MySQL stack)
- Web browser with JavaScript enabled
- Internet connection (for Google Translate API)

### Step 1: Extract Project Files
```bash
# Navigate to XAMPP htdocs directory
cd C:\xampp\htdocs\

# Extract project files to kandu3_project1/self directory
# (Already present in your setup)
```

### Step 2: Create Database
```sql
-- Create the project1 database
CREATE DATABASE IF NOT EXISTS project1;
USE project1;

-- Create users/registration table
CREATE TABLE registration (
    email VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    role ENUM('Farmer', 'Bidder', 'Admin') NOT NULL,
    full_name VARCHAR(255),
    city VARCHAR(100),
    pincode VARCHAR(10)
);

-- Create products table
CREATE TABLE products (
    p_id INT PRIMARY KEY AUTO_INCREMENT,
    farmer_email VARCHAR(255),
    p_name VARCHAR(255),
    min_bidding DECIMAL(10,2),
    bid_end_time DATETIME,
    status ENUM('pending', 'verified', 'sold') DEFAULT 'pending',
    photo1 VARCHAR(255),
    quantity INT,
    time_limit INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (farmer_email) REFERENCES registration(email)
);

-- Create bill/transaction table
CREATE TABLE bill (
    b_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id INT,
    farmer_email VARCHAR(255),
    bidder_email VARCHAR(255),
    bid_amount DECIMAL(10,2),
    bid_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    product_name VARCHAR(255),
    FOREIGN KEY (product_id) REFERENCES products(p_id),
    FOREIGN KEY (farmer_email) REFERENCES registration(email),
    FOREIGN KEY (bidder_email) REFERENCES registration(email)
);
```

### Step 3: Update Database Connection
Edit `db_connection.php` with your database credentials:

```php
<?php
$conn = new mysqli("localhost", "root", "", "project1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

### Step 4: Create Uploads Directory
```bash
# Create directory for product images
mkdir uploads
chmod 755 uploads
```

### Step 5: Start XAMPP
- Open XAMPP Control Panel
- Start Apache and MySQL services
- Navigate to `http://localhost/kandu3_project1/self/a.php`

---

## 📊 Database Schema

### registration table
```
Column      | Type          | Constraints
------------|---------------|------------------
email       | VARCHAR(255)  | PRIMARY KEY
password    | VARCHAR(255)  | NOT NULL
role        | ENUM          | 'Farmer'/'Bidder'/'Admin'
full_name   | VARCHAR(255)  | 
city        | VARCHAR(100)  | 
pincode     | VARCHAR(10)   | 
```

### products table
```
Column        | Type          | Constraints
--------------|---------------|------------------
p_id          | INT           | PRIMARY KEY AUTO_INCREMENT
farmer_email  | VARCHAR(255)  | FOREIGN KEY
p_name        | VARCHAR(255)  | 
min_bidding   | DECIMAL(10,2) | 
bid_end_time  | DATETIME      | 
status        | ENUM          | 'pending'/'verified'/'sold'
photo1        | VARCHAR(255)  | Image path
quantity      | INT           | Product quantity
time_limit    | INT           | Auction duration (hours)
timestamp     | DATETIME      | Creation time
```

### bill table
```
Column         | Type          | Constraints
----------------|---------------|------------------
b_id           | INT           | PRIMARY KEY AUTO_INCREMENT
product_id     | INT           | FOREIGN KEY
farmer_email   | VARCHAR(255)  | FOREIGN KEY
bidder_email   | VARCHAR(255)  | FOREIGN KEY
bid_amount     | DECIMAL(10,2) | 
bid_time       | DATETIME      | Transaction time
product_name   | VARCHAR(255)  | 
```

---

## 👥 User Roles & Workflows

### 🚜 Farmer Workflow
1. **Registration** → Create account at `farmer_sign.html`
2. **Add Products** → Upload agricultural products with details
3. **Wait for Verification** → Admin verifies product listing
4. **Monitor Bids** → View incoming bids on dashboard
5. **Complete Sale** → Bill is auto-generated when auction ends

```
farmer_login.php → farmer_dashboard.php
                ├─→ ad_product.php (add products)
                ├─→ view_bill_farm.php (sold products)
                └─→ account_details_admin.php (profile)
```

### 🏆 Bidder Workflow
1. **Registration** → Create account at `bidder_login.php`
2. **Browse Products** → View available auctions
3. **Place Bids** → Enter bid amount and submit
4. **Win Auction** → Highest bid wins when time expires
5. **View Invoice** → Access bill and transaction details

```
bidder_login.php → bidder_dashboard.php
                ├─→ biding_page.php (browse products)
                ├─→ place_bid.php (submit bids)
                ├─→ account_details.php (purchase history)
                └─→ view_bill.php (invoices)
```

### 👨‍💼 Admin Workflow
1. **Admin Login** → Access `admin_login.php`
2. **Verify Products** → Review pending product listings
3. **Approve/Reject** → Accept or decline farmer submissions
4. **Monitor Platform** → View all auctions and transactions

```
admin_login.php → admin_dashboard.php
               ├─→ admin_checking.php (product verification)
               ├─→ verify_product.php (approve/reject)
               └─→ account_details_admin.php (admin profile)
```

---

## ⚙️ Configuration

### Database Configuration
- **File:** `db_connection.php`
- **Default Settings:**
  - Host: `localhost`
  - User: `root`
  - Password: (empty)
  - Database: `project1`

### Time Zone Settings
- **Current:** UTC (recommended for auctions)
- **Location:** Bidding time validation in `place_bid.php`
- **Adjust if needed:** Modify `new DateTimeZone('UTC')` in PHP files

### Upload Directory
- **Location:** `uploads/` directory
- **Max File Size:** Configure in `php.ini` if needed
- **Allowed Types:** Images (jpg, png, gif)

### Session Configuration
- **Session Timeout:** Default PHP session timeout
- **Session Key:** `$_SESSION['a']` stores user data
- **Logout:** `logout.php` clears all sessions

---

## 🌐 Multi-Language Support

### Supported Languages (13 Indian Languages)
- 🇮🇳 English
- 🇮🇳 Hindi (हिन्दी)
- 🇮🇳 Marathi (मराठी)
- 🇮🇳 Gujarati (ગુજરાતી)
- 🇮🇳 Tamil (தமிழ்)
- 🇮🇳 Telugu (తెలుగు)
- 🇮🇳 Kannada (ಕನ್ನಡ)
- 🇮🇳 Malayalam (മലയാളം)
- 🇮🇳 Bengali (বাংলা)
- 🇮🇳 Punjabi (ਪੰਜਾਬੀ)
- 🇮🇳 Urdu (اردو)
- 🇮🇳 Odia (ଓଡିଆ)
- 🇮🇳 Assamese (অসমীয়া)

### How to Use
- **Location:** Language selector is included on all pages via `includes/lang_selector.php`
- **Features:**
  - 🌐 Dropdown selector with all 13 languages
  - 🍪 Cookie-based language persistence (7 days)
  - 📱 Mobile-responsive design
  - ⚡ Google Translate API integration
  - 🎯 Works on all major browsers

### Implementation
```php
<?php include __DIR__ . '/includes/lang_selector.php'; ?>
```

---

## 🔒 Security Features

### Authentication & Authorization
- ✅ Session-based login system
- ✅ Role-based access control (Farmer/Bidder/Admin)
- ✅ Password storage (recommended: implement hashing)
- ✅ Login validation on every protected page

### Data Protection
- ✅ SQL Injection Prevention (Prepared statements in critical operations)
- ✅ XSS Prevention (htmlspecialchars() for output)
- ✅ CSRF Protection (consider implementing token-based)
- ✅ Input Validation (server-side checks for bid amounts)

### Business Logic Security
- ✅ Server-Side Time Validation (prevents late bids)
- ✅ Atomic Database Transactions (prevents race conditions)
- ✅ Role Verification (only authorized users can perform actions)
- ✅ Bid Amount Validation (must be higher than current bid)

### Recommended Improvements
- 🔐 Implement password hashing (bcrypt or similar)
- 🔐 Add CSRF tokens for form submissions
- 🔐 Implement rate limiting for login attempts
- 🔐 Use HTTPS for production deployment
- 🔐 Add input sanitization for all user inputs

---

## 🔌 API Endpoints

### Bid Placement
```
Endpoint: place_bid.php
Method: POST
Parameters:
  - product_id (INT): ID of product
  - bid_amount (DECIMAL): Bid amount in rupees
Response: 
  - Redirects to biding_page.php on success
  - Shows alert on error
```

### Product Verification (Admin)
```
Endpoint: verify_product.php
Method: POST
Parameters:
  - product_id (INT): ID of product
  - action (STRING): 'accept' or 'reject'
Response:
  - Updates product status
  - Redirects to admin_checking.php
```

### Logout
```
Endpoint: logout.php
Method: GET
Parameters: None
Response:
  - Clears all sessions
  - Redirects to home page (a.php)
```

---

## 🐛 Troubleshooting

### Database Connection Error
**Problem:** "Connection failed" message
**Solution:**
1. Verify MySQL is running in XAMPP
2. Check database credentials in `db_connection.php`
3. Ensure `project1` database exists
4. Check database user permissions

### Login Issues
**Problem:** Cannot login as Farmer/Bidder
**Solution:**
1. Verify account exists in `registration` table
2. Check password is correct (case-sensitive)
3. Clear browser cookies and try again
4. Check PHP session settings in `php.ini`

### Bid Placement Fails
**Problem:** "Auction has already ended" when trying to bid
**Solution:**
1. Verify auction end time hasn't passed
2. Check server time is synchronized
3. Ensure product status is 'verified'
4. Check bid amount is higher than current bid

### Image Upload Issues
**Problem:** Product images not showing
**Solution:**
1. Check `uploads/` directory exists with 755 permissions
2. Verify image file paths are correct in database
3. Check file was uploaded to correct location
4. Ensure image format is supported (jpg, png, gif)

### Language Selector Not Working
**Problem:** Language dropdown not appearing
**Solution:**
1. Verify `includes/lang_selector.php` exists
2. Check include path is correct
3. Ensure Google Translate API is accessible
4. Clear browser cache and cookies

---

## 📝 File Descriptions

| File | Purpose |
|------|---------|
| `a.php` | Home page with navigation |
| `db_connection.php` | MySQL connection configuration |
| `farmer_login.php` | Farmer authentication |
| `bidder_login.php` | Bidder authentication |
| `admin_login.php` | Admin authentication |
| `farmer_dashboard.php` | Farmer main interface |
| `bidder_dashboard.php` | Bidder main interface |
| `admin_dashboard.php` | Admin main interface |
| `ad_product.php` | Add/manage farmer products |
| `place_bid.php` | Process bid submissions |
| `finalize_bids.php` | Mark auctions as completed |
| `auto_bid_update.php` | Batch process for status updates |
| `verify_product.php` | Admin product verification |
| `logout.php` | User session termination |
| `b.css` | Main stylesheet |
| `c.js` | Main JavaScript file |
| `includes/lang_selector.php` | Multi-language component |

---

## 🚀 Deployment

### Local Development
1. Extract files to `C:\xampp\htdocs\kandu3_project1\self\`
2. Start XAMPP (Apache + MySQL)
3. Access via `http://localhost/kandu3_project1/self/a.php`

### Production Deployment
1. Use Linux-based hosting with PHP and MySQL
2. Transfer files via FTP/SFTP
3. Update `db_connection.php` with production credentials
4. Configure proper file permissions (755 for directories, 644 for files)
5. Enable HTTPS/SSL certificate
6. Set up regular database backups
7. Configure firewall and access controls

### Environment Checklist
- [ ] PHP version 7.0 or higher
- [ ] MySQL version 5.7 or higher
- [ ] Apache with mod_rewrite enabled
- [ ] Write permissions on `uploads/` directory
- [ ] `includes/` directory accessible
- [ ] Database user with full permissions on `project1`

---

## 📧 Contact & Support

For issues, questions, or feature requests:
- 📧 Email: [Add your contact email]
- 📞 Phone: [Add phone number]
- 🐛 Report bugs via: [GitHub/Issue tracker]

---

## 📄 License

This project is part of the Kandu Agricultural Platform.
All rights reserved © 2025

---

## 🎯 Future Enhancements

- [ ] Payment gateway integration
- [ ] Email notifications for bid updates
- [ ] SMS alerts for auction endings
- [ ] Advanced search and filtering
- [ ] Mobile app (iOS/Android)
- [ ] Real-time notifications via WebSocket
- [ ] Machine learning for bid recommendations
- [ ] Export bills as PDF
- [ ] Two-factor authentication
- [ ] Advanced analytics dashboard

---

**Last Updated:** October 23, 2025  
**Version:** 1.0  
**Status:** Active Development
