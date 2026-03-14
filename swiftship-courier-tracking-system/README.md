# SwiftShip Couriers - Tracking System

**Tagline:** Fast Delivery. Smart Tracking.

A complete Courier / Parcel Tracking System built with HTML, CSS, Bootstrap, PHP (Core), and MySQL. This system allows admins to manage shipments and customers to track their parcels with real-time timeline updates.

## Features

**Customer Features:**
- Professional Homepage with Service overview.
- Public Tracking Page: Enter a Tracking ID (e.g., SS123456) to view shipment details.
- Real-time Shipment Timeline (Booked, Arrived at Hub, In Transit, Out for Delivery, Delivered).

**Admin Features:**
- Secure Admin Authentication (Session & Password Hash protected).
- Admin Dashboard with system-wide courier statistics (Total Shipments, In Transit, Out for Delivery, Delivered).
- Shipment Creation: Automatically generates unique `SS + 6 digit` Tracking IDs.
- Shipment Management: View, update, and delete shipments.
- Update Tracking Timeline: Add status updates with locations/timestamps.

## Prerequisites

- XAMPP / WAMP server.
- PHP 7.4 or higher.
- MySQL.

## Installation & Setup

1. **Clone the repository:**
   Move the `swiftship-courier-tracking-system` folder into your local server's `htdocs` (XAMPP) or `www` (WAMP) directory.

2. **Database Setup:**
   - Open phpMyAdmin.
   - Run the SQL script located in `database/schema.sql`.
   - This will create the `swiftship_db` database, necessary tables, and a default admin account.
   
3. **Database Configuration:**
   - Open `config/db.php`.
   - Update the `$user` and `$pass` if your local MySQL uses different credentials (default is `root` and empty password).

4. **Access the Application:**
   - Public Site: `http://localhost/swiftship-courier-tracking-system/public/index.php`
   - Admin Login: `http://localhost/swiftship-courier-tracking-system/admin/login.php`

## Default Admin Credentials

- **Username:** `admin`
- **Password:** `password123`

*(Change the password immediately after login for security purposes).*

## Folder Structure

```
/swiftship-courier-tracking-system
│── /admin
│   ├── create_shipment.php
│   ├── dashboard.php
│   ├── login.php
│   ├── logout.php
│   ├── shipment_detail.php
│   └── shipments.php
│── /assets
│   ├── /css
│   │   └── style.css
│   ├── /images
│   └── /js
│── /config
│   └── db.php
│── /database
│   └── schema.sql
│── /public
│   ├── index.php
│   └── track.php
│── index.php
└── README.md
```

## Security Measures

- **Prepared Statements:** Prevents SQL Injection.
- **Password Hashing:** Uses `password_hash()` for secure admin storage.
- **Session Protection:** Admin routes are secured against unauthorized access.
- **Input Validation:** Prevents XSS attacks on public tracking requests.
