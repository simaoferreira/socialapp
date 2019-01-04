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

function toggleFunction() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else {
        x.className = x.className.replace(" w3-show", "");
    }
}



function changeStatus(){
     var border = document.getElementById("imageStatus").style.border;
    if (border == "3px solid rgb(244, 67, 54)") {
        $("#painel").css({'border' : '3px solid rgb(76, 175, 80)'});
    } else {
        $("#imageStatus").css({'border-color' : '3px solid rgb(244, 67, 54)'});
        
    }
}


function imgSize(){
        var myImg = document.querySelector("#profilePic");
        var realWidth = myImg.naturalWidth;
        var realHeight = myImg.naturalHeight;
        alert("Original width=" + realWidth + ", " + "Original height=" + realHeight);
    }