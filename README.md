# GlobeTrek Adventures

GlobeTrek Adventures is a travel booking web application for Sri Lankan tour packages, built as a Web Application Development coursework project. Visitors can explore destinations, register, and book tours online, while staff and administrators manage bookings, customer queries and the package catalogue through role-based dashboards.

## Table of Contents

- [Features](#features)
- [User Roles](#user-roles)
- [Technologies Used](#technologies-used)
- [Project Structure](#project-structure)
- [Database](#database)
- [Installation and Setup](#installation-and-setup)
- [Security Practices](#security-practices)
- [Destinations Covered](#destinations-covered)

## Features

### Public (visitors)
- Home page with a hero section and featured destinations
- Browse all tour packages and view full package details
- Contact form for sending enquiries to the team
- Feedback form with a star rating and comments
- User registration and login

### Customers
- Book a tour package by choosing a travel date (past dates are rejected)
- Customer dashboard with an overview of activity
- "My Bookings" page showing every booking and its status (pending, confirmed or cancelled)

### Staff
- Staff dashboard listing bookings and customer queries
- Confirm or cancel bookings
- Mark customer queries as responded

### Administrators
- Admin dashboard with summary counts (users, packages, bookings, queries)
- View booking details and update booking status
- Delete bookings
- Add and delete tour packages, including image upload

## User Roles

| Role | Access |
|------|--------|
| Customer | Book tours, view own bookings |
| Staff | Handle bookings and customer queries |
| Admin | Full management of bookings and packages, plus dashboard statistics |

After login, users are redirected to the dashboard that matches their role, and pages are protected using PHP sessions.

## Technologies Used

- **Backend:** PHP 8 with PDO (prepared statements)
- **Database:** MySQL / MariaDB
- **Frontend:** HTML5 and CSS3
- **Local server:** XAMPP (Apache + MySQL)

## Project Structure

```
globetrek/
├── admin/
│   ├── booking-details.php
│   ├── dashboard.php
│   ├── delete-booking.php
│   └── manage-bookings.php
├── config/
│   └── database.php          # PDO database connection
├── css/
│   └── style.css
├── customer/
│   ├── dashboard.php
│   └── my-bookings.php
├── images/                   # Destination and hero images
├── staff/
│   ├── dashboard.php
│   ├── update-booking.php
│   └── update-query.php
├── booking.php
├── contact.php
├── feedback.php
├── index.php
├── login.php
├── logout.php
├── manage-packages.php
├── package-details.php
├── packages.php
└── register.php
```

## Database

Database name: `globetrek`

| Table | Purpose |
|-------|---------|
| `users` | Registered accounts with name, email, hashed password and role |
| `packages` | Tour packages (details, price, image) |
| `bookings` | Customer bookings linked to a user and a package, with status |
| `queries` | Messages submitted through the contact form, with status |
| `feedback` | Ratings and comments submitted by visitors |

The exported database file `globetrek.sql` is included so the project can be set up quickly.

## Installation and Setup

1. **Install XAMPP** from [apachefriends.org](https://www.apachefriends.org/) and start **Apache** and **MySQL** from the XAMPP Control Panel.
2. **Copy the project:** place the `globetrek` folder inside `C:\xampp\htdocs\`.
3. **Create the database:**
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Click **New** and create a database named `globetrek`
   - Select it, open the **Import** tab, choose `globetrek.sql` and click **Import**
4. **Check the connection settings** in `config/database.php`. The defaults are:
   ```php
   $host = "localhost";
   $dbname = "globetrek";
   $username = "root";
   $password = "";
   ```
5. **Run the site:** open [http://localhost/globetrek/](http://localhost/globetrek/) in your browser.

To try the different dashboards, register a customer account from the registration page and use the staff and admin accounts stored in the imported database.

## Security Practices

- Passwords are stored with `password_hash()` and checked with `password_verify()`
- All database queries use PDO prepared statements to prevent SQL injection
- Output is escaped with `htmlspecialchars()` to reduce XSS risk
- Role checks on protected pages using PHP sessions
- Input validation on forms (for example, travel dates must be in the future)

## Destinations Covered

Anuradhapura, Ella, Galle, Kandy, Mirissa, Sigiriya and Yala.
