// Change style of navbar on scroll
window.onscroll = function() {myFunction()};
function myFunction() {
    var navbar = document.getElementById("myNavbar");
    if (document.body.scrollTop > 600 || document.documentElement.scrollTop > 600) {
        navbar.className = "w3-bar" + " w3-card" + " w3-animate-top" + " w3-flat-belize-hole";
    } else {
        navbar.className = navbar.className.replace(" w3-card w3-animate-top w3-flat-belize-hole", "");
    }
}

function snackbar() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 6000);
}

$(document).ready(function(){
  $('#uploadProfilePicture').click(function(){
    var image_name = $('#image').val();
    if(image_name == ''){
      alert("Please Select Image");
      return false;
    }else{
      var extension = $('#image').val().split('.').pop().toLowerCase();
      if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1){
        alert('Invalid Image File');
        $('#image').val('');
        return false;
      }
    }

  });
});