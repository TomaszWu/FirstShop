$(function () {
    $.ajax({
        url: 'api/basketManagment.php',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            if (result.length == 0) {
            } else {
                for (var i = 0; i < result.length; i++) {
                    var order = JSON.parse(result[i]);
                    console.log(order);
                    var finalPrice = order.products.price * order.products.quantity;
                    var row = ('<tr class="singleProduct" data-id="' + order.id + '"><th class="productName">'
                            + order.products.product_name + '</th><th class="orderedQnt">\n\
<input type="number" min="1" id="changeTheQnt" value="' + order.products.quantity + '">\n\
</th><th  class="itemPrice">' + finalPrice + '</th><th><a href class="delete">Usuń z koszyka</th></tr>');
                    $('tbody').append(row);

                }
                var totalAmount = 0;
                var thsWithPrice = $('th.itemPrice');
                for (var i = 0; i < thsWithPrice.length; i++) {
                    var priceToConvert = parseFloat(thsWithPrice[i].innerHTML);
                    totalAmount += priceToConvert;
                }

                var rowWithPrice = ('<tr id="summary"><th></th><th> \n\
            </th><th id="finalPrice">Cena: ' + totalAmount + '</th><th></th></tr>');
                $('tbody').append(rowWithPrice);
                var confirmBtn = $('<button>').addClass('confirm btn btn-primary btn btn-info btn-lg').html('Zamawiam!').attr('data-toggle', 'modal').attr('data-target', '#myModal');
                $('#summary th:last-child').append(confirmBtn);

                var id = result['id'];
                var modalDiv = $('<div>').addClass('modal fade').attr('id', 'myModal').attr('role', 'dialog');
                var modalDialogDiv = $('<div>').addClass('modal-dialog');
                var modalContentDiv = $('<div>').addClass('modal-content');
                var modalHeaderDiv = $('<div>').addClass('modal-header');
                var h4WithTitle = $('<h4>').addClass('modal-title').html('Zamówienie zostało złożone');
                var modalBodyDiv = $('<div>').addClass('modal-body');
                var pWithInfo = $('<p>').addClass('orderNumber');
                var divFooter = $('<div>').addClass('modal-footer');
                var btnClose = $('<a href="index.php">').addClass('btn btn-default redirect').attr('data-dismiss', 'modal').html('Powrót na stronę główną');
//            var btnClose = $('<button>').addClass('btn btn-default').attr('data-dismiss', 'modal').html('Powrót na stronę główną');
                $('body').append(modalDiv.append(modalDialogDiv.append(modalContentDiv)));
                modalContentDiv.append(modalHeaderDiv.append(h4WithTitle));
                modalContentDiv.append(modalBodyDiv.append(pWithInfo));
                modalContentDiv.append(divFooter.append(btnClose));
            }
        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    });


    $(document).on('click', '#changeTheQnt', function (el) {
        $.ajax({
            url: 'api/basketManagment.php',
            type: 'GET',
            dataType: 'json',
            success: function (result) {

                var idToCompare = $(el.target).parent().parent().attr('data-id');
                for (var i = 0; i < result.length; i++) {
                    var itemToCompare = JSON.parse(result[i]);
                    console.log(itemToCompare);
                    if (itemToCompare.id == idToCompare) {
                        $(el.target).attr('max', itemToCompare.products.stock);
                        var price = itemToCompare.products.price;
                        var newQnt = $(el.target).val();
                        var newPrice = newQnt * price;
                        $(el.target).parent().next().html(newPrice);
                        $.ajax({
                            url: 'api/basketManagment.php',
                            type: 'PUT',
                            data: {orderId: itemToCompare.id, newQnt: newQnt},
                            dataType: 'json'
                        })
                                .done(function (result) {
                                    console.log(result['statusToConfirm']);
                                })
                                .fail(function () {
                                    console.log('Wystąpił błąd123');
                                });
                    }
                    ;
                }

            }
        })
    });
    console.log($('.redirect'));

    $(document).on('click', '.redirect', function () {
        window.location.href = "index.php";
    });


    $(document).on('click', '.delete', function (event) {
        event.preventDefault();
        event.stopImmediatePropagation();

        var idToDelete = $(this).parent().parent().attr('data-id');
        var thtoDelete = $(this).parent().parent().remove();
        finalPrice();
        $.ajax({
            url: 'api/basketManagment.php',
            type: 'DELETE',
            data: {idToDelete: idToDelete},
            dataType: 'json'
        })

                // tutaj to nie zwraca JSONa. Analogiczny zapis w POST działał. 

                .done(function (result) {
                    finalPrice();
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });


    });
    $(document).on('click', '.confirm', function (event) {

        $.ajax({
            url: 'api/basketManagment.php',
            type: 'PUT',
            data: {confirmTheBasket: 1},
            dataType: 'json'
        })
                .done(function (result) {
                    var orderNumber = result['orderId'];
                    $('.orderNumber').html('Numer zamówienia: ' + orderNumber);
                    var containerDiv = $('<div>').addClass('container');
                    $('.singleProduct').remove();
                    $('#finalPrice').html(null);
                    $('.confirm').remove();
                })
                .fail(function () {
                    console.log('Wystąpił błąd potwierdzenia');
                });


    });

    function finalPrice() {
        var changedAmount = 0;
        var thsWithPrice = $('th.itemPrice');
        console.log(thsWithPrice.length);
        if (thsWithPrice.length == 0) {
            $('#finalPrice').html(0);
            window.location.href = "index.php";
        } else {
            for (var j = 0; j < thsWithPrice.length; j++) {
                var priceToConvert = parseFloat(thsWithPrice[j].innerHTML);
                changedAmount += priceToConvert;
            }
            $('#finalPrice').html(changedAmount);
        }
    }
});