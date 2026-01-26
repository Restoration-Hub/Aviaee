(C) Copyright 2025 by Dale Scott and Ulian Shahnovich
All rights reserved.

# phpMyAdmin Setup Guide

This guide provides basic instructions for setting up and troubleshooting phpMyAdmin, specifically tailored for a **local XAMPP environment**.

---

## Prerequisites and Server Management (XAMPP)

Before starting, ensure your Web Server (Apache) and Database Server (MariaDB/MySQL) are running.

If you are on Windows, make sure to navigate to the xammp folder before running the commands below:
`cd C:\xampp`

### Web Server (Apache)

| Action | Mac Command | Windows Command |
| :--- | :--- | :--- |
| **Restart Apache** | `sudo /Applications/XAMPP/xamppfiles/xampp restartapache` | `.\apache_stop.bat; .\apache_start.bat` |

### Database Server (MariaDB/MySQL)

Use the following commands to manage your database server.

| Action | Mac Command | Windows Command |
| :--- | :--- | :--- |
| **Start Server** | `sudo /Applications/XAMPP/bin/mysql.server start` | `.\mysql_start.bat` |
| **Stop Server** | `sudo /Applications/XAMPP/bin/mysql.server stop` | `.\mysql_stop.bat` |
| **Restart Server** | `sudo /Applications/XAMPP/bin/mysql.server restart` | `.\mysql_stop.bat; .\mysql_start.bat` |

---

## 1. Installation

* **Note:** If you are using XAMPP, phpMyAdmin is usually pre-installed and configured. 

---

## 2. Access phpMyAdmin

Once your Apache and MariaDB services are running, open your web browser and navigate to the following URL:

`http://localhost/phpmyadmin/`

---

## Troubleshooting: World Writable Error

If you encounter the **"Configuration file is world writable"** error upon accessing phpMyAdmin, run the following command in your terminal to fix the permissions:

Mac:
```bash
sudo chmod 755 /Applications/XAMPP/xamppfiles/phpmyadmin
```
Windows:
* Open PowerShell as Administrator and replace `%USERNAME%`
```bash
icacls "C:\xampp\phpMyAdmin" /grant %USERNAME%:F /T
```

If the above does not work, go to config.inc.php 'Get Info' and change admin and everyone permissions to 'Read Only' 

---

## Architecture 

[HTTP Request] → Controller → Use Case → Interface → Repository → Model → Database

### HTTP Request

This is the request from the user, like submitting an email and password.
Example: someone types their email and password and clicks “Login”.

### Controller

The controller receives the request and talks to the use case.
It does not decide business rules — just passes the request along and later returns a response.

### Use Case

The use case is the business logic.
It decides what should happen, like checking if the email exists and if the password is correct.
It only works with interfaces and domain entities, not the database directly.

### Interface

The interface is a contract that says “this is what the use case expects a repository to do.”
It doesn’t know how data is stored — only what methods exist.

### Repository

The repository implements the interface.
It knows how to fetch or save data, using models and the database.
Example: UserRepository fetches a user from the database.

### Model

The model is Laravel’s way of representing a database table in PHP.
It helps the repository read and write data in the database.

### Database

The actual MariaDB database where all the data is stored.
The model/repository sends queries here to fetch or save information.

