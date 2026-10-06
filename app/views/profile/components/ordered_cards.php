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
                <?php
                if (isAuth("admin")) {
                    echo "<th scope='col'>Options</th>";
                }
                ?>
            </tr>
        </thead>
        <tbody>

            <?php
            if (!empty($data["orders"]["ordered"]["data"])) {
                foreach ($data["orders"]["ordered"]["data"] as $order) {
                    $buttonsAdmin = (isAuth("admin")) ?
                        "<td>
                            <div class='buttons'>
                                <button onclick='preparePopupMangeData(`cancelOrder`, {$order["id"]}, {$order["customer_id"]})' class='btn btn-danger me-2'>Cancel</button>
                                <button onclick='mangeOrder(`done`, {$order["id"]}, {$order["customer_id"]})' class='btn btn-success'>Done</button>
                            </div>
                        </td>"
                        :
                        "";

                    $timeOrder = "";


                    echo "
                    <tr data-id='{$order["id"]}'>
                        <th scope='row'>{$order['id']}</th>
                        <td>{$order['customer_name']}</td>
                        <td>{$order['total_price']}</td>
                        <td><a href='#' onclick='getItemsToAddIntoCart({$order["id"]}, `showOrder`)'>Show</a></td>
                        <td>{$order['created_at']}</td>
                        {$buttonsAdmin}
                    </tr>
                    ";
                }
            } else {
                echo "
                <tr>
                    <th colspan='7' class='alert alert-warning text-center mt-2'>Not Found Any Ordered</th>
                </tr>
                    ";
            }
            ?>
        </tbody>
    </table>
</div>

<?php preparePagination($data["orders"]["ordered"], 'ordered') ?>