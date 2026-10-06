<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class AuthorModel extends Model
{
    public static function addAuthor()
    {
        $DB = Database::getConnection();
        $stmt = $DB->prepare("INSERT INTO authors
                        (name, bio)
                        VALUES
                        (:name, :bio)");

        $stmt->execute([
            "name" => Request::input("authorName"),
            "bio" => Request::input("authorBio"),
        ]);

        $authorId = $DB->lastInsertId();

        $stmt = $DB->query("SELECT * FROM authors WHERE id = '{$authorId}';");

        return $stmt->fetch();
    }

    public static function editAuthor()
    {
        $DB = Database::getConnection();

        $authorId = trim(Request::input("authorId"));
        $authorName = trim(Request::input("authorName"));
        $authorBio = trim(Request::input("authorBio"));

        $DB->exec("UPDATE authors
                    SET 
                        id = '{$authorId}',
                        name = '{$authorName}',
                        bio = '{$authorBio}'
                    WHERE id = '{$authorId}';
                    ");

        $stmt = $DB->query("SELECT * FROM authors WHERE id = '{$authorId}';");

        return $stmt->fetch();
    }
}
