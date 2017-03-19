var contador = document.getElementsByClassName('counter');
console.log(contador);
var numerador = 1;
for (var i in contador) {
    contador[i].innerHTML = numerador;
    numerador++;
}





$(window).scroll(function(event) {
    var $returntop = $("#returntop");
    var scroll = $(window).scrollTop();
    if (scroll > 300) {
        $returntop.show("slow");
    }
});
$("#menu").on("click", "a", function(e) {
    var tag = $(this).attr("href");
    console.log(tag);
    $('html, body').animate({
        scrollTop: $(tag).offset().top
    }, 2000);
});
