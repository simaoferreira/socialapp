
$(window).load(function() {
    $(".loader").delay(5000).fadeOut(2000);
    $(".circulo").delay(5000).fadeOut(1000);
  })

$(document).ready(function() {
  alert("entrei");
  var elem = document.getElementById("progress");
  var width = 1;
  var id = setInterval(frame, 40);

  function frame() {
    if (width >= 100) {
      document.getElementById("progress").innerHTML = "Carregamento Concluido! Divirta-se!!!";
      document.getElementById("progress").style.left = "0%";
      $("#progress2").fadeOut(200);
      $(".circulo2").css({'border-color' : 'Transparent'})
      $(".circulo2").css({'width' : '100px'});
      $(".circulo2").css({'animation-duration' : '0s'});
      $(".circulo2").css({'border-radius' : '1%'});
      $(".circulo").css({'border-radius' : '1%'});
      $(".circulo").css({'width' : '250px'});
      $(".circulo").animate({'-webkit-transform' : '100deg'}, {duration : 300});
      $(".circulo").css({'border-color' : '#ecac31'});
      $(".circulo").css({'border-top-color' : '#551a04'});
      $(".circulo").delay(100).css({'border-right-color' : '#551a04'});
      $(".circulo").delay(200).css({'border-bottom-color' : '#551a04'});
      $(".circulo").delay(300).css({'border-left-color' : '#551a04'});
      $(".circulo").css({'animation-duration' : '0s'});
      clearInterval(id);
    } else {
      width++;
      document.getElementById("progress").innerHTML = width + '%';
    }
  }
});