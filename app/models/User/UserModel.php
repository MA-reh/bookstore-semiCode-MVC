<?php

require_once __DIR__ . "/../Model.php";
require_once __DIR__ . "/../../../core/Database.php";

class UserModel extends Model
{

    public static function createUser(): void
    {
        $DB = Database::getConnection();

        $data = Request::all();

        $passwordHashed = password_hash($data['password'], PASSWORD_DEFAULT);

        $imageName = self::uploadImage("image");

        $DB->exec("INSERT INTO users 
        (role, name, email, password, phone, image, gender) 
        VALUES 
        ('{$data['role']}', '{$data['name']}', '{$data['email']}', '{$passwordHashed}', '{$data['phone']}', '{$imageName}', '{$data['gender']}');");
    }

    private static function uploadImage(string $fileName): ?string
    {
        if (Request::hasFile($fileName)) {
            $file = Request::file($fileName);
            $fileName = $file["name"];
            $fileOriginalName = pathinfo($fileName, PATHINFO_FILENAME);
            $fileOriginalExtinction = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $tmpFile = $file["tmp_name"];


            $allowedExtensions = ["jpg", "jpeg", "png", "webp", "gif"];

            if (!in_array($fileOriginalExtinction, $allowedExtensions)) {
                Response::json($allowedExtensions, "File extension Not Allowed", 422);
            }

            $newFileName = $fileOriginalName . "." . time() . "." . $fileOriginalExtinction;

            $uploadDir = __DIR__ . "/../../../public/assets/images/uploads";

            if (is_dir($uploadDir)) {
                mkdir($uploadDir);
            }

            $uploadPath = $uploadDir . "/" . $newFileName;

            if (!move_uploaded_file($tmpFile, $uploadPath)) {
                Response::json([], "File Not be Uploaded", 403);
            }

            return $newFileName;
        } else {
            return null;
        }
    }

    public static function editUser(string $column, string $value)
    {
        $DB = Database::getConnection();

        $userId = auth("id");

        if ($column == "password") {
            $value = password_hash($value, PASSWORD_DEFAULT);
        }

        $DB->exec("UPDATE users
                    SET 
                    {$column} = '{$value}' 
                    WHERE id = '{$userId}';");
    }
}
