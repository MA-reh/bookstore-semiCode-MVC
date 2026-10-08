<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class CartModel extends Model
{
    private static function getPendingOrdersId(): false | int
    {
        $DB = Database::getConnection();

        $authId = auth("id");

        $stmt = $DB->query("SELECT id 
                            FROM orders 
                            WHERE 
                                customer_id = '{$authId}'
                            AND 
                                status = 'pending';");

        return $stmt->fetchColumn();
    }
    private static function getOrdersItemId(string $orderId, string $bookId): false | int
    {
        $DB = Database::getConnection();

        $stmt = $DB->query("SELECT id 
                            FROM orders_items 
                            WHERE 
                                order_id = '{$orderId}'
                            AND 
                                book_id = '{$bookId}'
                                ;");

        return $stmt->fetchColumn();
    }
    private static function updateTotalPriceOfOrders()
    {
        $DB = Database::getConnection();

        $orderId = self::getPendingOrdersId();

        $totalPrice = $DB->query("SELECT SUM(subtotal) AS total FROM orders_items WHERE order_id = '{$orderId}';")->fetchColumn() ?? 0;

        $DB->exec("UPDATE orders SET total_price = {$totalPrice} WHERE id = '{$orderId}';");

        return $totalPrice;
    }
    public static function totalItemsIntoOrders()
    {
        $DB = Database::getConnection();

        $orderId = self::getPendingOrdersId();

        if ($orderId == false) return 0;

        return $DB->query("SELECT COUNT(*) AS total FROM orders_items WHERE order_id = '{$orderId}';")->fetchColumn();
    }
    public static function addToCart()
    {
        $DB = Database::getConnection();
        $pendingOrderId = self::getPendingOrdersId();
        $authId = auth("id");

        if ($pendingOrderId == false) {
            // Create New Order
            $DB->exec("INSERT INTO orders (customer_id) VALUES ('{$authId}');");

            $pendingOrderId = $DB->lastInsertId();
        }

        $bookId = Request::input("bookId");
        $quantity = Request::input("quantity");

        $orderItemId = self::getOrdersItemId($pendingOrderId, $bookId);

        $unitPrice = $DB->query("SELECT price FROM books WHERE id = '{$bookId}';")->fetchColumn();
        if ($orderItemId == false) {
            $stmt = $DB->prepare("INSERT INTO orders_items 
                                    (order_id, book_id, quantity, unit_price, subtotal)
                                    VALUES
                                    (:order_id, :book_id, :quantity, :unit_price, :subtotal)
                                    ;");

            $stmt->execute([
                "order_id" => $pendingOrderId,
                "book_id" => $bookId,
                "quantity" => $quantity,
                "unit_price" => $unitPrice,
                "subtotal" => (float)$unitPrice * (int)$quantity,
            ]);
        } else {

            $newSubTotal = $unitPrice * $quantity;

            $DB->exec("UPDATE orders_items 
                        SET 
                        quantity = quantity + {$quantity},
                        subtotal = subtotal + {$newSubTotal}
                        WHERE id = '{$orderItemId}'
                        ;");
        }

        self::updateTotalPriceOfOrders();
    }
    public static function getItemsIntoCart(?int $orderId = null)
    {
        $DB = Database::getConnection();

        if ($orderId == null) {
            $orderId = self::getPendingOrdersId();
        }

        $stmt = $DB->prepare("SELECT 
                        books.id AS book_id, 
                        authors.name AS authorName, 
                        books.title, 
                        books.image, 
                        books.description, 
                        books.price,
                        authors.id AS author_id,
                        orders.id AS order_id,
                        orders_items.id AS order_item_id,
                        orders_items.quantity, 
                        orders_items.subtotal, 
                        orders.total_price
                    FROM orders_items
                        LEFT JOIN orders ON orders.id = orders_items.order_id
                        LEFT JOIN books ON books.id = orders_items.book_id
                        LEFT JOIN authors ON authors.id = books.author_id
                    WHERE orders.id = :order_id;
                    ");

        $stmt->execute([
            "order_id" => $orderId,
        ]);

        return [
            "books" => $stmt->fetchAll(),
            "order_id" => $orderId,
            "statusFunction" => Request::input("statusFunction", "cart"),
        ];
    }
    public static function increaseOrderItem()
    {
        $DB = Database::getConnection();
        $stmt = $DB->prepare("UPDATE orders_items 
                                SET 
                                    quantity = quantity + 1,
                                    subtotal = subtotal + unit_price
                                WHERE id = :orderItemId;");
        $stmt->execute([
            "orderItemId" => Request::input("orderItemId"),
        ]);

        $stmt = $DB->prepare("SELECT * FROM orders_items WHERE id = :orderItemId");

        $stmt->execute([
            "orderItemId" => Request::input("orderItemId"),
        ]);

        $totalPrice = self::updateTotalPriceOfOrders();

        $orderItem = $stmt->fetch();

        return [
            "orderItem" => $orderItem,
            "totalPrice" => $totalPrice,
        ];
    }
    public static function decreaseOrderItem()
    {
        $DB = Database::getConnection();
        $stmt = $DB->prepare("UPDATE orders_items 
                                SET 
                                    quantity = quantity - 1,
                                    subtotal = subtotal - unit_price
                                WHERE id = :orderItemId;");
        $stmt->execute([
            "orderItemId" => Request::input("orderItemId"),
        ]);

        $totalPrice = self::updateTotalPriceOfOrders();

        $stmt = $DB->prepare("SELECT * FROM orders_items WHERE id = :orderItemId;");

        $stmt->execute([
            "orderItemId" => Request::input("orderItemId"),
        ]);

        $orderItem = $stmt->fetch();

        if ($orderItem["quantity"] == 0) {
            $stmt = $DB->prepare("DELETE FROM orders_items WHERE id = :orderItemId;");
            $stmt->execute([
                "orderItemId" => Request::input("orderItemId"),
            ]);
        }

        $totalItems = self::totalItemsIntoOrders();

        return [
            "orderItem" => $orderItem,
            "totalPrice" => $totalPrice,
            "totalItems" => $totalItems,
        ];
    }
    public static function deleteOrderItem()
    {
        $DB = Database::getConnection();

        $stmt = $DB->prepare("DELETE FROM orders_items WHERE id = :orderItemId;");
        $stmt->execute([
            "orderItemId" => Request::input("orderItemId"),
        ]);

        $totalPrice = self::updateTotalPriceOfOrders();
        $totalItems = self::totalItemsIntoOrders();

        return [
            "totalPrice" => $totalPrice,
            "totalItems" => $totalItems,
        ];
    }
    public static function orderCustomerOrder()
    {
        $DB = \Database::getConnection();

        $orderId = \Request::input("orderId");

        $stmt = $DB->prepare("UPDATE orders SET status = 'ordered' WHERE id = :orderId;");
        $stmt->execute([
            "orderId" => $orderId,
        ]);

        $stmt = $DB->prepare("SELECT orders_items.id,orders_items.order_id,orders_items.quantity,orders_items.book_id 
                                FROM orders_items 
                                LEFT JOIN orders ON orders.id = orders_items.order_id
                                LEFT JOIN books ON books.id = orders_items.book_id
                                WHERE orders_items.order_id = :orderId;");
        $stmt->execute([
            "orderId" => $orderId,
        ]);

        $data = $stmt->fetchAll();

        // Change Stock
        for ($i = 0; $i < count($data); $i++) {
            $stmt = $DB->prepare("UPDATE books SET stock = stock - :bookStock WHERE id = :bookId;");
            $stmt->execute([
                "bookId" => $data[$i]["book_id"],
                "bookStock" => $data[$i]["quantity"],

            ]);
        }

        // ======================================================= //

        $stmt = $DB->prepare("SELECT orders.*, users.name AS customer_name
                                FROM orders
                                LEFT JOIN users ON users.id = orders.customer_id
                                WHERE orders.id = :orderId;
                                ");
        $stmt->execute([
            "orderId" => $orderId,
        ]);

        $itemsOrder = self::getItemsIntoCart($orderId);

        return [
            "dataOrder" => $stmt->fetch(),
            "orderItem" => $itemsOrder,
        ];
    }
}
