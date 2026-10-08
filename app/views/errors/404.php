<!DOCTYPE html>
<html lang="en">

<head>
    <title>404 Page Not Found</title>

    <?php include __DIR__ . "/../shared/metaTagsAndCssLinks.php" ?>
    <style>
        #error .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

</head>

<body>

    <div id="error" class="vh-100">
        <div class="container h-100">
            <div class="content text-center">
                <h1 class="text-danger mb-4" style="font-size: 100px;">404 Not Found</h1>
                <p>Check Your Request</p>
            </div>
        </div>
    </div>

</body>

</html>