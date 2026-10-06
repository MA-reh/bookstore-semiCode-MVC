function addAuthor(response) {
    // let message = response["message"];
    // let data = response["data"];
    // let status = response["status"];

    let { message, data, status } = returnDataOfResponse(response);


    if (status === 200) {
        resetPopupForm(data);

        let $rowOfDataPagination = $(`.categories-tabs[data-category="authors"] .row2`);

        removeLastElementIntoRowData("authors");

        $rowOfDataPagination.prepend(authorComponent(data, "authors"));
    }
}

function editAuthor(response) {
    let { message, data, status } = returnDataOfResponse(response);

    if (status === 200) {
        resetPopupForm(data);

        let cardHasBeenUpdated = $(`.categories-tabs[data-category="authors"] .row2 [data-id='${data["id"]}']`);

        let oldNameAuthorBooks = $(`.categories-tabs[data-category="books"] .row2 [data-author-id='${data["id"]}']`);


        oldNameAuthorBooks.text(data.name);

        cardHasBeenUpdated.html(authorComponent(data, "authors", true));
    }
}

function addBook(response) {
    let { message, data, status } = returnDataOfResponse(response);

    if (status == 200) {
        resetPopupForm(data);
        showAlerts(response);

        removeLastElementIntoRowData("books");

        insertingBooksIntoRow(1, data, "prepend");
    }
}

function banUser(userId, text, that) {
    banBtnForUser = that;

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: `Yes, ${text} it!`
    }).then((result) => {
        if (result.isConfirmed) {
            newRequestByAjax("profile/banUser", "POST", { "userId": userId }, userBanded, showAlerts);
        }
    });
}

function userBanded(response) {
    let { message, data, status } = returnDataOfResponse(response);

    if (status === 200) {

        let textBanned = (data["is_banned"]) ? "Ban" : "UnBan";

        let parentOfCategory = banBtnForUser.closest("[data-category]"),
            category = parentOfCategory.getAttribute("data-category");

        $(parentOfCategory).find(`[data-id="${data.id}"]`).html(userCardComponent(data, category, true));

        Swal.fire({
            title: `${textBanned}!`,
            text: "Your file has been deleted.",
            icon: "success"
        });
    }
}

function mangeOrder(status, orderId, userId) {
    let dataForm = {
        status: status,
        orderId: orderId,
        userId: userId,
    }

    newRequestByAjax("profile/mangeOrder", "POST", dataForm, function (response) {
        let { message, data, status } = returnDataOfResponse(response);
        let type;
        console.log(response);
        if (data["status"] == "canceled") {
            type = "canceled"
        } else {
            type = "done"
        }

        console.log(data);

        $(`[data-category="ordered"] table tbody tr[data-id="${data["order"]["id"]}"]`).remove();

        let tbodyForTableType = $(`[data-category="${type}"] table tbody`);

        tbodyForTableType.prepend(orderComponent(data["order"], type));

        if (tbodyForTableType.find("tr").length > NUMBER_OF_CARDS) {
            tbodyForTableType.find("tr").last().remove();
        }

        totalOrdersOrdered = totalOrdersOrdered - 1;

        closePopupNewData("popupDataModal");

        setTimeout(function () {
            showAlerts(response);
        }, 100);

    }, showAlerts);
}

function cancelOrderReason(typeInputs, orderId, customerId) {
    let footerPopup = $(`#popupDataModal .modal-footer`),
        bodyPopup = $(`#popupDataModal .modal-body`),
        popupModalLabel = $(`#popupDataModalLabel`);

    formPopupType = typeInputs;
    popupForm.setAttribute("data-type", formPopupType);

    popupModalLabel.text(`Cancel Order Reason`);
    bodyPopup.html(prepareInputsForPopups(typeInputs));

    footerPopup.html(`
        <button type="submit" class="btn btn-secondary me-2">Undo</button>
        <button onclick="mangeOrder('cancel', ${orderId}, ${customerId})" type="submit" class="btn btn-danger">Cancel</button>
        `);
}