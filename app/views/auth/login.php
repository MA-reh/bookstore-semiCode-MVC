<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . "/../shared/metaTagsAndCssLinks.php" ?>

    <title>Login</title>
</head>

<body>
    <?php include __DIR__ . "/../shared/navbar.php" ?>

    <section id="Login">
        <div class="container my-5">
            <form class="w-25 mx-auto" method="POST" action="<?= route("/auth/login") ?>">
                <h2 class="h1 text-success text-center mb-4 fw-semibold">Login</h2>
                <?= getSessionMag("invalid", "invalid") ?>
                <div class="mb-3">
                    <label for="Email" class="form-label fw-semibold">Email :</label>
                    <input type="email" class="form-control" id="Email" value="<?= old("email") ?>" name="email">
                    <?= getError("email") ?>
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label fw-semibold">Password :</label>
                    <input type="password" class="form-control" id="Password" value="<?= old("password") ?>" name="password">
                    <?= getError("password") ?>
                </div>
                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        </div>
    </section>

    <?php include __DIR__ . "/../shared/globalScriptsJS.php" ?>

</body>

</html>