<?php

require_once __DIR__ . "/../core/Route.php";
require_once __DIR__ . "/../app/controllers/web/HomeController.php";
require_once __DIR__ . "/../app/controllers/web/Auth/LoginController.php";
require_once __DIR__ . "/../app/controllers/web/Auth/RegisterController.php";
require_once __DIR__ . "/../app/controllers/web/user/UserController.php";
require_once __DIR__ . "/../app/controllers/web/author/AuthorController.php";
require_once __DIR__ . "/../app/controllers/web/book/BookController.php";
require_once __DIR__ . "/../app/controllers/web/admin/AdminController.php";
require_once __DIR__ . "/../app/controllers/web/customer/CustomerController.php";
require_once __DIR__ . "/../app/controllers/web/profile/ProfileController.php";
require_once __DIR__ . "/../app/middlewares/AuthMiddlewares.php";
require_once __DIR__ . "/../app/middlewares/RegisterMiddleware.php";
require_once __DIR__ . "/../app/middlewares/GuestMiddleware.php";



Route::get("", \HomeController::class, "index");

Route::get("/auth/register", \RegisterController::class, "index", [\RegisterMiddleware::class]);
Route::get("/auth/createNewUser", \RegisterController::class, "index", [\RegisterMiddleware::class]);
Route::post("/auth/register", \RegisterController::class, "register", [\RegisterMiddleware::class]);

Route::get("/auth/login", \LoginController::class, "index", [\GuestMiddleware::class]);
Route::post("/auth/login", \LoginController::class, "login", [\GuestMiddleware::class]);
Route::get("/auth/logout", \LoginController::class, "logout", [\AuthMiddlewares::class]);

/* ================ User ================ */
Route::get("/profile", \ProfileController::class, "index", [\AuthMiddlewares::class]);
Route::post("/profile/paginationData", \ProfileController::class, "paginationData", [\AuthMiddlewares::class]);
Route::post("/profile/editUserData", \UserController::class, "editUserData", [\AuthMiddlewares::class]);
Route::post("/profile/filterBooks", \BookController::class, "filterBooks", [\AuthMiddlewares::class]);
Route::post("/profile/getItemsToAddIntoCart", \CustomerController::class, "getItemsToAddIntoCart", ["AuthMiddlewares"]);

/* ================ Admin ================ */
Route::post("/profile/addAuthor", \AuthorController::class, "addAuthor", ["AuthMiddlewares:admin"]);
Route::post("/profile/editAuthor", \AuthorController::class, "editAuthor", ["AuthMiddlewares:admin"]);
Route::post("/profile/addBook", \BookController::class, "addBook", ["AuthMiddlewares:admin"]);
Route::post("/profile/banUser", \AdminController::class, "banUser", ["AuthMiddlewares:admin"]);
Route::post("/profile/mangeOrder", \AdminController::class, "mangeOrder", ["AuthMiddlewares:admin"]);
Route::post("/profile/doneOrder", \AdminController::class, "doneOrder", ["AuthMiddlewares:admin"]);

/* ================ Customer ================ */
Route::post("/profile/addBookQuantity", \CustomerController::class, "addBookQuantity", ["AuthMiddlewares:customer"]);
Route::post("/profile/increaseOrderItem", \CustomerController::class, "changeOrderItem", ["AuthMiddlewares:customer"]);
Route::post("/profile/decreaseOrderItem", \CustomerController::class, "changeOrderItem", ["AuthMiddlewares:customer"]);
Route::post("/profile/deleteOrderItem", \CustomerController::class, "deleteOrderItem", ["AuthMiddlewares:customer"]);
Route::post("/profile/orderCustomerOrder", \CustomerController::class, "orderCustomerOrder", ["AuthMiddlewares:customer"]);