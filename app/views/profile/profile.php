<?php
require_once __DIR__ . "/functions.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile | <?= auth("role") ?> </title>

    <?php include __DIR__ . "/../shared/metaTagsAndCssLinks.php" ?>
    <link rel="stylesheet" href="<?= asset("css/profile/profile.css") ?>">
    <link rel="stylesheet" href="<?= asset("css/profile/profile.responsive.css") ?>">
</head>

<body>
    <?php include __DIR__ . "/../shared/navbar.php" ?>


    <section id="Profile" class="py-3">
        <div class="container-fluid">
            <div class="row row1">
                <div class="col-lg-4 col-xl-3 col-xxl-3 userData mb-3 mb-lg-0">
                    <div class="item">
                        <div class="user-image">
                            <img src="<?= (isset($_SESSION["user"]["image"]) && ($_SESSION["user"]["image"] !== "" || $_SESSION["user"]["image"] !== null)) ? asset("images/uploads/{$_SESSION["user"]['image']}") : asset("images/default.png") ?>" alt="User Profile" class="img-fluid">
                        </div>
                        <div class="username d-flex align-items-center justify-content-center my-3">
                            <i class="fa-regular fa-pen-to-square" onclick="preparePopupMangeData('user', 'name', '<?= auth('name') ?>' , 'Name')"></i>
                            <h4 class="ms-2" data-input-value="name"><?= $_SESSION["user"]['name'] ?></h4>
                        </div>
                        <div class="row row2">
                            <div class="col-5 col-md-2 col-lg-5 col-xl-12 col-xxl-5">
                                <div class="item d-flex align-items-center mb-3">
                                    <i class="fa-regular fa-pen-to-square" onclick="preparePopupMangeData('user', 'email', '<?= auth('email') ?>' , 'Email')"></i>
                                    <h6 class="mb-0 ms-2">Email :</h6>
                                </div>
                            </div>
                            <div class="col-7 col-md-4 col-xl-12 col-xxl-7 mb-3 ">
                                <div class="item" data-input-value="email">
                                    <p class="mb-0"><?= $_SESSION["user"]['email'] ?></p>
                                </div>
                            </div>
                            <div class="col-5 col-md-2 col-lg-5">
                                <div class="item d-flex align-items-center mb-3">
                                    <i class="fa-regular fa-pen-to-square" onclick="preparePopupMangeData('user', 'gender', '<?= auth('gender') ?>' , 'Gender', true)"></i>
                                    <h6 class="mb-0 ms-2">Gender :</h6>
                                </div>
                            </div>
                            <div class="col-7 col-md-4 col-xl-7 mb-3">
                                <div class="item" data-input-value="gender">
                                    <p class="mb-0"><?= $_SESSION["user"]['gender'] ?></p>
                                </div>
                            </div>

                            <div class="col-5 col-md-2 col-lg-5 ">
                                <div class="item d-flex align-items-center mb-3">
                                    <i class="fa-regular fa-pen-to-square" onclick="preparePopupMangeData('user', 'phone', '<?= auth('phone') ?>' , 'Phone')"></i>
                                    <h6 class="mb-0 ms-2">Phone :</h6>
                                </div>
                            </div>
                            <div class="col-7 col-md-4 col-xl-7 mb-3 ">
                                <div class="item" data-input-value="phone">
                                    <p class="mb-0"><?= $_SESSION["user"]['phone'] ?></p>
                                </div>
                            </div>
                            <div class="col-5 col-md-2 col-lg-5 col-xl-12">
                                <div class="item d-flex align-items-center mb-3">
                                    <i class="fa-regular fa-pen-to-square" onclick="preparePopupMangeData('user', 'password', '', 'Password')"></i>
                                    <h6 class="mb-0 ms-2">Password :</h6>
                                </div>
                            </div>
                            <div class="col-7 col-md-4">
                                <div class="item"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-xl-9 col-xxl-9 data">
                    <div class="item profileData position-relative">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button data-nav-category="statistics" class="nav-link me-2 active" id="statistics-tab" data-bs-toggle="tab" data-bs-target="#statistics-tab-pane" type="button" role="tab" aria-controls="statistics-tab-pane" aria-selected="true">Statistics</button>
                            </li>

                            <?php
                            if (isAuth("admin")) {
                                echo "
                                <li class='nav-item' role='presentation'>
                                    <button data-nav-category='admins' class='nav-link me-2' id='admins-tab' data-bs-toggle='tab' data-bs-target='#admins-tab-pane' type='button' role='tab' aria-controls='admins-tab-pane' aria-selected='false'>Admins</button>
                                </li>
                                <li class='nav-item' role='presentation'>
                                    <button data-nav-category='customers' class='nav-link me-2' id='customers-tab' data-bs-toggle='tab' data-bs-target='#customers-tab-pane' type='button' role='tab' aria-controls='customers-tab-pane' aria-selected='false'>Customers</button>
                                </li>
                                <li class='nav-item' role='presentation'>
                                    <button data-nav-category='authors' class='nav-link me-2' id='authors-tab' data-bs-toggle='tab' data-bs-target='#authors-tab-pane' type='button' role='tab' aria-controls='authors-tab-pane' aria-selected='false'>Authors</button>
                                </li>
                            ";
                            }
                            ?>

                            <li class="nav-item" role="presentation">
                                <button data-nav-category="books" class="nav-link me-2 " id="books-tab" data-bs-toggle="tab" data-bs-target="#books-tab-pane" type="button" role="tab" aria-controls="books-tab-pane" aria-selected="false">Books</button>
                            </li>
                            <li class="nav-item dropdown <?= (isAuth("customer")) ? "brDrop" : "" ?>">
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Orders</a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a data-nav-category="ordered" href="#" class="dropdown-item" id="ordered-tab" data-bs-toggle="tab" data-bs-target="#ordered-tab-pane" type="button" role="tab" aria-controls="ordered-tab-pane" aria-selected="false">Ordered</a>
                                    </li>
                                    <li>
                                        <a data-nav-category="canceled" href="#" class="dropdown-item" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled-tab-pane" type="button" role="tab" aria-controls="canceled-tab-pane" aria-selected="false">Canceled</a>
                                    </li>
                                    <li>
                                        <a data-nav-category="done" href="#" class="dropdown-item" id="Done-tab" data-bs-toggle="tab" data-bs-target="#done-tab-pane" type="button" role="tab" aria-controls="done-tab-pane" aria-selected="false">Done</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <?php
                        if (isAuth("customer")) {
                            /**
                             * @var array  $total
                             */
                            echo "
                                <div class='bg-primary-subtle text-primary-emphasis' id='cartNumber' onclick='getItemsToAddIntoCart()'>
                                    <i class='fa-solid fa-cart-shopping'></i>
                                    <span id='cartQuantity' class='position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger'>
                                        {$total['totalItemsIntoCart']}
                                    </span>
                                </div>";
                        }
                        ?>
                        <div class="tab-content mt-4" id="myTabContent">
                            <div class="tab-pane fade categories-tabs show active" id="statistics-tab-pane" role="tabpanel" aria-labelledby="statistics-tab" tabindex="0">
                                <?php include __DIR__ . "/components/statistics_cards.php" ?>
                            </div>
                            <?php
                            if (isAuth("admin")) {
                                echo "<div class='tab-pane fade categories-tabs' data-category='admins' id='admins-tab-pane' role='tabpanel' aria-labelledby='admins-tab' tabindex='0'>";
                                include __DIR__ . '/components/admins_cards.php';
                                echo "</div>";
                                echo "<div class='tab-pane fade categories-tabs' data-category='customers' id='customers-tab-pane' role='tabpanel' aria-labelledby='customers-tab' tabindex='0'>";
                                include __DIR__ . '/components/customers_cards.php';
                                echo "</div>";

                                echo "<div class='tab-pane fade categories-tabs' data-category='authors' id='authors-tab-pane' role='tabpanel' aria-labelledby='authors-tab' tabindex='0'>";
                                include __DIR__ . '/components/authors_cards.php';
                                echo "</div>";
                            }
                            ?>
                            <div class="tab-pane fade categories-tabs" data-category="books" id="books-tab-pane" role="tabpanel" aria-labelledby="books-tab" tabindex="0">

                                <div id="BooksFilter">
                                    <form id="FormBooksFilter" data-form-type='filterBooks'>
                                        <input type="hidden" class="form-control" id="pageNumber" name="page" value="1">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="Title">
                                                            <i class="fa-solid fa-book"></i>
                                                        </label>
                                                        <input type="text" class="form-control" id="Title" name="titleBook" placeholder="Title...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="Author">
                                                            <i class="fa-solid fa-user"></i>
                                                        </label>
                                                        <input type="text" class="form-control" id="Author" name="authorName" placeholder="Author...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="minPrice">
                                                            <i class="fa-solid fa-dollar-sign"></i>
                                                        </label>
                                                        <input type="number" min="0" class="form-control" id="minPrice" name="minPrice" placeholder="Min Price...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="maxPrice">
                                                            <i class="fa-solid fa-dollar-sign"></i>
                                                        </label>
                                                        <input type="number" min="0" class="form-control" id="maxPrice" name="maxPrice" placeholder="Max Price...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="Stock">
                                                            <i class="fa-solid fa-hashtag"></i>
                                                        </label>
                                                        <input type="number" min="0" class="form-control" id="Stock" name="stock" placeholder="Stock...">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <label class="input-group-text" for="Sorting">
                                                            <i class="fa-solid sortIcon desc fa-arrow-up-wide-short"></i>
                                                            <i class="fa-solid sortIcon d-none asc fa-arrow-down-short-wide"></i>
                                                        </label>
                                                        <select onchange="changeSortingIcon(this.value)" class="form-select" id="Sorting" name="sorting">
                                                            <option selected value="DESC">DESC</option>
                                                            <option value="ASC">ASC</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="item">
                                                    <div class="input-group mb-3">
                                                        <button class="btn d-block w-100 btn-success filterBtn">Filter</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <?php include __DIR__ . "/components/books_cards.php" ?>
                            </div>
                            <div class="tab-pane fade categories-tabs" data-category="ordered" id="ordered-tab-pane" role="tabpanel" aria-labelledby="ordered-tab" tabindex="0">
                                <?php include __DIR__ . "/components/ordered_cards.php" ?>
                            </div>
                            <div class="tab-pane fade categories-tabs" data-category="canceled" id="canceled-tab-pane" role="tabpanel" aria-labelledby="canceled-tab" tabindex="0">
                                <?php include __DIR__ . "/components/canceled_cards.php" ?>
                            </div>
                            <div class="tab-pane fade categories-tabs" data-category="done" id="done-tab-pane" role="tabpanel" aria-labelledby="done-tab" tabindex="0">
                                <?php include __DIR__ . "/components/done_cards.php" ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="popupDataModal" tabindex="-1" aria-labelledby="popupDataModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="popupDataModalLabel">Edit Gender</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form data-type="edit" enctype="multipart/form-data">
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary functionBtn">Edit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    if (isAuth("customer")) {
        echo "
            <div class='modal fade popup_Books' id='popupCartModal' tabindex='-1' aria-labelledby='popupCartModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-xl'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='popupCartModalLabel'>Cart</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='titleOrders text-center mb-3'>
                        <h5>
                            Total Order Price : <span class='titlePrice totalNumber text-success'>0.00</span>$
                        </h5>
                    </div>
                    <div class='row data'>
                        
                    </div>
                </div>
                <div class='modal-footer d-none'>
                    <button type='submit' class='btn btn-success w-100'>Order Now</button>
                </div>
            </div>
        </div>
    </div>
    ";
    }
    ?>
    <div class='modal fade popup_Books' id='popupShowOrderModal' tabindex='-1' aria-labelledby='popupShowOrderModalLabel' aria-hidden='true'>
        <div class='modal-dialog modal-xl'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <h1 class='modal-title fs-5' id='popupShowOrderModalLabel'>Cart</h1>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='titleOrders text-center mb-3'>
                        <h5>
                            Total Order Price : <span class='titlePrice totalNumber text-success'>0.00</span>$
                        </h5>
                    </div>
                    <div class='row data'>

                    </div>
                </div>
                <div class='modal-footer d-none'>
                    <button type='submit' class='btn btn-success w-100'>Order Now</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const NUMBER_OF_CARDS = <?= NUMBER_OF_CARDS ?>;
        const baseUrl = "<?= baseUrl ?>";
        const userRole = "<?= auth("role") ?>";
        const userGender = "<?= auth("gender") ?>";
        const auth = JSON.parse(`<?= authJs() ?>`);
        let totalOrdersOrdered = <?= $total["orders"]["ordered"] ?>;
    </script>

    <?php include __DIR__ . "/../shared/globalScriptsJS.php" ?>


    <?php
    if (isAuth("admin")) {
        $linkFile = asset("js/profile/admin.functions.js");
        echo "<script src='{$linkFile}'></script>";
    } else if (isAuth("customer")) {
        $linkFile = asset("js/profile/customer.functions.js");
        echo "<script src='{$linkFile}'></script>";
    }
    ?>

    <script>
        <?php
        if (isset($_SESSION['alerts']['status']) && !empty($_SESSION['alerts']['status'])) {
            echo "        
            function showAlertError() {
                Swal.fire({
                icon: '{$_SESSION['alerts']['typeOfIcon']}',
                title: ' {$_SESSION['alerts']['status']} {$_SESSION['alerts']['title']}',
                text: '{$_SESSION['alerts']['message']}!',
            });
        }";

            echo "showAlertError()";
            unset($_SESSION['alerts']['403']);
        }
        ?>

        function alert() {

        }
    </script>


</body>

</html>