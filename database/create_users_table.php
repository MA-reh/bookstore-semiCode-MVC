<?php

require_once __DIR__ . "/../core/Database.php";


class create_users_table
{
    public static function up()
    {
        Database::getConnection()->exec("CREATE TABLE IF NOT EXISTS users
        (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL, 
                phone VARCHAR(255) NOT NULL UNIQUE,
                is_banded BOOLEAN DEFAULT false,
                image VARCHAR(255) NULL, 
                gender ENUM('male', 'female') NOT NULL,
                role ENUM('admin', 'customer') NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");
    }

    public static function down()
    {
        Database::getConnection()->exec("DROP TABLE IF EXISTS users;");
    }
}
