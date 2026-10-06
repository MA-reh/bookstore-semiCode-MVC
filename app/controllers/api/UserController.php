<?php

require_once __DIR__ . "/../../models/User/UserModel.php";

class UserController
{

    public function getData()
    {
        $users = [
            ["id" => 1, "name" => "Mohamed Ayman"],
            ["id" => 2, "name" => "Mohamed Ahmed"],
            ["id" => 3, "name" => "Atya Ayman"],
            ["id" => 4, "name" => "Aya Ayman"],
        ];

        $userModel = new UserModel();

        echo json_encode([
            "message" => "Successfully",
            "data" => $users,
        ]);

        // Response::json(Request::all(), "Successfully");
    }
}
