DROP DATABASE IF EXISTS booklist_db;
CREATE DATABASE booklist_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE booklist_db;

CREATE TABLE categories (
  id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
  name VARCHAR(150) NOT NULL UNIQUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE books (
  id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
  title VARCHAR(255) NOT NULL,
  author VARCHAR(255) NOT NULL,
  publication_date DATE NOT NULL,
  publisher VARCHAR(255) NOT NULL,
  num_pages INT UNSIGNED NOT NULL,
  category_id CHAR(36) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_books_category
    FOREIGN KEY (category_id) REFERENCES categories(id)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (id, name) VALUES
  ('11111111-1111-1111-1111-111111111111', 'Fiction'),
  ('22222222-2222-2222-2222-222222222222', 'Non-Fiction'),
  ('33333333-3333-3333-3333-333333333333', 'Science'),
  ('44444444-4444-4444-4444-444444444444', 'Technology'),
  ('55555555-5555-5555-5555-555555555555', 'History');

INSERT INTO books (id, title, author, publication_date, publisher, num_pages, category_id) VALUES
  (UUID(), 'Clean Code', 'Robert C. Martin', '2008-08-01', 'Prentice Hall', 464, '44444444-4444-4444-4444-444444444444'),
  (UUID(), 'A Brief History of Time', 'Stephen Hawking', '1988-04-01', 'Bantam Books', 256, '33333333-3333-3333-3333-333333333333'),
  (UUID(), 'Sapiens', 'Yuval Noah Harari', '2011-01-01', 'Harvill Secker', 443, '22222222-2222-2222-2222-222222222222'),
  (UUID(), '1984', 'George Orwell', '1949-06-08', 'Secker & Warburg', 328, '11111111-1111-1111-1111-111111111111'),
  (UUID(), 'The Pragmatic Programmer', 'Andrew Hunt', '1999-10-20', 'Addison-Wesley', 352, '44444444-4444-4444-4444-444444444444');