<?php

class Request
{
    public static function all(): array
    {
        $json = json_decode(file_get_contents("php://input", true));

        $allData = array_merge(
            $_GET,
            $_POST,
            (is_array($json)) ? $json : []
        );

        unset($allData["url"]);

        return $allData;
    }

    public static function hasFile(string $fileName)
    {
        return isset($_FILES[$fileName]) && $_FILES[$fileName]["tmp_name"] != "";
    }

    public static function file(string $fileName)
    {
        return $_FILES[$fileName];
    }

    public static function input(string $key, mixed $default = null): mixed
    {
        return self::all()[$key] ?? $default;
    }

    public static function validate(array $rules): array
    {
        $validator = new Validation(self::all(), $rules);

        $errors =  $validator->validate();

        $_SESSION["_errors"] = $errors;
        $_SESSION["_old"] = self::all();

        return $errors;
    }
}
