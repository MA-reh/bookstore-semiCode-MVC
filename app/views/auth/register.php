<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . "/../shared/metaTagsAndCssLinks.php" ?>

    <title>
        <?php
        if (auth("role") == "admin") {
            echo "Create New Admin";
        } else {
            echo "Register";
        }
        ?>
    </title>
</head>

<body>
    <?php include __DIR__ . "/../shared/navbar.php" ?>

    <?php echo '<pre>';
    // print_r($_SESSION["user"]);
    // unset($_SESSION["_errors"]);
    echo '</pre>'; ?>




    <section id="Register">
        <div class="container my-5">
            <form class="w-25 mx-auto" action="<?= route("/auth/register") ?>" method="POST" enctype="multipart/form-data">
                <h2 class="h1 text-success text-center mb-4 fw-semibold">
                    <?php
                    if (auth("role") == "admin") {
                        echo "Create New Admin";
                    } else {
                        echo "Register";
                    }
                    ?>
                </h2>
                <?= getSessionMag("correct", "correct") ?>
                <?= getSessionMag("invalid", "invalid") ?>

                <div class="mb-3">
                    <label for="Role" class="form-label fw-semibold">Role :</label>
                    <select class="form-control" id="Role" name="role">
                        <option <?= selectedLanguageType("role", '') ?> value="" hidden>Set Your Role</option>

                        <?php


                        if (auth("role") == "admin") {
                            $select = selectedLanguageType("role", 'admin', true);
                            echo "
                            <option {$select} value='admin'>Admin</option>
                            ";
                        } else {
                            $select = selectedLanguageType('role', 'customer', true);
                            echo "
                            <option {$select} value='customer'>Customer</option>
                            ";
                        }
                        ?>


                    </select>
                    <?= getError("role") ?>
                </div>
                <div class="mb-3">
                    <label for="Name" class="form-label fw-semibold">Name :</label>
                    <input type="text" class="form-control" id="Name" value="<?= old("name") ?>" name="name">
                    <?= getError("name") ?>
                </div>
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
                <div class="mb-3">
                    <label for="Phone" class="form-label fw-semibold">Phone :</label>
                    <input type="text" class="form-control" id="Phone" value="<?= old("phone") ?>" name="phone">
                    <?= getError("phone") ?>
                </div>
                <div class="mb-3">
                    <label for="ProfileImage" class="form-label fw-semibold">Profile image :</label>
                    <input type="file" class="form-control" id="ProfileImage" value="<?= old("image") ?>" name="image">
                    <?= getError("image") ?>
                </div>
                <div class="mb-3">
                    <label for="Gender" class="form-label fw-semibold">Gender :</label>
                    <select class="form-control" id="Gender" name="gender">
                        <option <?= selectedLanguageType("gender", '') ?> value="" hidden>Choose Your Gender</option>
                        <option <?= selectedLanguageType("gender", 'male') ?> value="male">Male</option>
                        <option <?= selectedLanguageType("gender", 'female', true) ?> value="female">Female</option>
                    </select>
                    <?= getError("gender") ?>
                </div>
                <button type="submit" class="btn btn-success w-100">
                    <?php
                    if (auth("role") == "admin") {
                        echo "Create New Admin";
                    } else {
                        echo "Register";
                    }
                    ?>
                </button>
            </form>
        </div>
    </section>

    <?php include __DIR__ . "/../shared/globalScriptsJS.php" ?>
</body>

</html>