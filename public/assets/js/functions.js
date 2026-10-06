let currentIndicator = 1,
    numberOfPages,
    nameOfCategory,
    searchValue = "",
    lastSearchValue = "",
    ul,
    pageNumberBooks,
    activeIndicator,
    indicator,
    lastValue,
    isClickedPaginationFilter = false,
    formPopupType,
    paginationBtnContent = 1,
    formBooksFilterType,
    errorsInputsName = [],
    $indicators,
    popupForm = document.querySelector("#popupDataModal form"),
    FormBooksFilter = document.querySelector("#FormBooksFilter"),
    btnForm = document.querySelector("#FormBooksFilter .filterBtn"),
    banBtnForUser,
    quantityInput;

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

function newRequestByAjax(pathToRequest = "", typeOfRequest = "POST", objectOfData = "", successFunction, errorFunction = showAlerts, hasReturn = false) {
    $.ajax({
        url: pathToRequest,
        type: typeOfRequest,
        data: objectOfData,
        success: function (response) {
            if (hasReturn) {
                return successFunction(response);
            } else {
                successFunction(response);
            }
        },
        error: function (response) {
            errorFunction(response["responseJSON"]);
        },
    });
}

function returnDataOfResponse(response) {
    return {
        message: response["message"] ?? "",
        data: response["data"] ?? "",
        status: response["status" ?? ""]
    }
}

function openPopups(ModalId) {
    let myModal = new bootstrap.Modal(document.getElementById(`${ModalId}`));
    myModal.show();
    document.activeElement?.blur();
}

function closePopupNewData(ModalId) {
    $(`#${ModalId}`).modal('hide');
}

function preparePopupMangeData(typeInputs, inputName, inputValue, labelInput, isGender = false) {

    let footerPopup = $(`#popupDataModal .modal-footer`),
        bodyPopup = $(`#popupDataModal .modal-body`),
        popupModalLabel = $(`#popupDataModalLabel`);

    if (typeInputs == "user") {
        formPopupType = 'edit';
        popupForm.setAttribute("data-type", formPopupType);
        popupModalLabel.text(`Edit ${labelInput}`);
        $(`#popupDataModal label`).text(labelInput);
        if (isGender) {
            bodyPopup.html(
                `<div class="mb-3">
                    <label for="${labelInput}" class="form-label">${labelInput}</label>
                    <select class="form-control userInput" id="${labelInput}" name="${inputName}">
                        <option ${selectedLanguageTypeToEdit(auth["gender"], '')} value="" hidden>Choose Your Gender</option>
                        <option ${selectedLanguageTypeToEdit(auth["gender"], 'male')} value="male">Male</option>
                        <option ${selectedLanguageTypeToEdit(auth["gender"], 'female')} value="female">Female</option>
                    </select>
                    <p class='alert alert-danger inputError mt-2 d-none' data-error-name="gender"></p>
                </div>`
            );
        } else {
            bodyPopup.html(prepareInputsForPopups(typeInputs, inputName, inputValue, labelInput));
        }
        footerPopup.html(`
        <button type="submit" class="btn btn-primary functionBtn">Edit</button>
        `);
    } else if (typeInputs == "addAuthor") {
        authorPopups(typeInputs, "Add Author");

    } else if (typeInputs == "editAuthor") {
        authorPopups(typeInputs, "Edit Author", inputName /* btn */);
    } else if (typeInputs == "addBook") {
        authorPopups(typeInputs, "Add Book", inputName /* btn */);
    } else if (typeInputs == "cancelOrder") {
        cancelOrderReason(typeInputs, inputName, inputValue);
        // preparePopupMangeData('cancelOrder', ${data["id"]}, ${data["customer_id"]})
    }

    openPopups("popupDataModal");
}

function authorPopups(typeInputs, modalLabel, btnEdit) {
    let footerPopup = $(`#popupDataModal .modal-footer`),
        bodyPopup = $(`#popupDataModal .modal-body`),
        popupModalLabel = $(`#popupDataModalLabel`);

    formPopupType = typeInputs;
    popupForm.setAttribute("data-type", formPopupType);

    popupModalLabel.text(`${modalLabel}`);
    bodyPopup.html(prepareInputsForPopups(typeInputs, btnEdit));

    footerPopup.html(`
        <button type="submit" class="btn btn-${(typeInputs == "addAuthor" || typeInputs == "addBook") ? "success" : "info text-light"} functionBtn" data-btn-category="authors">${modalLabel}</button>
        `);
}

function prepareInputsForPopups(typeInputs, inputName = null, inputValue = null, labelInput = null) {
    let inputHtml = "";

    if (typeInputs == "addAuthor") {
        inputHtml = `
            <div class="mb-3">
                <label for="DATA1" class="form-label">Author Name:</label>
                <input type="text" class="form-control" id="DATA1" name="authorName">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorName"></p>
            <div class="mb-3">
                <label for="authorBio" class="form-label">Author Bio:</label>
                <textarea name="authorBio" rows="10" class="form-control" id="authorBio"></textarea>
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorBio"></p>
        `;
    } else if (typeInputs == "editAuthor") {
        let btn = inputName,
            cardAuthor = btn.closest("[data-id]"),
            idAuthor = $(cardAuthor).attr("data-id"),
            nameAuthor = $(cardAuthor).find(".nameAuthor").text().trim(),
            bioAuthor = $(cardAuthor).find(".bioAuthor").text().trim();

        inputHtml = `
            <input type="hidden" value="${idAuthor}" name="authorId">
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorId"></p>
            <div class="mb-3">
                <label for="DATA1" class="form-label">Author Name:</label>
                <input type="text" class="form-control" id="DATA1" value="${nameAuthor}" name="authorName">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorName"></p>
            <div class="mb-3">
                <label for="authorBio" class="form-label">Author Bio:</label>
                <textarea name="authorBio" rows="10" class="form-control" id="authorBio">${bioAuthor}</textarea>
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorBio"></p>
        `;

    } else if (typeInputs == "user") {
        inputHtml = `
            <div class="mb-3">
                <label for="DATA" class="form-label">${labelInput}</label>
                <input type="${(inputName == "email") ? "email" : (inputName == "password") ? "password" : "text"}" class="form-control userInput" id="DATA" value="${inputValue}" name="${inputName}">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="${inputName}"></p>
        `;
    } else if (typeInputs == "addBook") {
        let btn = inputName,
            cardAuthor = btn.closest("[data-id]"),
            idAuthor = $(cardAuthor).attr("data-id"),
            nameAuthor = $(cardAuthor).find(".nameAuthor").text().trim(),
            bioAuthor = $(cardAuthor).find(".bioAuthor").text().trim();

        inputHtml = `
            <input type="hidden" value="${idAuthor}" name="authorId">
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorId"></p>
            <input type="hidden" class="form-control" value="${nameAuthor}" name="authorName">
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="authorName"></p>
            <div class="mb-3">
                <label for="DATA1" class="form-label">Author Name:</label>
                <input type="text" class="form-control" id="DATA1" value="${nameAuthor}" disabled>
            </div>
            <div class="mb-3">
                <label for="DATA2" class="form-label">Title Book:</label>
                <input type="text" class="form-control" id="DATA2" name="bookTitle">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="bookTitle"></p>
            <div class="mb-3">
                <label for="DATA3" class="form-label">Book Image:</label>
                <input type="file" class="form-control" id="DATA3" name="bookImage">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="bookImage"></p>
            <div class="mb-3">
            <label for="DescriptionBook" class="form-label">Description:</label>
            <textarea name="bookDescription" rows="5" class="form-control" id="DescriptionBook"></textarea>
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="bookDescription"></p>
            <div class="mb-3">
                <label for="DATA4" class="form-label">Price:</label>
                <input type="number" min="0" class="form-control" id="DATA4" name="bookPrice">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="bookPrice"></p>
            <div class="mb-3">
                <label for="DATA5" class="form-label">Stock:</label>
                <input type="number" min="0" class="form-control" id="DATA5" name="bookStock">
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="bookStock"></p>
        `;
    } else if (typeInputs == "cancelOrder") {
        inputHtml = `
            <div class="mb-3">
                <label for="cancelOrderReason" class="form-label">Cancel Reason:</label>
                <textarea name="cancelReason" rows="10" class="form-control" id="cancelOrderReason"></textarea>
            </div>
            <p class='alert alert-danger inputError mt-2 d-none' data-error-name="cancelReason"></p>
        `;
    }

    return inputHtml;
}

function resetPopupForm(data) {
    popupForm.reset();
    closePopupNewData("popupDataModal");

    for (let objOfData in data) {
        // (objOfData) // key of value
        // (data[objOfData]) // String of success Value Add Author

        alertInputsIsShow(objOfData);
    }
    errorsInputsName = [];
}

function editUser(response) {
    let { message, data, status } = returnDataOfResponse(response);

    if (status == 200) /* Successfully */ {
        setTimeout(function () {
            alertInputs("success", message);
        }, 100);

        for (let objectOfData in data) {
            $(`[data-input-value="${objectOfData}"]`).html(`<p class="">${data[objectOfData]}</p>`);

            auth[objectOfData] = data[objectOfData];
        }

        closePopupNewData("popupDataModal");
    }
}

function getItemsToAddIntoCart(orderId = null, status = "cart") {
    let dataForm = {
        statusFunction: status,
    }
    if (orderId != null) {
        dataForm["orderId"] = orderId;
    }

    newRequestByAjax("profile/getItemsToAddIntoCart", "POST", dataForm, openPopupBooks);
}

function openPopupBooks(response) {
    let { message, data, status } = returnDataOfResponse(response);

    $("#popupCartModal .modal-body .data").html("");
    $("#popupShowOrderModal .modal-body .data").html("");

    if (data["books"].length > 0) {

        if (data["statusFunction"] == "cart") {
            $("#popupCartModal .totalNumber").text(data["books"][0]["total_price"]); // Total Price

            data["books"].forEach(function (book, index) {
                $("#popupCartModal .modal-body .data").prepend(bookComponent(book, "books", data["statusFunction"]));
            });

            $("#popupCartModal .modal-footer").html(`<button onclick="orderCustomerOrder(${data["order_id"]})" class='btn btn-success w-100 d-block'>Order Now</button>`);
            $("#popupCartModal .modal-footer").removeClass("d-none");

        } else if (data["statusFunction"] == "showOrder") {

            $("#popupShowOrderModal .totalNumber").text(data["books"][0]["total_price"]);

            data["books"].forEach(function (book, index) {
                $("#popupShowOrderModal .modal-body .data").prepend(bookComponent(book, "books", "showOrder"));
            });
        }
    } else {
        $("#popupCartModal .modal-footer").html("");
        $("#popupCartModal .modal-footer").addClass("d-none");
        $("#popupCartModal .modal-body .data").html(` <div class='col'><h5 class='alert alert-warning text-center mt-2'>Your Cart Is Empty</h5></div>`);
    }

    // Open Popup
    if (data["statusFunction"] == "cart") {
        openPopups("popupCartModal");
    } else if (data["statusFunction"] == "showOrder") {
        openPopups("popupShowOrderModal");
    }

}






function removeLastElementIntoRowData(categoryName) {
    let $rowOfDataPagination = $(`.categories-tabs[data-category="${categoryName}"] .row2`),
        lengthCards = $rowOfDataPagination.children().length;

    $rowOfDataPagination.children()[lengthCards - 1].remove();

}

function insertingBooksIntoRow(total, dataOfBook, typeInsert = "append") {
    let $rowOfDataPagination = $(`.categories-tabs[data-category="books"] .row2`);

    if (total > 0) {
        if (typeInsert == "append") {
            $rowOfDataPagination.append(cardStructure(dataOfBook, "books"));
        } else if (typeInsert == "prepend") {
            $rowOfDataPagination.prepend(cardStructure(dataOfBook, "books"));
        }
    } else {
        $rowOfDataPagination.append(`<div class='col'><h5 class='alert alert-warning text-center mt-2'>Not Found Any Books</h5></div>`);
    }
}

function alertInputs(typeLogo = 'error', errors = "Your Ajax Request Has Failed") {

    let errorsStr = "";
    if (Array.isArray(errors)) {
        for (let i = 0; i < errors.length; i++) {
            errorsStr += errors[i] + "<br>"
        }
    } else {
        errorsStr = errors;
    }

    Swal.mixin({
        toast: true,
        position: "bottom-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    }).fire({
        icon: typeLogo,
        title: errorsStr
    });

}

function alertInputsIsShow(inputName, isShow = false, errorMsg = "") {

    let alertElement = $(
        `.inputError[data-error-name="${inputName}"]`
    );

    errorsInputsName.push(inputName);

    if (isShow) {
        alertElement
            .html(errorMsg)
            .removeClass("d-none");
    } else {
        alertElement
            .addClass("d-none")
            .html("");
    }

    $(`.inputError`).each(function () {
        let currentInputName = $(this).attr("data-error-name");

        if (!errorsInputsName.includes(currentInputName)) {
            $(this)
                .addClass("d-none")
                .html("");
        }
    });
}

function showAlerts(response, typeAlert = "alert") {
    let { message, status } = returnDataOfResponse(response);

    if (status == 422) {
        let { data } = returnDataOfResponse(response);
        let errorsStr = "";
        for (let objOfErrors in data) {
            // (objOfErrors) // key of objOfErrors
            // (data[objOfErrors]) // array of errors
            data[objOfErrors].forEach(function (msg, index) {
                if (index > 0) {
                    errorsStr += "<br>";
                }
                errorsStr += msg;
            });
            alertInputsIsShow(objOfErrors, true, errorsStr);
            errorsStr = "";
        }

        errorsInputsName = [];

        if (typeAlert == "toast") {
            setTimeout(function () {
                Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                }).fire({
                    icon: "error",
                    title: message
                });
            }, 100);
        } else if (typeAlert == "alert") {
            setTimeout(function () {
                Swal.fire({
                    icon: "error",
                    title: "Invalid Data !",
                    text: message,
                });
            }, 100);
        }

    } else if (status == 403) {
        setTimeout(function () {
            Swal.fire({
                icon: "error",
                title: "Forbidden For You",
                text: message,
            });
        }, 100)
    } else if (status == 200) {
        if (typeAlert == "toast") {
            Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            }).fire({
                icon: "success",
                title: message
            });
        } else if (typeAlert == "alert") {
            setTimeout(function () {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: message,
                });
            }, 100)
        }
    }
}

function checkLastValueOfFormSortingBooks(newObjValues, lastObjValues, objLength) {
    let flags = [];

    for (let inputName in newObjValues) {
        if (lastObjValues[inputName] == newObjValues[inputName]) {
            flags.push(true);
        }
    }

    if (flags.length == objLength) {
        return flags.includes(true);
    }
    return false;
}

function sortingBooks(response) {
    let { message, data, status } = returnDataOfResponse(response);


    if (status == 200) {
        let categoryName = "books";
        let $rowOfDataPagination = $(`.categories-tabs[data-category="${categoryName}"] .row2`);

        btnForm.removeAttribute("disabled");

        $rowOfDataPagination.html("");

        if (data["total"] > 0) {
            data["data"].forEach((dataOfObject) => {
                insertingBooksIntoRow(data["total"], dataOfObject);
            });
        } else {
            insertingBooksIntoRow(data["total"]);
        } // Done

        $("ul[data-category='books'] li .pages-links").removeAttr("disabled");

        pagination(categoryName, data["total"], paginationBtnContent, isClickedPaginationFilter);
    }

}

function filterPagination(contentBtn, that, numberOfCurrentPage) {
    $("ul[data-category='books'] li .pages-links").attr("disabled");

    // $(FormBooksFilter).find("#pageNumber").val(numberOfCurrentPage);
    // $("#FormBooksFilter").trigger("submit"); // Does not Working /* If Turn on this this line on fire it does reload Page and send data in get */

    if (paginationStatus(that, numberOfCurrentPage, contentBtn) == false) return;

    paginationBtnContent = that.getAttribute("data-pagination-number");

    isClickedPaginationFilter = true;


    let dataPaginationFilter = {
        page: paginationBtnContent,
        ...lastSearchValue
    };


    newRequestByAjax("profile/filterBooks", "POST", dataPaginationFilter, sortingBooks, showAlerts);
}

function paginationStatus(that, numberOfCurrentPage, contentBtn) {
    let ul = that.closest("[data-category]");
    indicator = ul.querySelector(`li .pages-links[data-pagination="${contentBtn}"]`);
    $indicators = $(ul).find("li .pages-links");

    if ($indicators.attr("disabled") || indicator.classList.contains("active") || indicator.classList.contains("clicked") || indicator.classList.contains("disabled")) return false;

    $indicators.attr("disabled", true);

    if (contentBtn == "prev") {
        currentIndicator = (numberOfCurrentPage > 1) ? numberOfCurrentPage-- : 1;
    } else if (contentBtn == "next") {
        currentIndicator = (numberOfCurrentPage >= numberOfPages) ? numberOfPages : numberOfCurrentPage++;
    } else {
        currentIndicator = contentBtn;
    }

    nameOfCategory = ul.getAttribute("data-category");

    return {
        "categoryName": nameOfCategory
    }
}

function paginationClick(contentBtn, that, numberOfCurrentPage) {
    let returnValueOfFunction = paginationStatus(that, numberOfCurrentPage, contentBtn);

    if (returnValueOfFunction == false) return;
    let { categoryName } = returnValueOfFunction;

    paginationData(categoryName, currentIndicator);
}

function paginationData(categoryName, indicatorNumber = 1) {

    $.ajax({
        url: 'profile/paginationData',
        type: 'POST',
        data: {
            "categoryName": categoryName,
            "page": indicatorNumber
        },
        success: function (data) {
            let allData = data["data"]["data"][categoryName]["data"],
                totalData = data["data"]["total"],
                $rowOfDataPagination = $(`.categories-tabs[data-category="${categoryName}"] .row2`),
                $rowOfOrdersPagination = $(`.categories-tabs[data-category="${categoryName}"] .table-responsive table tbody`);
            $indicators.prop("disabled", false);

            if (categoryName == "ordered" || categoryName == "canceled" || categoryName == "done") {
                $rowOfOrdersPagination.html("");
            } else {
                $rowOfDataPagination.html("");
            }

            allData.forEach((dataOfObject) => {
                if (categoryName == "ordered" || categoryName == "canceled" || categoryName == "done") {
                    $rowOfOrdersPagination.append(cardStructure(dataOfObject, categoryName));
                } else {
                    $rowOfDataPagination.append(cardStructure(dataOfObject, categoryName));
                }
            });

            pagination(categoryName, totalData, indicatorNumber);
        },
        error: function (error) {
            Swal.fire({
                icon: "error",
                title: "You Are Write That Search You",
                text: error?.responseJSON?.message ?? "oops! \n something Was Error",
            });
        },
    });
}

function pagination(categoryName, total, currentPage, isFilter = false) {
    let ulJquery;

    if (isFilter) {
        ulJquery = $(`ul[data-category="${categoryName}"]`);
    } else {
        ulJquery = $(`ul[data-category="${categoryName}"]`);
    }

    ulJquery.html("");

    let liHTML = "";

    numberOfPages = Math.ceil(total / NUMBER_OF_CARDS);

    if (total > 0) {
        for (let i = 0; i <= numberOfPages + 1; i++) {
            if (i == 0) {
                let isDisabled = (currentPage == 1) ? "disabled" : "";
                let prevPage = (currentPage == 1) ? 1 : currentPage - 1;
                let onClickFun = (isFilter) ?
                    `filterPagination("prev", this, ${prevPage})`
                    :
                    `paginationClick("prev", this, ${prevPage})`;

                liHTML += `<li class='page-item'><button data-pagination="prev" data-pagination-number="${prevPage}" onclick='${onClickFun}' class='btn me-2 pages-links prev ${isDisabled}'>Previous</button></li>`;
            } else if (i == numberOfPages + 1) {
                let isDisabled = (currentPage == numberOfPages) ? "disabled" : "";
                let nextPage = (currentPage == numberOfPages) ? numberOfPages : +currentPage + 1;
                let onClickFun = (isFilter) ?
                    `filterPagination("next", this, ${nextPage})`
                    :
                    `paginationClick("next", this, ${nextPage})`;

                liHTML += `<li class='page-item'><button data-pagination="next" data-pagination-number="${nextPage}" onclick='${onClickFun}' class='btn pages-links next ${isDisabled}'>Next</button></li>`;
            } else {
                let isActive = (i == currentPage) ? "active" : "";
                let onClickFun = (isFilter) ?
                    `filterPagination(${i}, this, ${i})`
                    :
                    `paginationClick(${i}, this, ${i})`;

                liHTML += `<li class='page-item'><button data-pagination="${i}" data-pagination-number="${i}" onclick='${onClickFun}' class='btn me-2 pages-links ${isActive}'>${i}</button></li>`;
            }
        }
    }

    ulJquery.append(liHTML);
}

// Cards Profile

function getImagePath(data, categoryName) {
    let categoryNameItem = categoryName.slice(0, -1);
    let imageName = (data["image"] == "" || data["image"] == null) ? `${categoryNameItem}.png` : data["image"];


    if (categoryName === "books" && data.image) {
        imageName = data["image"];
    }

    return {
        imageName,
        imagePath: `${baseUrl}/assets/images/${(data.image == null) ? `${imageName}` : `uploads/${imageName}`}`
    };
}

function cardStructure(data, categoryName) {

    if (categoryName === "admins" || categoryName === "customers") {
        return userCardComponent(data, categoryName);
    }
    else if (categoryName === "authors") {
        return authorComponent(data, categoryName);
    }
    else if (categoryName === "books") {
        return bookComponent(data, categoryName);
    }
    else if (categoryName === "ordered" || categoryName === "canceled" || categoryName === "done") {
        return orderComponent(data, categoryName);
    }

    return "";
}

function userCardComponent(data, categoryName, hasBanded = false) {

    let { imageName, imagePath } = getImagePath(data, categoryName);

    let isBanned = data.is_banned;

    let bannedBadge = (isBanned) ? ` <span class="badge text-bg-danger position-absolute" style="top:10px; right:10px">Banned</span> ` : "";

    let bannedButton = (isBanned) ?
        ` <button class="btn btn-warning w-100 text-light" onclick='banUser(${data.id}, "Unban", this)'>Unban</button> `
        :
        ` <button class="btn btn-danger w-100 text-light" onclick='banUser(${data.id}, "Ban", this)'>Ban</button> `;

    if (auth["role"] == "admin") {
        if (data["role"] == "admin" && auth["id"] > data["id"]) {
            bannedButton = "<h6 class='mb-0 alert alert-warning text-center'>You Can't Banned This User</h6>";
        } else {
            bannedButton = (data["is_banned"]) ?
                `<button class='btn btn-warning w-100 text-light' onclick='banUser(${data["id"]}, "Unban", this)'>Unban</button>`
                :
                `<button class='btn btn-danger w-100 text-light' onclick='banUser(${data["id"]}, "Ban", this)'>Ban</button>`
        }
    }

    let cardUserHtml = `
            ${bannedBadge}

            <div class="profile mb-4">
                <img src="${imagePath}" alt="${imageName} logo" class="img-fluid">

                <h5 class="mb-0">
                    ${data.name}
                </h5>
            </div>

            <div class="row row3">
                <div class="col-4 mb-4">
                    <div class="items">
                        <h6 class="mb-0">Email :</h6>
                    </div>
                </div>
                <div class="col-8">
                    <div class="items">
                        <h6 class="mb-0">${data.email}</h6>
                    </div>
                </div>
                <div class="col-4 mb-4">
                    <div class="items">
                        <h6 class="mb-0">Gender :</h6>
                    </div>
                </div>
                <div class="col-8">
                    <div class="items">
                        <h6 class="mb-0">${data.gender}</h6>
                    </div>
                </div>
                <div class="col-4 mb-4">
                    <div class="items">
                        <h6 class="mb-0">Phone :</h6>
                    </div>
                </div>
                <div class="col-8">
                    <div class="items">
                        <h6 class="mb-0">${data.phone}</h6>
                    </div>
                </div>
            </div>

           ${bannedButton}
    `

    if (hasBanded) {
        return cardUserHtml
    }

    return `
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded" style="height: 100%; display: flex; justify-content: space-between;" data-id="${data.id}">

                ${cardUserHtml}

            </div>
        </div>
    `;
}

function authorComponent(data, categoryName, isEditing = false) {

    let { imagePath } = getImagePath(data, categoryName);

    let shortBio = data.bio?.slice(0, 100).trim() ?? "Not Founded",
        dots = (data.bio) ? "..." : "";


    let btnAdmin = (auth["role"]) ?
        `
        <div class='buttons'>
            <button class='btn btn-info mb-2 d-block w-100 text-light' onclick='preparePopupMangeData("editAuthor", this)'>
                Edit Author
            </button>
            <button class='btn btn-success d-block w-100 text-light' onclick='preparePopupMangeData("addBook", this)'>
                Add Book
            </button>
        </div>
        `
        :
        ``;

    let authorStructure = `
                <div class="profile mb-4">
                    <img src="${imagePath}" alt="${data.name} logo" class="img-fluid">

                    <h5 class="mb-0 nameAuthor">
                        ${data.name}
                    </h5>
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
                                    ${shortBio}
                                </span>
                                ${dots}
                            </p>
                        </div>
                    </div>
                </div>
                ${btnAdmin}`;

    if (isEditing) {
        return authorStructure;
    }


    return `
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded" style="height: 100%; display: flex; justify-content: space-between;" data-id="${data.id}">
                ${authorStructure}
            </div>
        </div>
    `;
}

function bookComponent(data, categoryName, status = "book", hasReturn = false) {

    let { imageName, imagePath } = getImagePath(data, categoryName);

    let shortDescription =
        data.description?.slice(0, 100) ?? "Not Founded";


    let buttonsUser = "";
    let iconDelete = "";

    let additionalParts = "";

    if (status == "book") {
        additionalParts = `
                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">Stock :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0 stockBook">
                                ${(data.stock > 0) ? data.stock : `Out Of Stock`}
                            </div>
                    </div>
        `;
        if (auth["role"] == "customer") {
            if (data.stock > 0) {
                buttonsUser = `
                <div class='input-group'>
                    <input type='number' min='1' class='form-control' id='input-quantity-${data['id']}' placeholder='Quantity' required>
                    <button class='btn btn-outline-success' onclick='addBookIntoCart(${data["id"]}, ${data["stock"]})'>Add To Cart</button>
                </div>    
                `;
            }
        }
    } else if (status == "cart") {

        iconDelete = `<i onclick="deleteItemFromCart(${data.order_item_id}, this)" class="fa-solid fa-trash-can position-absolute text-danger" style="right:15px; top:15px; cursor:pointer"></i>`;

        additionalParts = `
                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">SubTotal :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0 new-subTotal">
                                ${data.subtotal}
                            </h6>
                        </div>
                    </div>
        `;

        if (auth["role"] == "customer") {
            buttonsUser = `
            <div class='input-group w-75 mx-auto'>
                <button class='btn btn-outline-danger fw-bolder changeOrderItem' onclick='changeOrderItem("decrease", ${data.order_item_id}, this)'><i class="fa-solid fa-minus"></i></button>
                <input type='number' min='0' class='form-control text-center' id='input-quantity-${data.id ?? data["book_id"]}' value='${data["quantity"]}' placeholder='Quantity' required disabled>
                <button class='btn btn-outline-success fw-bolder changeOrderItem' onclick='changeOrderItem("increase", ${data.order_item_id}, this)'><i class="fa-solid fa-plus"></i></button>
            </div>    
        `;
        }

    } else if (status == "showOrder") {
        additionalParts = `
                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">Quantity :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0">
                                ${data.quantity}
                            </h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">SubTotal :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0">
                                ${data.subtotal}
                            </h6>
                        </div>
                    </div>
        `;
    }

    let structureOfBook = `
                ${iconDelete}
                <div class="profile mb-4">
                    <img src="${imagePath}" alt="${imageName} logo" class="img-fluid">

                    <h5 class="mb-0">
                        ${data.title}
                    </h5>
                </div>

                <div class="row row3">
                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">Author :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0" data-author-id="${data["author_id"]}">
                                ${data.authorName ?? data["author_name"]}
                            </h6>
                        </div>
                    </div>

                    <div class="col-5 mb-2">
                        <div class="items">
                            <h6 class="mb-0">Description :</h6>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="items">
                            <h6 class="mb-0 d-block">
                                ${shortDescription}...
                            </h6>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="items">
                            <h6 class="mb-0">Price :</h6>
                        </div>
                    </div>
                    <div class="col-8 mb-4">
                        <div class="items">
                            <h6 class="mb-0">
                                ${data.price}
                            </h6>
                        </div>
                    </div>

                    ${additionalParts}                
                </div>

                ${buttonsUser}
                `;

    if (hasReturn) {
        return structureOfBook;
    }

    return `
        <div class="col-xl-4 mb-3 ${(status == "book") ? "col-md-6" : "col-11 mx-auto mx-lg-0 col-lg-6"}">
            <div class="cartBooks mx-auto item p-3 card bg-primary-subtle text-primary-emphasis border-0 rounded" style="height: 100%; display: flex; justify-content: space-between;" data-id="${data.id ?? data.book_id}" >

                ${structureOfBook}                

            </div>
        </div>
    `;
}

function orderComponent(data, categoryName) {

    let buttons = "";

    if (auth["role"] == "admin" && categoryName === "ordered") {

        buttons = `
            <td>
                <div class="buttons">

                    <button onclick='mangeOrder('cancel', ${data["id"]}, ${data["customer_id"]})' class='btn btn-danger me-2'>
                        Cancel
                    </button>

                    <button onclick='mangeOrder('done', ${data["id"]}, ${data["customer_id"]})' class='btn btn-success'>
                        Done
                    </button>

                </div>
            </td>
        `;
    }


    return `
        <tr data-id="${data.id}">

            <th scope="row">${data.id}</th>
            <td>${data.customer_name}</td>
            <td>${data.total_price}</td>
            <td>
                <a href="#" onclick="getItemsToAddIntoCart(${data["id"]}, 'showOrder')">Show</a>
            </td>
            <td>${data.created_at}</td>
            ${buttons}
        </tr>
    `;
}

// another Tools

function selectedLanguageTypeToEdit($key, $inputValue) {
    return ($key == $inputValue) ? "selected" : "";
}

function changeSortingIcon(selectedValue) {
    $(`.sortIcon`).toggleClass("d-none");
}

function updateTotalNumberOfCart(totalPrice, totalItems) {
    $(".totalNumber").text(totalPrice);
    $("#cartQuantity").text(totalItems);
}