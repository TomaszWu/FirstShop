$(function () {
    $.ajax({
        url: 'api/basketManagment.php',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            for (var i = 0; i < result.length; i++) {
                var order = JSON.parse(result[i]);
                console.log(order.products);
                var finalPrice = order.products.price * order.products.quantity;
                var row = ('<tr class="singleProduct" id="' + order.orderId + '"><th class="productName">'
                        + order.products.product_name + '</th><th class="orderedQnt">\n\
<input type="number" min="1" id="changeTheQnt" value="' + order.products.quantity + '">\n\
</th><th  class="itemPrice">' + finalPrice + '</th><</tr>');
                $('tbody').append(row);

            }
            var totalAmount = 0;
            var thsWithPrice = $('th.itemPrice');
            for (var i = 0; i < thsWithPrice.length; i++) {
                var priceToConvert = parseFloat(thsWithPrice[i].innerHTML);
                totalAmount += priceToConvert;
            }

            var rowWithPrice = ('<tr><th></th><th> \n\
            </th><th  class="itemPrice" id="finalPrice">Cena: ' + totalAmount + '</th><</tr>');
            $('tbody').append(rowWithPrice);

        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    });

    $(document).on('click', 'th.itemPrice', function (event) {
        console.log('tak');
        var test = $('itemPrice');
        console.log(test);
    });

    $(document).on('click', '#changeTheQnt', function (el) {
        $.ajax({
            url: 'api/basketManagment.php',
            type: 'GET',
            dataType: 'json',
            success: function (result) {

                var idToCompare = $(el.target).parent().parent().attr('id');
                console.log(idToCompare);
                for (var i = 0; i < result.length; i++) {
                    var itemToCompare = JSON.parse(result[i]);
                    if (itemToCompare.itemId == idToCompare) {
                        var price = itemToCompare.itemPrice;
                        var newQnt = $(el.target).val();
                        var newPrice = intval(newQnt) * intval(price);
                        $(el.target).parent().next().html(newPrice);
                        console.log(newPrice);

                    }
                    ;
                }
                var changedAmount = 0;
                var thsWithPrice = $('th.itemPrice');
                for (var j = 0; j < thsWithPrice.length - 1; j++) {
                    var priceToConvert = parseFloat(thsWithPrice[j].innerHTML);
                    changedAmount += priceToConvert;
                    if (j == (thsWithPrice.length - 2)) {
                        console.log(changedAmount);
                        $('#finalPrice').html(changedAmount);
                    }
                }

            }


        })
    });
});