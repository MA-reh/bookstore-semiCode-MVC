function addBookIntoCart(bookId, quantityOfBook) {
    quantityInput = $(`#input-quantity-${bookId}`);
    let quantity = Number(quantityInput.val());

    if (quantity <= 0) return;
    if (quantity > quantityOfBook) {
        showAlerts(
            {
                "status": 422,
                "message": "quantity of Book Not allowed",
            });
        return;
    }

    let dataForm = {
        bookId: bookId,
        quantity: quantity
    };

    newRequestByAjax("profile/addBookQuantity", "POST", dataForm, afterAddQuantityIntoCart, showAlerts);
}

function afterAddQuantityIntoCart(response) {
    let { message, data, status } = returnDataOfResponse(response);
    quantityInput.val("");

    $("#cartQuantity").text(data["totalItems"]);
    showAlerts(response, "toast");
}

function changeOrderItem(typeOfAction, orderItemId, that) {
    let dataForm = {
        typeOfAction: typeOfAction,
        orderItemId: orderItemId
    }

    let bookId = $(that).parents("[data-id]").attr("data-id"),
        inputValue = $(that).parent().find("input").val(),
        stockBook = $(`.categories-tabs[data-category="books"] [data-id="${bookId}"] .stockBook`).text();

    if (typeOfAction == "increase") {
        if (inputValue > stockBook) {
            showAlerts({
                status: 422,
                message: "Can't add Upper Stock The Book"
            }, "toast");
            return;
        }
    }

    $(that).parent().find(".changeOrderItem").attr("disabled", true);
    if (typeOfAction == "increase") {
        newRequestByAjax("profile/increaseOrderItem", "POST", dataForm, changeQuantityIntoCart, showAlerts);
    } else if (typeOfAction == "decrease") {
        newRequestByAjax("profile/decreaseOrderItem", "POST", dataForm, changeQuantityIntoCart, showAlerts);
    }

}

function changeQuantityIntoCart(response) {
    let { message, data, status } = returnDataOfResponse(response);
    let $bookItemIntoCart = $(`#popupCartModal .modal-body .data .card[data-id="${data["orderItem"]["book_id"]}"]`);
    let quantity = data["orderItem"]["quantity"];

    $bookItemIntoCart.find(".changeOrderItem").attr("disabled", false);

    updateTotalNumberOfCart(data["totalPrice"], data["totalItems"]);

    if (quantity == 0) {
        if (data["totalItems"] <= 0) {
            $bookItemIntoCart.parent().parent().delay(10).html(` <div class='col'>
                <h5 class='alert alert-warning text-center mt-2'>Your Cart Is Empty</h5>
                </div>`);
            $("#popupCartModal .modal-footer").addClass("d-none");
        }
        $bookItemIntoCart.parent().remove();
    } else {
        $bookItemIntoCart.find(`#input-quantity-${data["orderItem"]["book_id"]}`).val(quantity);
        $bookItemIntoCart.find(".new-subTotal").text(data["orderItem"]["subtotal"]);
    }


}

function deleteItemFromCart(orderItemId, that) {
    if (that.classList.contains("clicked")) return;

    let cardItem = $(that).parents(".card");

    cardItem.find(".changeOrderItem").attr("disabled", true);

    let dataForm = {
        orderItemId: orderItemId
    };

    that.classList.add("clicked");

    newRequestByAjax("profile/deleteOrderItem", "POST", dataForm,
        function (response) {
            let { message, data, status } = returnDataOfResponse(response);
            let footerModal = $("#popupCartModal .modal-footer");
            let rowOfData = $("#popupCartModal .modal-body .data");
            cardItem.parent().remove();
            updateTotalNumberOfCart(data["totalPrice"], data["totalItems"]);
            if (rowOfData.find(".card").length == 0) {
                rowOfData.append(`<div class='col'>
                                    <h5 class='alert alert-warning text-center mt-2'>Your Cart Is Empty</h5>
                                  </div>`);
                footerModal.addClass("d-none");
            }
        }
        , showAlerts);
}

function orderCustomerOrder(orderId) {
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Order it!"
    }).then((result) => {
        if (result.isConfirmed) {
            let dataForm = {
                orderId: orderId
            };

            newRequestByAjax("profile/orderCustomerOrder", "POST", dataForm,
                function (response) {
                    let { message, data, status } = returnDataOfResponse(response);
                    let footerModal = $("#popupCartModal .modal-footer");
                    let rowCards = $("#popupCartModal .modal-body .data");
                    let cards = rowCards.find(".card");
                    let tbodyForTableOrdered = $(`[data-category="ordered"] table tbody`);
                    closePopupNewData("popupCartModal");

                    console.log(data);

                    data["orderItem"]["books"].forEach(function (orderItem) {
                        let stockSlot = $(`.categories-tabs[data-category="books"] [data-id="${orderItem["book_id"]}"] .row3 .stockBook`);
                        let lastNumber = stockSlot.text();
                        let newNumberOfStock = lastNumber - orderItem["quantity"];

                        stockSlot.get(0).textContent = newNumberOfStock;
                    });

                    tbodyForTableOrdered.prepend(orderComponent(data["dataOrder"], "ordered"));

                    if (tbodyForTableOrdered.find("tr").length > NUMBER_OF_CARDS) {
                        tbodyForTableOrdered.find("tr").last().remove();
                    }

                    totalOrdersOrdered = totalOrdersOrdered + 1;

                    $(".totalPendingOrders").text(totalOrdersOrdered);

                    pagination("ordered", totalOrdersOrdered, 1);

                    console.log(response);
                    setTimeout(function () {
                        updateTotalNumberOfCart(0.00, 0);

                        cards.remove();

                        rowCards.html(`<div class='col'><h5 class='alert alert-warning text-center mt-2'>Your Cart Is Empty</h5></div>`);
                        footerModal.addClass("d-none");
                    }, 100);

                    showAlerts(response);

                }
                , showAlerts);
        }
    });
}
