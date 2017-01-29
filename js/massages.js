$(function () {

    $.ajax({
        url: 'api/massagesInfo.php',
        type: 'GET',
        dataType: 'json',
        success: function (result) {
            for (var i = 0; i < result.length; i++) {
                console.log(result[i]);
            }
        },
        error: function () {
            console.log('Wystąpił błąd');
        }
    })
    
    $('.showMsg').on('click', function(el){
        var msgId = $(el.target).attr('id');
        
        $.ajax({
            url: 'api/massagesInfo.php',
            type: 'GET',
            data: {msgId: msgId},
            dataType: 'json'
        })
                .done(function (result) {
                })
                .fail(function () {
                    console.log('Wystąpił błąd');
                });
    })
    
})