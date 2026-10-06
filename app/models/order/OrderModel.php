<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class OrderModel extends Model
{
    public static function getDataOfOrders(array $wheres = [], $page = 1): array
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $numberOfCards = NUMBER_OF_CARDS;

        $offset = ($page * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $stmt = $DB->query("SELECT orders.*, users.name AS customer_name FROM 
                            orders
                            LEFT JOIN users ON users.id = orders.customer_id
                            {$wheresQuery} 
                            GROUP BY id DESC
                            LIMIT {$numberOfCards} OFFSET {$offset};");

        $data = $stmt->fetchAll();

        $stmt = $DB->query("SELECT COUNT(*) AS total FROM orders {$wheresQuery} ;");
        $total = $stmt->fetch()["total"];

        return [
            "data" => $data,
            "total" => $total,
        ];
    }
}
