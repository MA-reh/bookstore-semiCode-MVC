<?php

/**
 * @var array  $data
 */

?>

<div class="row row2 rowOfUsers">

    <?php
    if (!empty($data["admins"]["data"])) {
        foreach ($data["admins"]["data"] as $admin) {

            $imageName = (isset($admin["image"]) && $admin["image"] !== null) ? $admin["image"] : "";

            $imageProfile = (is_file(asset("images\uploads\{$imageName}")) ? asset("images\uploads\{$imageName}") : asset("images\admin.png"));
            $isBadgeBanned = ($admin["is_banned"]) ? "<span class='badge text-bg-danger position-absolute' style='top:10px; right:10px'>Banned</span>" : "";

            $isBtnBanned = "";

            if (auth("role") == "admin") {
                if (auth("id") > $admin["id"]) {
                    $isBtnBanned = "<h6 class='mb-0 alert alert-warning text-center'>You Can't Banned This User</h6>";
                } else {
                    $isBtnBanned = ($admin["is_banned"]) ?
                        "<button class='btn btn-warning w-100 text-light' onclick='banUser({$admin["id"]}, `Unban`, this)'>Unban</button>"
                        :
                        "<button class='btn btn-danger w-100 text-light' onclick='banUser({$admin["id"]}, `Ban`, this)'>Ban</button>";
                }
            }

            echo "
    <div class='col-xl-4 col-md-6 mb-3'>
        <div class='cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded' style='height: 100%;display: flex;justify-content: space-between;' data-id='{$admin["id"]}'>

            {$isBadgeBanned}

            <div class='profile mb-4'>
                <img src='{$imageProfile}' alt='admin logo' class='img-fluid'>
                <h5 class='mb-0'>{$admin['name']}</h5>
            </div>
            <div class='row row3'>
                <div class='col-4 mb-4'>
                    <div class='items'>
                        <h6 class='mb-0'>Email :</h6>
                    </div>
                </div>
                <div class='col-8'>
                    <div class='items'>
                        <h6 class='mb-0'>{$admin['email']}</h6>
                    </div>
                </div>
                <div class='col-4 mb-4'>
                    <div class='items'>
                        <h6 class='mb-0'>Gender :</h6>
                    </div>
                </div>
                <div class='col-8'>
                    <div class='items'>
                        <h6 class='mb-0'>{$admin['gender']}</h6>
                    </div>
                </div>
                <div class='col-4 mb-4'>
                    <div class='items'>
                        <h6 class='mb-0'>Phone :</h6>
                    </div>
                </div>
                <div class='col-8'>
                    <div class='items'>
                        <h6 class='mb-0'>{$admin['phone']}</h6>
                    </div>
                </div>
            </div>
            {$isBtnBanned}
        </div>
    </div>
            ";
        }
    } else {
        echo "
        <div class='col'>
            <h5 class='alert alert-warning text-center mt-2'>Not Found Any Admins</h5>
        </div>
        ";
    }
    ?>

</div>

<?php preparePagination($data["admins"], "admins") ?>