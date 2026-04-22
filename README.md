# Simple PHP Task Manager

A clean, modern, and simple task management platform built with vanilla PHP, MySQL, and Tailwind CSS. It features a Kanban-style board, task assignment, and user management without the complexity of heavy frameworks.

## Features

- **Kanban Board:** View tasks in To Do, In Progress, and Done columns.
- **Task Management:** Create, edit, and delete tasks with details, assignee, URL, and assignment date.
- **User Management:** Add team members and assign tasks to them.
- **Modern UI/UX:** Built with Tailwind CSS for a responsive, clean dashboard layout.
- **Vanilla PHP:** Lightweight and easy to understand MVC-like structure using PDO.

---

## 💻 Local Installation

1. **Prerequisites:** 
   - PHP 7.4+ or 8.x
   - MySQL / MariaDB
   - Web server (Apache/Nginx) or use PHP's built-in server.

2. **Clone / Extract the project:**
   Extract the files into your web server's root directory (e.g., `htdocs` or `www`) or any local folder.

3. **Database Setup:**
   - Open your MySQL client (like phpMyAdmin or CLI).
   - Import the `database.sql` file provided in the root directory:
     ```bash
     mysql -u root -p < database.sql
     ```
   - This will create a database named `simple_task_manager` with `users` and `tasks` tables.

4. **Configuration:**
   - Open `config/database.php`.
   - Update the `$username` and `$password` if your local MySQL uses different credentials (default is usually `root` and empty password `""`).

5. **Run the Application:**
   - If using XAMPP/MAMP, navigate to `http://localhost/your_folder/public/index.php`.
   - Or, use PHP's built-in server from the project root:
     ```bash
     php -S localhost:8000 -t public/
     ```
   - Open `http://localhost:8000` in your browser.

---

## 🚀 Deployment to VPS (Ubuntu)

This guide covers deploying the app to an Ubuntu VPS using Apache.

### 1. Update Server & Install Apache
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2
```

### 2. Install PHP and Extensions
```bash
sudo apt install php libapache2-mod-php php-mysql php-cli php-pdo -y
```

### 3. Install MySQL Server
```bash
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql
```
*Run `sudo mysql_secure_installation` to set up a root password and secure the database.*

### 4. Create Database and User
Log into MySQL:
```bash
sudo mysql -u root -p
```
Run the following SQL commands:
```sql
CREATE DATABASE simple_task_manager;
CREATE USER 'taskuser'@'localhost' IDENTIFIED BY 'StrongPassword123!';
GRANT ALL PRIVILEGES ON simple_task_manager.* TO 'taskuser'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 5. Import Schema
Upload the project files to your server (e.g., using `scp` or `git clone`). Assuming files are in `/var/www/taskmanager`.
Import the schema:
```bash
mysql -u taskuser -p simple_task_manager < /var/www/taskmanager/database.sql
```

### 6. Configure Database Connection in PHP
Edit `/var/www/taskmanager/config/database.php`:
```php
private $db_name = "simple_task_manager";
private $username = "taskuser";
private $password = "StrongPassword123!"; // Password from step 4
```

### 7. Setup Virtual Host & Permissions
Move files to the web directory if not already there:
```bash
sudo mv /path/to/project_todo /var/www/taskmanager
```

Set permissions:
```bash
sudo chown -R www-data:www-data /var/www/taskmanager
sudo chmod -R 755 /var/www/taskmanager
```

Create an Apache config file:
```bash
sudo nano /etc/apache2/sites-available/taskmanager.conf
```
Add the following content:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/taskmanager/public

    <Directory /var/www/taskmanager/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/taskmanager_error.log
    CustomLog ${APACHE_LOG_DIR}/taskmanager_access.log combined
</VirtualHost>
```

Enable the site and rewrite module:
```bash
sudo a2ensite taskmanager.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### 8. (Optional) Setup Domain and SSL
To secure your site with HTTPS, use Let's Encrypt Certbot:
```bash
sudo apt install certbot python3-certbot-apache -y
sudo certbot --apache -d yourdomain.com
```
Follow the prompts to enable HTTPS. Your site is now secure!
