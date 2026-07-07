# 📚 Book Manager

A simple **Book Management System** developed as part of the **Azura Labs** technical assignment. This application is built using **Native PHP**, **MySQL**, and **Tailwind CSS**, implementing a clean and lightweight CRUD architecture without using any framework.

---

## 🚀 Tech Stack

* **Backend:** Native PHP
* **Frontend:** Native PHP
* **Styling:** Tailwind CSS
* **Database:** MySQL / MariaDB
* **Database Access:** PDO (PHP Data Objects)

---

## 📂 Project Structure

```text
BOOK_MANAGER/
├── assets/                 # CSS, JS, images
├── config/
│   └── database.php        # Database configuration
├── books/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── categories/
│   ├── index.php
│   ├── create.php
│   ├── edit.php
│   └── delete.php
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
├── database.sql
├── index.php
└── README.md
```

---

## ✨ Features

### 📖 Book Management

* View all books
* Add new book
* Edit book information
* Delete book
* Book category relationship
* Publication date management
* Author and publisher management
* Number of pages management

### 🏷️ Category Management

* View all categories
* Create category
* Edit category
* Delete category
* Prevent duplicate category names

### 🎨 User Interface

* Responsive design using Tailwind CSS
* Clean and modern interface
* Simple navigation
* User-friendly forms
* Confirmation before deleting data

### 🗄️ Database

* MySQL relational database
* UUID as primary key
* Foreign key constraints
* Automatic timestamps
* PDO prepared statements for secure database access

---

## 🛠 Installation

### 1. Clone Repository

```bash
git clone https://github.com/arselia/book-manager.git
cd book-manager
```

### 2. Create Database

Import the provided `database.sql` file into MySQL.

#### Option 1 — Using phpMyAdmin

1. Open **phpMyAdmin**
2. Click **Import**
3. Choose the `database.sql` file
4. Click **Go**

#### Option 2 — Using MySQL Command Line

```bash
mysql -u root -p < database.sql
```

The script will automatically:

* Create the `booklist_db` database
* Create the `categories` table
* Create the `books` table
* Insert sample categories
* Insert sample book data

---

### 3. Configure Database Connection

Open:

```text
config/database.php
```

Update your database credentials:

```php
$host = "localhost";
$dbname = "booklist_db";
$username = "root";
$password = "";
```

Adjust the username and password according to your local MySQL configuration.

---

### 4. Run the Application

If using PHP Built-in Server:

```bash
php -S localhost:8000
```

Then open:

```
http://localhost:8000
```

Or place the project inside your web server directory:

* XAMPP → `htdocs`
* Laragon → `www`
* MAMP → `htdocs`

---

## 🗃 Database Schema

### Categories

| Field      | Type         |
| ---------- | ------------ |
| id         | UUID         |
| name       | VARCHAR(150) |
| created_at | TIMESTAMP    |
| updated_at | TIMESTAMP    |

### Books

| Field            | Type               |
| ---------------- | ------------------ |
| id               | UUID               |
| title            | VARCHAR(255)       |
| author           | VARCHAR(255)       |
| publication_date | DATE               |
| publisher        | VARCHAR(255)       |
| num_pages        | INT                |
| category_id      | UUID (Foreign Key) |
| created_at       | TIMESTAMP          |
| updated_at       | TIMESTAMP          |

---

## 🔒 Security

* PDO Prepared Statements
* Input Validation
* Foreign Key Constraints
* UUID-based Primary Keys

---

## 📈 Future Improvements

* Authentication & Authorization
* Search and filter books
* Pagination
* Image upload for book covers
* Export data to PDF or Excel
* REST API implementation
* Dashboard statistics
* Responsive mobile optimization

---

## 👩‍💻 Developed For

**Azura Labs – Technical Assessment**

Built with ❤️ using Native PHP, MySQL, and Tailwind CSS.
