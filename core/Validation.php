<?php

require_once __DIR__ . "/Database.php";

class Validation
{
    private array $data;
    /* 
    EX : 
    [
        "email" => "ma@gmail.com",
        "password" => "12345678"
    ]
    */
    private array $rules;
    /* 
    EX : 
    [
        "email" => ["required" , "email", ['unique']],
        "password" => ["required" , ["min", 8]],
    ]
    */

    private array $errors = [];
    /* 
    EX : 
    [
        "email" => [
            "email is required",
            "email must to be Valid Email",
        ]
    ]
    */

    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate(): array
    {

        foreach ($this->rules as $field => $rules) {
            $value = $this->data[$field] ?? null;

            foreach ($rules as $rule) {
                if (is_string($rule)) {
                    if ($rule === "required") {
                        // EX: "required"
                        $this->validateRequired($field, $value);
                    } else if ($rule === "name") {
                        // EX: "email"
                        $this->validateName($field, $value);
                    } else if ($rule === "email") {
                        // EX: "email"
                        $this->validateEmail($field, $value);
                    } else if ($rule === "egPhone") {
                        // EX: "egPhone"
                        $this->validateEGPhone($field, $value);
                    } else if ($rule === "number") {
                        $this->validateNumber($field, $value);
                    }
                } else if (is_array($rule)) {
                    if ($rule[0] == "min") {
                        // EX: ["min", 8]
                        $this->validateMinLength($field, $value, (isset($rule[1])) ? $rule[1] : 8);
                    } else if ($rule[0] === "unique") {
                        // EX: ["unique", "users", "tableName" , auth('id')]
                        $this->validateUnique($field, $value, $rule[1], (isset($rule[2])) ? $rule[2] : null);
                    } else if ($rule[0] === "exists") {
                        // EX: ["exists", "users", "email"]
                        $this->validateExists($field, $value, $rule[1], $rule[2]);
                    } else if ($rule[0] === "in") {
                        // EX: ["in", "admin,customer"] *! Check Values Only
                        $this->validateCheckValues($field, $value, $rule[1]);
                    } else if ($rule[0] === "required") {
                        // EX: ["required", "admin,customer"] *! this For Should Not be empty and Check Values
                        $this->validateRequired($field, $value, $rule[1]);
                    } else if ($rule[0] === "image") {
                        // EX: ["image", "jpg,jpeg,png,webp,gif"]
                        $this->validateImage($field, $value, $rule[1]);
                    } else if ($rule[0] === "minNumber") {
                        // EX: ["minNumber", 1]
                        $this->validateMinimumNumber($field, $value, $rule[1]);
                    } else if ($rule[0] === "maxNumber") {
                        // EX: ["maxNumber", 99]
                        $this->validateMaximumNumber($field, $value, $rule[1]);
                    }
                }
            }
        }

        return $this->errors;
    }
    private function addError(string $field, string $msg): void
    {
        $this->errors[$field][] = $msg;
    }
    private function validateRequired(string $field, mixed $value, string $rolesToCheck = ""): void
    {
        if ($value === null || trim((string)$value) === "") {
            $this->addError($field, "{$field} is Required.");
        } else if ($rolesToCheck !== "") {
            $this->validateCheckValues($field, $value, $rolesToCheck);
        }
    }
    private function validateName(string $field, mixed $value)
    {
        if (empty($value)) {
            return;
        }

        $regex = "/^[A-Za-z]{3,15}( [A-Za-z]{3,15})*$/";

        if (!preg_match($regex, $value)) {
            $this->addError($field, "{$field} Write Name With Letters Only.");
        }
    }
    private function validateEmail(string $field, mixed $value): void
    {
        if (empty($value)) {
            return;
        }

        $regex = "/^[A-Za-z_][A-Za-z_0-9\.\-]+@(gmail.com|yahoo.org)$/";

        if (!preg_match($regex, $value)) {
            $this->addError($field, "{$field} Must to be Valid email.");
        }
    }
    private function validateNumber(string $field, mixed $value): void
    {
        if (empty($value)) {
            return;
        }

        $regex = "/^[0-9]+$/";

        if (!preg_match($regex, $value)) {
            $this->addError($field, "{$field} this Field Must be Number.");
        }
    }
    private function validateMinLength(string $field, mixed $value, int $min = 8): void
    {
        if (empty($value)) {
            return;
        }

        if (strlen($value) < $min) {
            $this->addError($field, "{$field} Must be at lest {$min} letters.");
        }
    }
    private function validateMinimumNumber(string $field, mixed $value, int $min = 1): void
    {
        if (empty($value)) {
            return;
        }

        if ($value < $min) {
            $this->addError($field, "{$field} Must be upper or equal this Number: {$min}.");
        }
    }
    private function validateMaximumNumber(string $field, mixed $value, int $max): void
    {
        if (empty($value)) {
            return;
        }

        if ($value > $max) {
            $this->addError($field, "{$field} Must be lower or equal this Number: {$max}.");
        }
    }
    private function validateEGPhone(string $field, mixed $value): void
    {
        if (empty($value)) {
            return;
        }

        $regex = "/^(02)?01(0|1|2|5)[0-9]{8}$/";

        if (!preg_match($regex, $value)) {
            $this->addError($field, "{$field} is must be to Egyptian Phone.");
        }
    }
    private function validateUnique(string $field, mixed $value, string $tableName, ?int $exceptId = null): void
    {

        $DB = Database::getConnection();

        $subQuery = "";
        if ($exceptId != null) {
            $subQuery = "AND id != '{$exceptId}'";
        }

        $stmt = $DB->query("SELECT * FROM {$tableName} WHERE {$field} = '{$value}' {$subQuery}");

        $result = $stmt->fetchAll();

        if (!empty($result)) {
            $this->addError($field, "{$field} is already Exists");
        }
    }
    private function validateExists(string $field, mixed $value, string $tableName, string $columnName): void
    {
        $DB = Database::getConnection();

        $stmt = $DB->query("SELECT * FROM {$tableName} WHERE {$columnName} = '{$value}'");

        $result = $stmt->fetchAll();

        if (empty($result)) {
            $this->addError($field, "{$field} is Not Exists");
        }
    }
    private function validateCheckValues(string $field, mixed $value, string $rolesToCheck): void
    {
        $rolesToCheckArr = explode(",", $rolesToCheck);

        if (!in_array($value, $rolesToCheckArr)) {
            $this->addError($field, "{$field} is Not Founded");
        }
    }
    private function validateImage(string $field, mixed $value, ?string $extensionsForImage): void
    {
        if ($value === null || trim((string)$value) === "") {
            return;
        }

        if (!$extensionsForImage) {
            $extensionsForImage = "jpg,jpeg,png,webp,gif";
        }

        if (Request::hasFile($field)) {
            $file = Request::file($field);
            $fileName = $file["name"];
            $fileOriginalExtinction = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $extensionsForImageArr = explode(",", $extensionsForImage);

            if (!in_array($$fileOriginalExtinction, $extensionsForImageArr)) {
                $this->addError($field, "{$field} Extension is Not Allowed!");
            }
        } else {
            $this->addError($field, "{$field} Can't access This File");
        }
    }
}
