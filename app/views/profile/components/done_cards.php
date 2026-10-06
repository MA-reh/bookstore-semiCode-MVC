<?php

/**
 * @var array  $data
 */
?>
<div class="table-responsive">
    <table class="table table-info rounded overflow-hidden table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Customer</th>
                <th scope="col">Total Price</th>
                <th scope="col">Details</th>
                <th scope="col">Created_at</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($data["orders"]["done"]["data"])) {
                foreach ($data["orders"]["done"]["data"] as $order) {

                    echo "
                    <tr data-id='{$order["id"]}'>
                        <th scope='row'>{$order['id']}</th>
                        <td>{$order['customer_name']}</td>
                        <td>{$order['total_price']}</td>
                        <td><a href='#' onclick='getItemsToAddIntoCart({$order["id"]}, `showOrder`)'>Show</a></td>
                        <td>{$order['created_at']}</td>
                    </tr>
                    
                    ";
                }
            } else {
                echo "
                <tr>
                    <th colspan='6' class='alert alert-warning text-center mt-2'>Not Found Any Ordered</th>
                </tr>
                    ";
            }
            ?>

        </tbody>
    </table>
</div>


<?php preparePagination($data["orders"]["done"], 'done') ?>