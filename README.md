(C) Copyright 2025 by Dale Scott and Ulian Shahnovich
All rights reserved.

# phpMyAdmin Setup Guide

This guide provides basic instructions for setting up and troubleshooting phpMyAdmin, specifically tailored for a **local XAMPP environment**.

---

## Prerequisites and Server Management (XAMPP)

Before starting, ensure your Web Server (Apache) and Database Server (MariaDB/MySQL) are running.

### Web Server (Apache)

| Action | Command |
| :--- | :--- |
| **Restart Apache** | `sudo /Applications/XAMPP/xamppfiles/xampp restartapache` |

### Database Server (MariaDB/MySQL)

Use the following commands to manage your database server.

| Action | Command |
| :--- | :--- |
| **Start Server** | `sudo /Applications/XAMPP/bin/mysql.server start` |
| **Stop Server** | `sudo /Applications/XAMPP/bin/mysql.server stop` |
| **Restart Server** | `sudo /Applications/XAMPP/bin/mysql.server restart` |

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

```bash
sudo chmod 755 /Applications/XAMPP/xamppfiles/phpmyadmin