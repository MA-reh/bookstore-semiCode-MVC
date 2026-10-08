<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class DBModel extends Model
{
    // Totals Of Data
    public static function getTotalOfTable(string $tableName, array $wheres = [])
    {
        $DB = Database::getConnection();
        /* 
        [column, operator,  `value],
        [column, operator, value, logicalOperator],
        */

        $wheresQuery = Model::prepareWhereArray($wheres);

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM {$tableName}
                            {$wheresQuery}
                            ;");

        $result = $stmt->fetch();

        return $result['total'];
    }
    public static function getTotalBoughtBooksOfCustomer()
    {
        $DB = Database::getConnection();

        $customerId = auth("id");

        $stmt = $DB->query("SELECT SUM(orders_items.quantity) AS total
                              FROM 
                                orders_items
                              LEFT JOIN 
                                orders ON orders.id = orders_items.order_id
                              WHERE
                                  orders.customer_id = {$customerId}
                                    AND
                                  orders.status = 'done';");

        return $stmt->fetchColumn() ?? 0;
    }

    public static function getTotalOfBooks(array $wheres = [])
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM books
                            {$wheresQuery}
                            ;");

        $result = $stmt->fetch();

        return $result['total'];
    }
    public static function getTotalOfUsers(array $wheres = [])
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM users
                            {$wheresQuery}
                            ;");

        $result = $stmt->fetch();

        return $result['total'];
    }
    public static function getTotalOfAuthors(array $wheres = [])
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM authors
                            {$wheresQuery}
                            ;");

        $result = $stmt->fetch();

        return $result['total'];
    }
    public static function getTotalOfOrders(array $wheres = [])
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $stmt = $DB->query("SELECT COUNT(*) AS total 
                            FROM orders
                            {$wheresQuery}
                            ;");

        $result = $stmt->fetch();

        return $result['total'];
    }

    // All Data In Data
    public static function getDataOfTable(string $tableName, array $wheres = [], $page = 1): array
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $numberOfCards = NUMBER_OF_CARDS;

        $offset = ($page * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $stmt = $DB->query("SELECT * FROM 
                            {$tableName}  
                            {$wheresQuery} 
                            ORDER BY id DESC
                            LIMIT {$numberOfCards} OFFSET {$offset};");

        $data = $stmt->fetchAll();

        $stmt = $DB->query("SELECT COUNT(*) AS total FROM {$tableName} {$wheresQuery} ;");
        $total = $stmt->fetch()["total"];

        if ($tableName == "users") {
            unset($data["password"]);
        }

        return [
            "data" => $data,
            "total" => $total,
        ];
    }
    public static function getDataOfUsers(array $wheres = [], $page = 1): array
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $numberOfCards = NUMBER_OF_CARDS;

        $offset = ($page * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $stmt = $DB->query("SELECT * 
                            FROM users  
                            {$wheresQuery} 
                            ORDER BY id DESC
                            LIMIT {$numberOfCards} OFFSET {$offset};");

        $data = $stmt->fetchAll();

        $stmt = $DB->query("SELECT COUNT(*) AS total FROM users {$wheresQuery} ;");
        $total = $stmt->fetch()["total"];

        unset($data["password"]);

        return [
            "data" => $data,
            "total" => $total,
        ];
    }
    public static function getDataOfAuthors(array $wheres = [], $page = 1): array
    {
        $DB = Database::getConnection();

        $wheresQuery = Model::prepareWhereArray($wheres);

        $numberOfCards = NUMBER_OF_CARDS;

        $offset = ($page * NUMBER_OF_CARDS) - NUMBER_OF_CARDS;

        $stmt = $DB->query("SELECT * 
                            FROM authors  
                            {$wheresQuery} 
                            ORDER BY id DESC
                            LIMIT {$numberOfCards} OFFSET {$offset};");

        $data = $stmt->fetchAll();

        $stmt = $DB->query("SELECT COUNT(*) AS total FROM authors {$wheresQuery} ;");
        $total = $stmt->fetch()["total"];

        return [
            "data" => $data,
            "total" => $total,
        ];
    }

    // Public
    public static function getRoleOfUser(int $userId)
    {
        $DB = Database::getConnection();

        $stmt = $DB->query("SELECT role FROM users WHERE id = '{$userId}';");

        return $stmt->fetchColumn();
    }
}
