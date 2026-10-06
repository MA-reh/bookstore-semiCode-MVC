<?php

require_once __DIR__ . "/../core/Route.php";
require_once __DIR__ . "/../app/controllers/web/HomeController.php";
require_once __DIR__ . "/../app/controllers/api/UserController.php";
require_once __DIR__ . "/../app/middlewares/AuthMiddlewares.php";
require_once __DIR__ . "/../app/middlewares/GuestMiddleware.php";


// Route::post("/api/v1/getUsers", UserController::class, "getData");

