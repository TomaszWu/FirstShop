$(function () {
    
    var th = $('td:last-child');
    var sum = 0;
    for (var i = 0; i < th.length - 1; i++) {
        sum += parseFloat(th[i].innerHTML);
    }
    $('tbody tr:last-child td:last-child').html(sum);


});