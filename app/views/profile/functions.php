<?php

function preparePagination(array $data, string $nameOfCategory)
{
    $liHTML = "";
    if (!empty($data["data"])) {

        $currentPage = 1;
        $numberOfPages = ceil($data["total"] / NUMBER_OF_CARDS);


        for ($i = 0; $i <= $numberOfPages + 1; $i++) {

            if ($i == 0) {
                $isDisabled = ($currentPage == 1) ? "disabled" : "";
                $prevPage = ($currentPage == 1) ? 1 : $currentPage - 1;

                $liHTML .= " <li class='page-item'><button data-pagination='prev' onclick='paginationClick(`prev`, this, {$prevPage})' class='btn me-2 pages-links prev {$isDisabled}'>Previous</button></li> <br>";
            } else if ($i == $numberOfPages + 1) {
                $isDisabled = ($currentPage == $numberOfPages) ? "disabled" : "";
                $nextPage = ($currentPage == $numberOfPages) ? $numberOfPages : $currentPage + 1;

                $liHTML .= "<li class='page-item'><button data-pagination='next' onclick='paginationClick(`next`, this, {$nextPage})' class='btn pages-links next {$isDisabled}'>Next</button></li> <br>";
            } else {
                $isActive = ($i === (int)$currentPage) ? "active" : "";

                $liHTML .= "<li class='page-item'><button data-pagination='{$i}' onclick='paginationClick({$i}, this, {$i})' class='btn me-2 pages-links {$isActive}'>{$i}</button></li> <br>";
            }
        }

        echo "<nav aria-label='Page navigation example' class='table-responsive'>
                <ul class='pagination' data-category='{$nameOfCategory}'>
                    {$liHTML}
                </ul>
              </nav>";
    } else {
        echo $liHTML;
    }
}
