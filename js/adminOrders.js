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
//                    console.log(' orderID: ' + orderId + ' UserId: ' + orderId);
                    // zamówienia  w koszyku nie mają jeszcze swojego nr zamówienia. 
                    if (status == 0) {
                        var infoAbuoutOrderId = 'Zamówienie niepotwierdzone';
                    } else {
                        infoAbuoutOrderId = orderId;
                    }
                    var tr = ('<tr class="single order" id="' + userId +
                            '" orderNumber="' + orderId + '"><td><span>' +
                            infoAbuoutOrderId + '</span></td><td><select name="cars">\n\
<option class="option1" value="1">1</option><option class="option2" value="2">2</option>\n\
<option class="option3" value="3">3</option></select></td><td>\n\
<button class="btn btn-warning changeStatus">Zmień status zamówienia</button>\n\
</td><td><button class="btn btn-danger delete">Usuń zamówienie</button></td>\n\
<td><button class="btn btn-info sendMsg" id="' + userId + '">Wyślij wiadomość</button></td></tr>\n\
');

                    $('tbody').append(tr);
                    // dlatego nie można zmienić im statusu :-)
                    if (status == 0) {
                        $('.changeStatus').addClass('disabled');
                    }

                    if (status == 2) {
                        $('.option2').prop("selected", "selected");
                    } else if (status == 3) {
                        $('.option3').prop("selected", "selected");
                    }


                });
                $('.changeStatus').on('click', function (el) {

                    var newStatus = $(el.target).parent().prev().children().val();
                    var orderId = $(el.target).parent().parent().attr('orderNumber');
                    console.log(orderId);
                    $.ajax({
                        url: 'api/adminOrdersManagment.php',
                        type: 'PUT',
                        data: {newStatus: newStatus, orderId: orderId},
                        dataType: 'json',
                        success: function (result) {
                            $(el.target).parent().parent().remove();
                        },
                    })

                });

            },
            error: function () {
                console.log('Wystąpił błąd');
            }
        })

    })


    $(document).on('click', '.delete', function (el) {
        var orderIdToDelete = $(el.target).parent().parent().attr('orderNumber');
        $.ajax({
            url: 'api/adminOrdersManagment.php',
            type: 'DELETE',
            data: {orderIdToDelete: orderIdToDelete},
            dataType: 'json',
            success: function () {
            },
//            error: function () {
//                // mimo że funckja kasuje pozyjcę to jednak zwraca ona błąd. Nie rozumiem
////                dlaczego
////                console.log('Wystąpił błąd');
//            }
        })
        $(el.target).parent().parent().remove();
    });
})