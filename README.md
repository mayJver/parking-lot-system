# Car Workshop Management System

A robust, fully functional web-based system designed to manage daily operations for automotive workshops. This project demonstrates solid backend architecture, relational database design, and secure server communication.

## Overview

The Workshop Management System streamlines the workflow between administrators and mechanics. It allows for tracking vehicle history, scheduling preventive maintenance, and logging complex repairs using a strictly enforced relational database structure to ensure long-term data integrity.

## Tech Stack & Architecture

*   **Backend:** PHP (Native)
*   **Database:** MySQL / MariaDB
*   **Connection Protocol:** PDO (PHP Data Objects) with Prepared Statements and disabled emulation to strictly prevent SQL Injection.
*   **Architecture:** Custom Relational Schema (DDL/DML) enforcing `FOREIGN KEY` constraints and optimized indexing.
*   **Deployment Environment:** Linux (LAMP stack compatible).

## Key Features

*   **Role-Based Access Control (RBAC):** Differentiates system permissions between Administrators and Mechanics.
*   **Vehicle Tracking:** Registers vehicles with specific attributes (license plates, model, fuel type) and tracks their current physical status.
*   **Maintenance & Repair Logs:** Relational logs that dynamically tie specific vehicles and mechanics to detailed service entries.
*   **Data Integrity:** Implements `ON DELETE CASCADE` and restrictive relational logic to prevent orphaned records in the database.

##Installation & Local Setup

To run this project locally, you will need a standard LAMP/XAMPP environment (PHP and MySQL/MariaDB).

### 1. Clone the repository
Open your terminal and clone this project into your local server directory (e.g., `htdocs` for XAMPP or `/var/www/html` for standard Apache):

```bash
git clone [https://github.com/mayJver/parking-lot-system.git](https://github.com/mayJver/parking-lot-system.git)
cd parking-lot-system

2. Database Setup

This project includes a complete SQL script with the database architecture and seed data to test the system immediately.

Option A: Using the Command Line (Recommended)
Bash

# 1. Access your MySQL console and create the database
mysql -u root -p -e "CREATE DATABASE estacionamiento;"

# 2. Import the schema and seed data
mysql -u root -p estacionamiento < database/parking_schema.sql

Option B: Using phpMyAdmin

    Open phpMyAdmin in your browser (http://localhost/phpmyadmin).

    Create a new database named estacionamiento.

    Navigate to the Import tab.

    Upload the database/parking_schema.sql file and click Go.

3. Environment Configuration

Since the actual database credentials are intentionally ignored via .gitignore for security reasons, you need to set up your local connection:

    1.-Locate the conexion.example.php file in the root directory.

    2.-Duplicate it and rename the copy to conexion.php.

    3.-Open conexion.php and update the credentials to match your local MySQL server environment.

Once configured, simply navigate to http://localhost/parking-lot-system in your browser to start using the system.


