# GlobeTrek Adventures

A travel booking web application for Sri Lankan tour packages, built for **CSE5009 Web Application Development** (Cardiff Metropolitan University, ICBT Campus).

**Student ID:** ST20345189

## Features

- Browse tour packages and view package details
- Customer registration and login
- Book a package and view your own bookings
- Contact form and feedback page
- **Customer dashboard** with booking history
- **Staff dashboard** to update bookings and respond to customer queries
- **Admin dashboard** to manage bookings and tour packages

## Technologies

- PHP (PDO)
- MySQL
- HTML and CSS

## Project Structure

```
globetrek/
├── admin/        Admin pages (dashboard, manage/delete bookings)
├── config/       Database connection (database.php)
├── css/          Stylesheet
├── customer/     Customer dashboard and bookings
├── images/       Package and hero images
├── staff/        Staff dashboard, booking and query updates
└── *.php         Public pages (index, packages, booking, login, register, ...)
```

## How to Run

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache** and **MySQL**.
2. Copy the `globetrek` folder into `C:\xampp\htdocs\`.
3. Open **phpMyAdmin** (http://localhost/phpmyadmin), create a database named `globetrek`, then **Import** the `globetrek.sql` file.
4. Check `config/database.php` (default: host `localhost`, user `root`, empty password).
5. Open http://localhost/globetrek/ in your browser.

## Database

Main tables: `users`, `packages`, `bookings`, `queries`, `feedback`.
