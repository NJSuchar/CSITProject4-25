CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,          -- Unique ID for each book
    title VARCHAR(255) NOT NULL,                -- Title of the book
    author VARCHAR(255) NOT NULL,               -- Author of the book
    isbn VARCHAR(20) UNIQUE NOT NULL,           -- ISBN number
    publisher VARCHAR(255),                     -- Publisher of the book
    publish_date DATE,                          -- Date the book was published
    genre VARCHAR(100),                         -- Genre or category of the book
    page_count INT,                             -- Number of pages in the book
    language VARCHAR(50),                       -- Language of the book
    is_available BOOLEAN DEFAULT TRUE          -- Indicates if the book is available
);
