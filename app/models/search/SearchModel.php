<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class SearchModel extends Model
{
    public static function getData(string $nameTable, string $search = "", int|string $pageNumber = 1)
    {
        $DB = Database::getConnection();

        $offset = ($pageNumber * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $numberOfData = NUMBER_OF_CARDS;

        $subQuery = "";

        if (!empty($search) && false) {
            $subQuery = "WHERE
                            concat(first_name, ' ', last_name) LIKE '%$search%'
                            OR
                            email LIKE '%$search%'
                            OR
                            age LIKE '%$search%'
                            OR
                            phone LIKE '%$search%'";
        }

        $stmt = $DB->query("SELECT * 
                            FROM {$nameTable}
                            {$subQuery}
                            ORDER BY id DESC
                            LIMIT {$numberOfData} offset {$offset}
                            ");

        return $stmt->fetchAll();
    }
}
