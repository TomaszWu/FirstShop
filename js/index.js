$(function () {
    var allCat = $('.snlgCat');
    console.log(allCat.length);
    for (var i = 0; i < allCat.length; i++) {
        var randomColor = '#' + (Math.random().toString(16) + "000000").substring(2,8);
        if(i%2 == 0){
            var color = '#F2F2F2';
        } else {
            var color = 'white';
        }
//        allCat[i].style.backgroundColor = color;
        allCat[i].style.paddingTop = '50px';
        allCat[i].style.height = '500px';
//        allCat[i].style.color = '#fff';
    }
});