<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/DB/DBModel.php";
require_once __DIR__ . "/../../../models/author/AuthorModel.php";

class AuthorController extends Controller
{
    public function addAuthor()
    {
        $errors = Request::validate([
            "authorName" => ["required", "name", ["min", 3]],
            "authorBio" => ["required"],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $newAuthor = AuthorModel::addAuthor();

        Response::json($newAuthor, "New Author Has Been Added Successfully");
    }

    public function editAuthor()
    {
        $errors = Request::validate([
            "authorId" => ["required", ["exists", "authors", "id"]],
            "authorName" => ["required", "name", ["min", 3]],
            "authorBio" => ["required"],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }

        $newDataAuthor = AuthorModel::editAuthor();

        Response::json($newDataAuthor, "Update Data Author Has Been Editing Successfully");
    }
}
