$(function () {
    $.ajax({
        url: 'api/basketManagment.php',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            for (var i = 0; i < result.length; i++) {
                var order = JSON.parse(result[i]);
                console.log(order);
                var finalPrice = order.products.price * order.products.quantity;
                var row = ('<tr class="singleProduct" data-id="' + order.orderId + '"><th class="productName">'
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
            </th><th  class="itemPrice" id="finalPrice">Cena: ' + totalAmount + '</th><th></th></tr>');
            $('tbody').append(rowWithPrice);
            var confirmBtn = $('<button>').addClass('confirm btn btn-primary').html('Zamawiam!');
            $('#summary th:last-child').append(confirmBtn);
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
                    if (itemToCompare.orderId == idToCompare) {
                        $(el.target).attr('max', itemToCompare.products.stock);
                        var price = itemToCompare.products.price;
                        var newQnt = $(el.target).val();
                        var newPrice = newQnt * price;
                        $(el.target).parent().next().html(newPrice);
                        $.ajax({
                            url: 'api/basketManagment.php',
                            type: 'PUT',
                            data: {orderId: itemToCompare.orderId, newQnt: newQnt},
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
                finalPrice();

            }
        })
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
                    console.log(result['statusToConfirm']);
                })
                .fail(function () {
                    console.log('Wystąpił błąd2');
                });

    });
    $(document).on('click', '.confirm', function (event) {




    });


    function finalPrice() {
        var changedAmount = 0;
        var thsWithPrice = $('th.itemPrice');
        for (var j = 0; j < thsWithPrice.length - 1; j++) {
            var priceToConvert = parseFloat(thsWithPrice[j].innerHTML);
            changedAmount += priceToConvert;
            if (j == (thsWithPrice.length - 2)) {
                $('#finalPrice').html(changedAmount);
            }
        }
    }
});