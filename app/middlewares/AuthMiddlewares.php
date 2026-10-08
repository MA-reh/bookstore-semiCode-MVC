<?php

require_once __DIR__ . "/middleware.php";


class AuthMiddlewares implements Middleware
{
    public function handle(string ...$roles): void
    {
        if (!isset($_SESSION["user"])) {
            // Response::error("Your Are Not Access This Page", 401);

            redirect("/auth/login");
        }

        if (empty($roles)) {
            return;
        }

        $currentRoleAuth = $_SESSION["user"]["role"];

        if (!in_array($currentRoleAuth, $roles)) {
            Response::error("Forbidden.", 405);
        }
    }
}
