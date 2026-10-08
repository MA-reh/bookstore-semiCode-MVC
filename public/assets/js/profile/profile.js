popupForm.addEventListener("submit", function (e) {
    e.preventDefault();

    if (formPopupType == "edit") {
        let input = popupForm.querySelector(".userInput"),
            inputName = input.name,
            inputValue = input.value;
        if (lastValue == inputValue) return;

        lastValue = inputValue;
        let arr = {
            "inputName": inputName,
        }
        arr[inputName] = inputValue;

        newRequestByAjax("profile/editUserData", "POST", arr, editUser, showAlerts);
    } else if (formPopupType == "addAuthor") {
        let dataForm = new FormData(this);

        newRequestByAjax("profile/addAuthor", "POST", dataForm, addAuthor, showAlerts);
    } else if (formPopupType == "editAuthor") {
        let dataForm = new FormData(this);

        newRequestByAjax("profile/editAuthor", "POST", dataForm, editAuthor, showAlerts);
    } else if (formPopupType == "addBook") {
        let dataForm = new FormData(this);

        newRequestByAjax("profile/addBook", "POST", dataForm, addBook, showAlerts);
    }
});

FormBooksFilter.addEventListener("submit", function (e) {
    e.preventDefault();
    let formBooksFilterType = this.getAttribute("data-form-type");

    if (formBooksFilterType == "filterBooks") {
        let dataForm = new FormData(this);
        // dataForm.append("page", 1); // To add New Value in my FormData

        let formValues = {
            "titleBook": dataForm.get("titleBook"),
            "authorName": dataForm.get("authorName"),
            "minPrice": dataForm.get("minPrice"),
            "maxPrice": dataForm.get("maxPrice"),
            "stock": dataForm.get("stock"),
            "sorting": dataForm.get("sorting"),
        }

        if (checkLastValueOfFormSortingBooks(formValues, lastSearchValue, 6)) return;

        $("ul[data-category='books'] li .pages-links").attr("disabled");
        btnForm.setAttribute("disabled", true);
        isClickedPaginationFilter = true;
        newRequestByAjax("profile/filterBooks", "POST", dataForm, sortingBooks, showAlerts);
        lastSearchValue = formValues;
    }

});