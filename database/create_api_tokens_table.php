<?php

require_once __DIR__ . "/../core/Database.php";


class create_api_tokens_table
{
    public static function up()
    {
        Database::getConnection()->exec("CREATE TABLE IF NOT EXISTS api_tokens
        (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id),
                token VARCHAR(255) UNIQUE,
                expires_at TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );");
    }

    public static function down()
    {
        Database::getConnection()->exec("DROP TABLE IF EXISTS api_tokens;");
    }
}
