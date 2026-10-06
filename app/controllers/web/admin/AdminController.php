<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/DB/DBModel.php";
require_once __DIR__ . "/../../../models/admin/AdminModel.php";


class AdminController extends Controller
{
    public function banUser()
    {

        $errors = Request::validate([
            "userId" => ["required", ["exists", "users", "id"]]
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Changed", 422);
        }

        $idBanUser = Request::input("userId");
        $roleOfBanUser = DBModel::getRoleOfUser($idBanUser);


        if ($roleOfBanUser == "admin" && auth("id") > $idBanUser) {
            Response::json($errors, "You Can't Banned This User ", 403);
        }

        $user = AdminModel::banUser();

        Response::json($user, "This Action Has");
    }
    public function mangeOrder()
    {
        $errors = Request::validate([
            "status" => [["required", "done,cancel"]],
            "orderId" => ["required", "number", ["exists", "orders", "id"]],
            "userId" => ["required", "number", ["exists", "users", "id"]],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Changed", 422);
        }

        $status = Request::input("status");
        
        $actionMsg = ($status == "done") ? "This Order Has Been Done" : "the Order Has Been Canceled";

        $data = AdminModel::mangeOrder($status);

        Response::json($data, $actionMsg);
    }
}
