$(function () {
    $('.chooseStatus').on('click', function (el) {
        $('#productList').children().remove();
        var status = $(el.target).attr('id');
        var elementWhereToAddAList = $(el.target);
        var tableToAdd = $('#orderList');
        $.ajax({
            url: 'api/adminOrdersManagment.php',
            type: 'GET',
            data: {statusToReceive: status},
            dataType: 'json',
            success: function (result) {
                $.each(result, function (orderId, userId) {

                    //display the key and value pair
                    console.log(' orderID: ' + orderId + ' UserId: ' + orderId);
                    // zamówienia  w koszyku nie mają jeszcze swojego nr zamówienia. 
                    if (status == 0) {
                        orderId = 'Zamówienie niepotwierdzone';
                    }
                    var tr = ('<tr class="single order" id="' + userId +
                            '"><td>' + orderId + '</td><td><select name="cars">\n\
<option value="1">1</option><option value="2">2</option>\n\
<option value="3">3</option></select></td><td>\n\
<button class="btn btn-warning changeStatus">Zmień status zamówienia</button>\n\
</td><td><button class="btn btn-danger delete">Usuń zamówienie</button></td>\n\
<td><button class="btn btn-info sendMsg" id="' + userId + '">Wyślij wiadomość</button></td></tr>\n\
');

                    $('tbody').append(tr);
                    // dlatego nie można zmienić im statusu :-)
                    if (status == 0) {
                        $('.changeStatus').addClass('disabled');
                    }

                });
                $('.changeStatus').on('click', function (el) {
                    var newStatus = $(el.target).parent().prev().children().val();
                    var orderId = $(el.target).parent().prev().prev().html();
                    $.ajax({
                        url: 'api/adminOrdersManagment.php',
                        type: 'PUT',
                        data: {newStatus: newStatus, orderId: orderId},
                        dataType: 'json',
                        success: function (result) {

                        },
                    })

                });

            },
            error: function () {
                console.log('Wystąpił błąd');
            }
        })

    })
})