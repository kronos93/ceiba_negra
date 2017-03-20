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

var $returntop = $("#returntop");
$(window).scroll(function(event) {
    var $returntop = $("#returntop");
    var scroll = $(window).scrollTop();
    if (scroll > 300) {
        $returntop.show("slow");
    } else {
        $returntop.hide("slow")
    }
});
$returntop.on("click", function(e) {
    $('html, body').animate({
        scrollTop: 100
    }, 2000);
});
$("#menu").on("click", "a", function(e) {
    var tag = $(this).attr("href");
    console.log(tag);
    $('html, body').animate({
        scrollTop: $(tag).offset().top
    }, 2000);
});
