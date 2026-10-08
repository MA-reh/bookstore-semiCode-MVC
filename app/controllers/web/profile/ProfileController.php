<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/User/UserModel.php";
require_once __DIR__ . "/../../../models/DB/DBModel.php";
require_once __DIR__ . "/../../../models/book/BookModel.php";
require_once __DIR__ . "/../../../models/order/OrderModel.php";
require_once __DIR__ . "/../../../models/search/SearchModel.php";
require_once __DIR__ . "/../../../models/cart/CartModel.php";

class ProfileController extends Controller
{
    public function index()
    {
        $data = [];
        if (isAuth("admin")) {
            $data = $this->getAdminData();
        } else if (isAuth("customer")) {
            $data = $this->getCustomerData();
        }

        $this->view("profile/profile", $data);
    }

    private function getAdminData(string $category = "all"): array
    {
        $pageNumber = Request::input("page", 1);

        if ($category == "admins" || $category == "customers") {
            $categoryItem = rtrim($category, "s");

            $whereAdmin = ($categoryItem == "admin") ? ["id", "!=", auth("id")] : null;

            $wheresUsers = [["role", "=", $categoryItem]];

            if ($whereAdmin !== null) {
                array_push($wheresUsers, $whereAdmin);
            }

            $total = DBModel::getTotalOfUsers($wheresUsers);

            $data = [
                $category => DBModel::getDataOfUsers($wheresUsers, page: $pageNumber),
            ];
        } else if ($category == "authors") {
            $total = DBModel::getTotalOfAuthors();

            $data = [
                "authors" => DBModel::getDataOfAuthors(page: $pageNumber),
            ];
        } else if ($category == "books") {
            $total = DBModel::getTotalOfBooks();

            $data = [
                "books" => BookModel::getDataOfBooks(page: $pageNumber),
            ];
        } else if ($category == "canceled" || $category == "done" || $category == "ordered") {
            $total = DBModel::getTotalOfOrders([["status", "=", $category]]);

            $data = [
                $category => OrderModel::getDataOfOrders([["status", "=", $category]], page: $pageNumber),
            ];
        } else {
            // To Create Data By PHP In First Time

            $total = [
                "admins" => DBModel::getTotalOfUsers([["role", "=", "admin"]]),
                "customers" => DBModel::getTotalOfUsers([["role", "=", "customer"]]),
                "authors" => DBModel::getTotalOfAuthors(),
                "books" => DBModel::getTotalOfBooks(),
                "orders" => [
                    "ordered" => DBModel::getTotalOfOrders([["status", "=", "ordered"]]),
                    "canceled" => DBModel::getTotalOfOrders([["status", "=", "canceled"]]),
                    "done" => DBModel::getTotalOfOrders([["status", "=", "done"]]),
                ],
            ];

            $data = [
                "admins" => DBModel::getDataOfUsers([["role", "=", "admin"], ["id", "!=", auth("id")]], page: $pageNumber),
                "customers" => DBModel::getDataOfUsers([["role", "=", "customer"]], page: $pageNumber),
                "authors" => DBModel::getDataOfAuthors(page: $pageNumber),
                "books" => BookModel::getDataOfBooks(page: $pageNumber),
                "orders" => [
                    "ordered" => OrderModel::getDataOfOrders([["status", "=", "ordered"]], page: $pageNumber),
                    "canceled" => OrderModel::getDataOfOrders([["status", "=", "canceled"]], page: $pageNumber),
                    "done" => OrderModel::getDataOfOrders([["status", "=", "done"]], page: $pageNumber),
                ],
            ];
        }

        return [
            "total" => $total,
            "data" => $data,
        ];
    }

    private function getCustomerData(string $category = "all"): array
    {
        $pageNumber = Request::input("page", 1);

        // All if conditions to Get Data for Pagination  
        if ($category == "admins" || $category == "customers") {
            $categoryItem = rtrim($category, "s");

            $whereAdmin = ($categoryItem == "admin") ? ["id", "!=", auth("id")] : null;

            $wheresUsers = [["role", "=", $categoryItem]];

            if ($whereAdmin !== null) {
                array_push($wheresUsers, $whereAdmin);
            }

            $total = DBModel::getTotalOfUsers($wheresUsers);

            $data = [
                $category => DBModel::getDataOfUsers($wheresUsers, page: $pageNumber),
            ];
        } else if ($category == "authors") {
            $total = DBModel::getTotalOfAuthors();

            $data = [
                "authors" => DBModel::getDataOfAuthors(page: $pageNumber),
            ];
        } else if ($category == "books") {
            $total = DBModel::getTotalOfBooks();

            $data = [
                "books" => BookModel::getDataOfBooks([["stock", ">=", "0"]], page: $pageNumber),
            ];
        } else if ($category == "canceled" || $category == "done" || $category == "ordered") {
            $total = DBModel::getTotalOfOrders([
                ["status", "=", $category],
                ["customer_id", "=", auth("id")],
            ]);

            $data = [
                $category => OrderModel::getDataOfOrders([
                    ["status", "=", $category],
                    ["customer_id", "=", auth("id")],
                ], page: $pageNumber),
            ];
        } else {
            // To Create Data By PHP In First Time

            $total = [
                "books" => DBModel::getTotalOfBooks(),
                "boughtBooks" => DBModel::getTotalBoughtBooksOfCustomer(),
                "totalItemsIntoCart" => CartModel::totalItemsIntoOrders(),
                "orders" => [
                    "ordered" => DBModel::getTotalOfOrders([
                        ["status", "=", "ordered"],
                        ["customer_id", "=", auth("id")],
                    ]),
                    "canceled" => DBModel::getTotalOfOrders([
                        ["status", "=", "canceled"],
                        ["customer_id", "=", auth("id")],
                    ]),
                    "done" => DBModel::getTotalOfOrders([
                        ["status", "=", "done"],
                        ["customer_id", "=", auth("id")],
                    ]),
                ],
            ];

            $data = [
                "books" => BookModel::getDataOfBooks([["stock", ">=", "0"]], page: $pageNumber),
                "orders" => [
                    "ordered" => OrderModel::getDataOfOrders([
                        ["customer_id", "=", auth("id")],
                        ["status", "=", "ordered"]
                    ], page: $pageNumber),
                    "canceled" => OrderModel::getDataOfOrders([
                        ["customer_id", "=", auth("id")],
                        ["status", "=", "canceled"]
                    ], page: $pageNumber),
                    "done" => OrderModel::getDataOfOrders([
                        ["customer_id", "=", auth("id")],
                        ["status", "=", "done"]
                    ], page: $pageNumber),
                ],
            ];
        }

        return [
            "total" => $total,
            "data" => $data,
        ];
    }
    public function paginationData()
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        $errors = Request::validate([
            "page" => ["required"]
        ]);

        if (!empty($errors)) {
            Response::error("Data Changed", 422);
        }


        $categoryName = Request::input("categoryName");

        $dataOfCategory = [];
        if (isAuth("admin")) {
            $dataOfCategory = $this->getAdminData($categoryName);
        } else if (isAuth("customer")) {
            $dataOfCategory = $this->getCustomerData($categoryName);
        }

        $maximumNumber = ceil((float)$dataOfCategory["total"] / NUMBER_OF_CARDS);

        $errors = Request::validate([
            "page" => [
                ["minNumber", 1],
                ["maxNumber", $maximumNumber], // Because if Number Of Pages He Sended Upper the Normal
            ]
        ]);

        if (!empty($errors)) {
            Response::error("Data Changed", 422);
        }

        Response::json($dataOfCategory, "Successfully");
    }
}
