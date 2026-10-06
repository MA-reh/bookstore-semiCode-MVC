<?php

/**
 * @var array  $total
 */
?>

<div class="row text-center">
    <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
        <div class="item rounded p-3 bg-primary-subtle text-primary-emphasis">
            <i class="fs-4 mx-auto fa-solid fa-book"></i>
            <h6 class="my-2 fw-semibold">Total Books</h6>
            <p class="mb-0 text-success fw-semibold fs-5"><?= $total['books'] ?></p>
        </div>
    </div>
    <?php

    if (isAuth("admin")) {
        echo "
    <div class='col-xl-3 col-md-4 col-sm-6 mb-3'>
        <div class='item rounded p-3 bg-primary-subtle text-primary-emphasis'>
            <i class='fs-4 mx-auto fa-solid fa-user-group'></i>
            <h6 class='my-2 fw-semibold'>Total Authors</h6>
            <p class='mb-0 text-success fw-semibold fs-5'>{$total['authors']}</p>
        </div>
    </div>
    <div class='col-xl-3 col-md-4 col-sm-6 mb-3'>
        <div class='item rounded p-3 bg-primary-subtle text-primary-emphasis'>
            <i class='fs-4 mx-auto fa-solid fa-users'></i>
            <h6 class='my-2 fw-semibold'>Total Customers</h6>
            <p class='mb-0 text-success fw-semibold fs-5'>{$total['customers']}</p>
        </div>
    </div>
    <div class='col-xl-3 col-md-4 col-sm-6 mb-3'>
        <div class='item rounded p-3 bg-primary-subtle text-primary-emphasis'>
            <i class='fs-4 mx-auto fa-solid fa-users'></i>
            <h6 class='my-2 fw-semibold'>Total Admins</h6>
            <p class='mb-0 text-success fw-semibold fs-5'>{$total['admins']}</p>
        </div>
    </div>
        ";
    } else if (isAuth("customer")) {
        echo "
    <div class='col-xl-3 col-md-4 col-sm-6 mb-3'>
        <div class='item rounded p-3 bg-primary-subtle text-primary-emphasis'>
            <i class='fs-4 mx-auto fa-solid fa-book'></i>
            <h6 class='my-2 fw-semibold'>Total Bought Books</h6>
            <p class='mb-0 text-success fw-semibold fs-5'>{$total['boughtBooks']}</p>
        </div>
    </div>
        ";
    }

    ?>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
        <div class="item rounded p-3 bg-primary-subtle text-primary-emphasis">
            <i class="fs-4 mx-auto fa-solid fa-circle-pause"></i>
            <h6 class="my-2 fw-semibold">Total Pending Orders</h6>
            <p class="mb-0 text-success fw-semibold fs-5 totalPendingOrders"><?= $total['orders']['ordered'] ?></p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6 mb-3">
        <div class="item rounded p-3 bg-primary-subtle text-primary-emphasis">
            <i class="fs-4 mx-auto fa-solid fa-ban"></i>
            <h6 class="my-2 fw-semibold">Total Canceled Orders</h6>
            <p class="mb-0 text-success fw-semibold fs-5"><?= $total['orders']['canceled'] ?></p>
        </div>
    </div>
    <div class="col-xl-3 col-md-4 col-sm-6">
        <div class="item rounded p-3 bg-primary-subtle text-primary-emphasis">
            <i class="fs-4 mx-auto fa-solid fa-circle-check"></i>
            <h6 class="my-2 fw-semibold">Total Done Orders</h6>
            <p class="mb-0 text-success fw-semibold fs-5"><?= $total['orders']['done'] ?></p>
        </div>
    </div>
</div>