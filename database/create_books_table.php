<?php

require_once __DIR__ . "/../core/Database.php";


class create_books_table
{
    public static function up()
    {
        Database::getConnection()->exec("CREATE TABLE IF NOT EXISTS books
        (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                author_id BIGINT UNSIGNED NOT NULL,
                CONSTRAINT fk_author_id FOREIGN KEY (author_id) REFERENCES authors(id),
                title VARCHAR(255) NOT NULL,
                image VARCHAR(255) NULL,
                description TEXT, 
                price DECIMAL(10,2), 
                stock INT UNSIGNED DEFAULT 0 , 
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");
    }

    public static function down()
    {
        Database::getConnection()->exec("DROP TABLE IF EXISTS books;");
    }
}
