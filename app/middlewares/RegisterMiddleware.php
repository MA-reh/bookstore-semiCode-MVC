<?php

require_once __DIR__ . "/middleware.php";

class RegisterMiddleware implements Middleware
{
    public function handle(string ...$roles): void
    {
        if (isAuth("customer")) {
            // Response::error("Already Authenticated.", 403);
            redirect("/profile");
        }
    }
}
