<?php

/**
 * @var array  $data
 */

if (auth("role") == "admin") {
    echo "
    <button class='btn btn-success mb-3 d-block w-100 text-light' onclick='preparePopupMangeData(`addAuthor`)'>
        Add New Author
    </button>
    ";
}

?>


<div class="row row2">



    <?php

    if (!empty($data["authors"]["data"])) {
        foreach ($data["authors"]["data"] as $author) {
            $shortBio =  (isset($author["bio"])) ? trim(substr($author["bio"], 0, 100)) : "Not Founded";
            $dots = (isset($author["bio"])) ? "..." : "";
            $imageProfile = asset("images\author.png");

            $adminBtn = "";
            if (auth("role") == "admin") {
                $adminBtn = "
                <div class='buttons'>
                    <button class='btn btn-info mb-2 d-block w-100 text-light' onclick='preparePopupMangeData(`editAuthor`, this)'>
                        Edit Author
                    </button>
                    
                    <button class='btn btn-success d-block w-100 text-light' onclick='preparePopupMangeData(`addBook`, this)'>
                        Add Book
                    </button>
                </div>
                ";
            }

            echo "

        <div class='col-xl-4 col-md-6 mb-3'>
            <div class='cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded' style='height: 100%;display: flex;justify-content: space-between;' data-id='{$author["id"]}'>
                <div class='profile mb-4'>
                    <img src='{$imageProfile}' alt='{$author["name"]} author logo' class='img-fluid'>
                    <h5 class='mb-0 nameAuthor'>{$author["name"]}</h5>
                </div>
                <div class='row row3 mb-4'>
                    <div class='col-6 mb-2'>
                        <div class='items'>
                            <h6 class='mb-0'>Bio :</h6>
                        </div>
                    </div>
                    <div class='col-12 mb-4'>
                        <div class='items mb-2'>
                            <p class='mb-0'>
                                <span class='bioAuthor'>
                                    {$shortBio}
                                </span>
                                {$dots}
                            </p>
                        </div>
                    </div>
                </div>
                {$adminBtn}
            </div>
        </div>    
        ";
        }
    } else {
        echo "
        <div class='col'>
            <h5 class='alert alert-warning text-center mt-2'>Not Found Any Authors</h5>
        </div>
        ";
    }
    ?>

</div>

<?php preparePagination($data["authors"], "authors") ?>