$(function() {
    $(".lazy").lazyload({
        effect: "fadeIn"
    });
});

var contador = document.getElementsByClassName('counter');
console.log(contador);
var numerador = 1;
for (var i in contador) {
    contador[i].innerHTML = numerador;
    numerador++;
}
