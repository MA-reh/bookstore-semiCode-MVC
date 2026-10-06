<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/cart/CartModel.php";


class CustomerController extends Controller
{
    public function addBookQuantity()
    {
        $errors = Request::validate([
            "bookId" => ["required", ["exists", "books", "id"]],
            "quantity" => ["required", ["minNumber", 1]],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Changed", 422);
        }
        CartModel::addToCart();

        $totalItems = CartModel::totalItemsIntoOrders();

        Response::json([
            "totalItems" => $totalItems
        ], "this book Has Been Added in Your Cart.");
    }

    public function getItemsToAddIntoCart()
    {
        if (Request::input("orderId") != null) {
            $errors = Request::validate([
                "orderId" => ["required", ["exists", "orders", "id"]]
            ]);

            if (!empty($errors)) {
                Response::json($errors, "Data Invalid", 422);
            }
        }

        $errors = Request::validate([
            "statusFunction" => [["required", "cart,showOrder"]],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $data = CartModel::getItemsIntoCart(Request::input("orderId", null));

        Response::json($data);
    }

    public function changeOrderItem()
    {
        $errors = Request::validate([
            "typeOfAction" => [["required", "increase,decrease"]],
            "orderItemId" => ["required", ["exists", "orders_items", "id"]]
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $typeOfAction = Request::input("typeOfAction");

        if ($typeOfAction == "increase") {
            $data = CartModel::increaseOrderItem();
        } else {
            $data = CartModel::decreaseOrderItem();
        }

        Response::json($data);
    }

    public function deleteOrderItem()
    {
        $errors = Request::validate([
            "orderItemId" => ["required", ["exists", "orders_items", "id"]]
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $data = CartModel::deleteOrderItem();

        Response::json($data, "Book Has Been Deleted From Your Cart");
    }

    public function orderCustomerOrder()
    {
        $errors = Request::validate([
            "orderId" => ["required", ["exists", "orders", "id"]]
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $data = CartModel::orderCustomerOrder();

        Response::json($data, "Your Order has been Updated.");
    }
}
