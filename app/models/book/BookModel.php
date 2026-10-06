<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class BookModel extends Model
{
    public static function getDataOfBooks(array $wheres = [], string $sortType = "DESC", $page = 1): array
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $numberOfCards = NUMBER_OF_CARDS;

        $offset = ($page * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $stmt = $DB->query("SELECT books.*, authors.name AS authorName FROM 
                            books 
                            left join authors ON authors.id = books.author_id
                            {$wheresQuery} 
                            GROUP BY id {$sortType}
                            LIMIT {$numberOfCards} OFFSET {$offset};");

        $data = $stmt->fetchAll();

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM books 
                            left join authors ON authors.id = books.author_id
                            {$wheresQuery} ;");

        $total = $stmt->fetch()["total"];

        return [
            "data" => $data,
            "total" => $total,
        ];
    }

    public static function addBook()
    {
        $DB = Database::getConnection();

        $stmt = $DB->prepare("INSERT INTO books 
                                (author_id, title, image, description, price, stock) 
                                VALUES 
                                (:author_id, :title, :image, :description, :price, :stock)
                                ;");

        $stmt->execute([
            "author_id" => Request::input("authorId"),
            "title" => Request::input("bookTitle"),
            "image" => self::uploadImage("bookImage"),
            "description" => Request::input("bookDescription"),
            "price" => Request::input("bookPrice"),
            "stock" => Request::input("bookStock"),
        ]);

        $newBookId = $DB->lastInsertId();

        $newBook = self::getDataOfBooks([["books.id", "=", $newBookId]])["data"][0];

        return $newBook;
    }

    private static function uploadImage(string $fileName): ?string
    {
        if (Request::hasFile($fileName)) {
            $file = Request::file($fileName);
            $fileName = $file["name"];
            $fileOriginalName = pathinfo($fileName, PATHINFO_FILENAME);
            $fileOriginalExtinction = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $tmpFile = $file["tmp_name"];


            $allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

            if (!in_array($fileOriginalExtinction, $allowedExtensions)) {
                Response::json($allowedExtensions, "File extension Not Allowed", 422);
            }

            $newFileName = $fileOriginalName . "." . time() . "." . $fileOriginalExtinction;

            $uploadDir = __DIR__ . "../../../public/assets/images/uploads";

            if (is_dir($uploadDir)) {
                mkdir($uploadDir);
            }

            $uploadPath = $uploadDir . "/" . $newFileName;

            if (!move_uploaded_file($tmpFile, $uploadPath)) {
                Response::json([], "File Not be Uploaded", 403);
            }

            return $newFileName;
        } else {
            return null;
        }
    }
}
