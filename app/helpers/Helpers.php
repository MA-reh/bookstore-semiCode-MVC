<?php

function pr(mixed $data, bool $die = false)
{
    echo '<pre>';
    print_r(($data));
    echo '</pre>';

    if ($die) {
        exit();
    }
}

function prAPI(mixed $data, bool $die = false)
{
    echo '<pre>';
    print_r(json_encode($data));
    echo '</pre>';

    if ($die) {
        exit();
    }
}

function asset(string $path)
{
    return baseUrl . "/assets/" . ltrim($path, "/");
}

function route(string $path)
{
    return baseUrl . "/" . ltrim($path, "/");
}

function session(string $key, mixed $value = null): mixed
{
    if (func_num_args() === 1) {
        // GETTER
        return $_SESSION[$key] ?? null;
    }
    // SETTER
    $_SESSION[$key] = $value;

    return $_SESSION[$key];
}

function back(?string $key = null, string $msg = "")
{
    $path = $_SERVER["HTTP_REFERER"];

    if ($key !== null) {
        $_SESSION["_msg"][$key] = $msg;
        if ($_SESSION["_msg"]["correct"]) {
            unset($_SESSION["_old"]);
        }
    }

    header("Location: {$path}");
    exit();
}

// Forms Only 
function getError(string $key)
{
    $htmlErr = "";

    if (isset($_SESSION["_errors"][$key])) {
        $htmlErr = "<p class='alert alert-danger mt-2'>{$_SESSION["_errors"][$key][0]}</p>";
        unset($_SESSION["_errors"][$key]);
    }
    return $htmlErr;
}

// to Show Any Message Any Where
function getSessionMag(string $key, string $typeOfMsg)
{
    $htmlErr = "";

    if (isset($_SESSION["_msg"][$key])) {
        $typeAlertMsg = "";

        if ($typeOfMsg == "correct") {
            $typeAlertMsg = "alert-success";
        } else if ($typeOfMsg == "invalid") {
            $typeAlertMsg = "alert-danger";
        } else if ($typeOfMsg == "warning") {
            $typeAlertMsg = "alert-warning";
        }

        $htmlErr = "<p class='alert {$typeAlertMsg} mt-2'>{$_SESSION["_msg"][$key]}</p>";
        unset($_SESSION["_msg"][$key]);
    }

    return $htmlErr;
}

function old(string $key, mixed $default = ""): mixed
{
    $result = $_SESSION["_old"][$key] ?? $default;

    unset($_SESSION["_old"][$key]);

    return $result;
}

function selectedLanguageType(string $key, string $inputValue, bool $last = false)
{
    $result = (isset($_SESSION['_old'][$key]) && $_SESSION['_old'][$key] === $inputValue) ? "selected" : "";

    if ($last) {
        unset($_SESSION["_old"][$key]);
    }

    return $result;
}

function selectedLanguageTypeToEdit(string $key, string $inputValue)
{
    return ($key == $inputValue) ? "selected" : "";
}

function isAuth(?string $key = null): bool
{
    if (!isset($_SESSION["user"])) {
        return false;
    }

    if ($key == null) {
        return true;
    }

    return ($_SESSION["user"]["role"] ?? null) === $key;
}

function auth(?string $key = null, ?string $value = null)
{
    if (func_num_args() == 0 || func_num_args() == 1 || func_get_arg(1) /* arg = 2 */ == null) {
        // getter
        $user = $_SESSION["user"] ?? null;

        if ($key === null) {
            return $user;
        }

        return $user[$key] ?? null;
    }

    // setter
    $_SESSION["user"][$key] = $value;
}

function authJs(?string $key = null, ?string $value = null)
{
    if (func_num_args() == 0 || func_num_args() == 1 || func_get_arg(1) /* arg = 2 */ == null) {
        // getter
        $user = $_SESSION["user"] ?? null;

        if ($key === null) {
            return json_encode($user);
        }

        return json_encode($user[$key]) ?? null;
    }

    // setter
    $_SESSION["user"][$key] = $value;
}

function isGuest(): bool
{
    return !isAuth();
}

function redirect(string $path)
{
    $url = baseUrl . $path;

    header("Location: {$url}");
    exit();
}
