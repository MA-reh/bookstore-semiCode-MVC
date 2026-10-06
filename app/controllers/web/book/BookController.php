<?php

require_once __DIR__ . "/../../Controller.php";
require_once __DIR__ . "/../../../models/DB/DBModel.php";
require_once __DIR__ . "/../../../models/author/AuthorModel.php";

class BookController extends Controller
{
    public static function filterBooks()
    {
        header('Content-Type: application/json; charset=UTF-8');

        $errors = Request::validate([
            "page" => [["minNumber", 1]],
            "minPrice" => ["number", ["minNumber", 0]],
            "maxPrice" => ["number", ["minNumber", 0]],
            "stock" => ["number", ["minNumber", 0]],
            "sorting" => [["in", "DESC,ASC"]],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Must be Right Don't Change it", 422);
        }

        /* 
        page: 1
        authorName: ""
        titleBook: ""
        stock: ""
        maxPrice: ""
        minPrice: ""
        sorting: "DESC"
        
        */


        $minPrice = (Request::input("minPrice", "") == "") ? 0 : Request::input("minPrice");
        $maxPrice = (Request::input("maxPrice", "") == "") ? null : Request::input("maxPrice");
        $stock = Request::input("stock");

        $wheres = [
            ["authors.name", 'LIKE', "%" . Request::input("authorName", "") . "%"],
            ["books.title", 'LIKE', "%" . Request::input("titleBook", "") . "%"],
            ["books.price", '>=', $minPrice],
        ];

        if ($maxPrice != null) {
            array_push($wheres, ["books.price", '<=', $maxPrice]);
        }

        if ($stock != "") {
            array_push($wheres, ["books.stock", '>=', $stock]);
        } else {
            array_push($wheres, ["books.stock", '>=', 0]);
        }


        $books = BookModel::getDataOfBooks($wheres, Request::input("sorting"), Request::input("page", 1));

        $maxNumberOfPage = (isset($books['total']) && $books['total'] > 0) ? ceil($books['total'] / NUMBER_OF_CARDS) : 1;

        $errors = Request::validate([
            "page" => [["maxNumber", $maxNumberOfPage]],
        ]);

        if (!empty($errors)) {
            Response::json($errors, "Data Must be Right, Don't Change it", 422);
        }

        Response::json($books);
    }

    public static function addBook()
    {
        $errors = Request::validate([
            "authorId" => ["required", "number", ["exists", "authors", "id"]],
            "bookTitle" => ["required"],
            "bookImage" => [["image", "jpg,jpeg,png,webp,gif"]],
            "bookDescription" => ["required"],
            "bookPrice" => ["required", "number"],
            "bookStock" => ["required", "number"],
        ]);


        if (!empty($errors)) {
            Response::json($errors, "Data Invalid", 422);
        }


        $newBook = BookModel::addBook();

        Response::json($newBook, "Book Has Been Add Successfully");
    }
}
