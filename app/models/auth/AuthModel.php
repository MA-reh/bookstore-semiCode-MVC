<?php

require_once __DIR__ . "/../Model.php";

class AuthModel extends Model
{
    public static function login()
    {
        $DB = Database::getConnection();

        $email = Request::input("email");
        $password = Request::input("password");

        $stmt = $DB->query("SELECT * FROM users WHERE email = '{$email}';");

        $result = $stmt->fetch();

        if (!empty($result) && password_verify($password, $result["password"])) {
            unset($result["password"]);
            $_SESSION["user"] = $result;
            return true;
        }

        return false;
    }
}
