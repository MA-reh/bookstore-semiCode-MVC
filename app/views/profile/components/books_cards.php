<?php

/**
 * @var array  $data
 */
?>

<div class="row row2">

    <?php

    if (!empty($data["books"]["data"])) {
        foreach ($data["books"]["data"] as $book) {
            $shortDescription = (isset($book["description"])) ? substr($book["description"], 0, 100) . "..." : "Not Founded";

            $imageName = (isset($book["image"]) && $book["image"] !== null) ? $book["image"] : "";

            $imageProfile = (is_file("assets/images/uploads/{$imageName}") ? asset("images/uploads/{$imageName}") : asset("images/book.png"));

            $buttonsUser = "";
            if (auth("role") == "customer") {
                $buttonsUser = "
                    <div class='input-group'>
                        <input type='number' min='1' class='form-control' id='input-quantity-{$book['id']}' placeholder='Quantity' required>
                        <button class='btn btn-outline-success' onclick='addBookIntoCart({$book["id"]}, {$book['stock']})'>Add To Cart</button>
                    </div>
                ";
                if ($book["stock"] == 0) {
                    $buttonsUser = "";
                }
            } else if (auth("role") == "admin") {
                $buttonsUser = "
                    <p class='alert alert-info text-center mb-0'>You Are Admin</p>
                ";
            }

            $stock = ($book["stock"] > 0) ? $book["stock"] : "Out Of Stock";
            $stockClass = ($book["stock"] > 0) ? "mb-4" : "mb-1";

            echo "
                <div class='col-xl-4 col-sm-6 mb-3'>
                    <div class='cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded' style='height: 100%;display: flex;justify-content: space-between;' data-id='{$book["id"]}'>
                        <div class='profile mb-4'>
                            <img src='{$imageProfile}' alt='{$imageName} logo' class='img-fluid'>
                            <h5 class='mb-0'>{$book["title"]}</h5>
                        </div>
                        <div class='row row3'>
                            <div class='col-4'>
                                <div class='items'>
                                    <h6 class='mb-0'>Author :</h6>
                                </div>
                            </div>
                            <div class='col-8  mb-4'>
                                <div class='items'>
                                    <h6 class='mb-0' data-author-id='{$book["author_id"]}'>{$book["authorName"]}</h6>
                                </div>
                            </div>
                            <div class='col-12 mb-2'>
                                <div class='items'>
                                    <h6 class='mb-0'>Description :</h6>
                                </div>
                            </div>
                            <div class='col-12 mb-4'>
                                <div class='items'>
                                    <h6 class='mb-0 d-block'>
                                    {$shortDescription}
                                    </h6>
                                </div>
                            </div>
                            <div class='col-4'>
                                <div class='items'>
                                    <h6 class='mb-0'>Price :</h6>
                                </div>
                            </div>
                            <div class='col-8  mb-4'>
                                <div class='items'>
                                    <h6 class='mb-0'>{$book["price"]}</h6>
                                </div>
                            </div>
                            <div class='col-4'>
                                <div class='items'>
                                    <h6 class='mb-0'>Stock :</h6>
                                </div>
                            </div>
                            <div class='col-8 {$stockClass}'>
                                <div class='items'>
                                    <h6 class='mb-0 stockBook'>{$stock}</h6>
                                </div>
                            </div>
                        </div>
                        {$buttonsUser}
                    </div>
                </div>
            ";
        }
    } else {
        echo "
        <div class='col'>
            <h5 class='alert alert-warning text-center mt-2'>Not Found Any Books</h5>
        </div>
        ";
    }

    ?>

</div>

<?php preparePagination($data["books"], "books") ?>