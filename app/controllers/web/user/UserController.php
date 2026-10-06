<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/User/UserModel.php";
require_once __DIR__ . "/../../../models/DB/DBModel.php";

class UserController extends Controller
{
    public function editUserData()
    {
        $inputName = Request::input("inputName");

        $validateArr = [
            $inputName => ["required"],
        ];

        if ($inputName == "name") {
            $validateArr = [$inputName => ['required', "name", ["min", 3]]];
        } else if ($inputName == "email") {
            $validateArr = [$inputName => ['required', "email", ["unique", "users", auth('id')]]];
        } else if ($inputName == "phone") {
            $validateArr = [$inputName => ['required', "egPhone",]];
        } else if ($inputName == "gender") {
            $validateArr = [$inputName => [['required', "male,female"]]];
        } else if ($inputName == "password") {
            $validateArr = [$inputName => ['required', ["min", 8]]];
        }

        $errors = Request::validate($validateArr);

        if (!empty($errors)) {
            Response::json($errors, status: 422);
        }

        $inputValue = Request::input($inputName);

        UserModel::editUser($inputName, $inputValue);

        auth($inputName, $inputValue);

        Response::json([$inputName => $inputValue], "Update Your {$inputName} Has been Successfully", status: 200);
    }
}
