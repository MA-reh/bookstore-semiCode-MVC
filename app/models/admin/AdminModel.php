<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class AdminModel extends Model
{
    public static function banUser()
    {
        $DB = Database::getConnection();

        $userId = Request::input("userId");

        $stmt = $DB->query("SELECT * FROM users WHERE id = '{$userId}';");

        $user = $stmt->fetch();

        $stmt = $DB->prepare("UPDATE users 
                                SET 
                                is_banned = :newBan
                                WHERE id = :userId;
                                ");

        $user["is_banned"] = !$user["is_banned"];

        $stmt->execute([
            "newBan" => $user["is_banned"],
            "userId" => $userId,
        ]);

        unset($user["password"]);

        return $user;
    }

    public static function mangeOrder(string $status)
    {
        $DB = Database::getConnection();

        $status = ($status == "cancel") ? "canceled" : "done";
        $orderId = Request::input("orderId");

        $stmt = $DB->prepare("UPDATE orders 
                                SET 
                                    status = :status, 
                                    cancel_reason = :cancel_reason
                                WHERE id = :orderId;
                                ");

        $stmt->execute([
            "status" => $status,
            "orderId" => $orderId,
            "cancel_reason" => Request::input("cancelReason"),
        ]);

        $stmt = $DB->prepare("SELECT orders.*, users.name AS customer_name
                                FROM orders
                                LEFT JOIN users ON users.id = orders.customer_id
                                WHERE orders.id = :orderId;
                                ");
        $stmt->execute([
            "orderId" => $orderId,
        ]);


        return [
            "order" => $stmt->fetch(),
            "status" => $status,
        ];
    }
}
