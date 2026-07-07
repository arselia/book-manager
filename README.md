# Book Manager

A simple **Book Management System** developed as part of the **Azura Labs** technical assignment. This application is built using **Native PHP**, **MySQL**, and **Tailwind CSS**, implementing a clean and lightweight CRUD architecture without using any framework.

---

## Tech Stack

* **Backend:** Native PHP
* **Frontend:** Native PHP
* **Styling:** Tailwind CSS
* **Database:** MySQL / MariaDB
* **Database Access:** PDO (PHP Data Objects)

---

## Project Structure

```text
BOOK_MANAGER/
├── backend/
│   ├── api/
│   │   ├── books/
│   │   │   ├── create_book.php
│   │   │   ├── delete_book.php
│   │   │   ├── get_books.php
│   │   │   └── update_book.php
│   │   │
│   │   ├── category/
│   │   │   ├── create_category.php
│   │   │   ├── delete_category.php
│   │   │   ├── get_categories.php
│   │   │   └── update_category.php
│   │   │
│   │   └── get_dashboard.php
│   │
│   └── config/
│       └── database.php
│
├── frontend/
│   ├── books/
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── index.php
│   │
│   ├── categories/
│   │   ├── create.php
│   │   ├── edit.php
│   │   └── index.php
│   │
│   └── includes/
│       ├── header.php
│       └── footer.php
│
├── database.sql
├── index.php
└── README.md
```

---

## Features

### Book Management

- Create, update, and delete books
- View a list of all books
- Assign books to categories
- Filter books by category
- Search books by title, author, or publisher
- Filter books by publication date

### Category Management

- Create, update, and delete book categories
- View a list of all categories

### Interactive Features

- **Live Search (AJAX)** – Search results are updated dynamically as users type, without reloading the page or pressing Enter.
- **Pagination** – Display book records across multiple pages for better performance and usability.
- **Responsive UI** – Built with Tailwind CSS for a clean and responsive user interface.

### 💾 Database

- MySQL relational database
- UUID as the primary key
- Foreign key constraints to maintain data integrity
- Automatic `created_at` and `updated_at` timestamps

## Highlights

- Native PHP (No Framework)
- PDO for secure database operations
- Tailwind CSS for responsive styling
- AJAX-powered live search
- Pagination for efficient data browsing
- Relational database design with UUID and foreign keys
- Clean project structure separating frontend and backend logic

---

## Installation

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

## Database Schema

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

## Security

* PDO Prepared Statements
* Input Validation
* Foreign Key Constraints
* UUID-based Primary Keys

---

## Future Improvements

* Authentication & Authorization
* Search and filter books
* Pagination
* Image upload for book covers
* Export data to PDF or Excel
* REST API implementation
* Dashboard statistics
* Responsive mobile optimization

---

## Developed For

**Azura Labs – Technical Assessment**
