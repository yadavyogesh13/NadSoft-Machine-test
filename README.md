# Business Listing & Rating System

A professional Business Listing and Rating system built with Core PHP, MySQL, and jQuery.

## Features
- **Business CRUD**: Add, Edit, and Soft Delete businesses via AJAX-powered Bootstrap modals.
- **Rating System**: 
    - Half-star support using the Raty jQuery plugin.
    - Real-time average rating calculation.
    - Rule-based rating logic: Updates existing rating if the same Email or Phone is used for a business; otherwise, inserts a new one.
    - Soft delete support for ratings.
- **Real-time UI**: Tables update dynamically without page refreshes.

## Setup Instructions
1. **Clone Repository**:
    - git clone https://github.com/yadavyogesh13/NadSoft-Machine-test.git

2. **Move Project**:
    - Place the project folder inside `xampp/htdocs/business-listing`
    - Ensure PHP is installed and running.

3. **Database Setup**:
    - Import the `db.sql` file into your MySQL database (e.g., using phpMyAdmin or the command line).
    - By default, the system looks for a database named `business_listing` with user `root` and no password.

4. **Access**:
    - Open your browser and navigate to `http://localhost/business-listing`
