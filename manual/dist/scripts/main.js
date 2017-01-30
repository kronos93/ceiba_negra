$(function() {
    console.log($.fn.lazyload);
    console.log($("img.lazy"));
    console.log($("img.lazy").lazyload());
    $("img.lazy").lazyload();
});

var contador = document.getElementsByClassName('counter');
console.log(contador);
var numerador = 1;
for (var i in contador) {
    contador[i].innerHTML = numerador;
    numerador++;
}
