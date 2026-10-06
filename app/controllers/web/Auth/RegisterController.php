<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/User/UserModel.php";

class RegisterController extends Controller
{
    public function index()
    {
        $this->view("auth/register");
    }

    public function register()
    {
        $errors = Request::validate([
            "role" => [["required", "admin,customer"]],
            "name" => ["required", "name", ["min", 3]],
            "email" => ["required", "email", ["unique", "users"]],
            "password" => ["required", ["min", 8]],
            "phone" => ["required", "egPhone", ["unique", "users"]],
            "image" => [["image", "jpg,jpeg,png,webp,gif"]],
            "gender" => [["required", "male,female"]],
        ]);

        if (!empty($errors)) {
            back();
        }

        if (Request::input("role") == "admin" && !isAuth("admin")) {
            back("invalid", "You Must To Login As Admin");
        }

        UserModel::createUser();

        $newRole = Request::input("role");

        back("correct", "New {$newRole} Created Successfully");
    }
}
