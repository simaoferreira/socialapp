<!DOCTYPE html>
<html>
<head>
<style>
#snackbar {
    visibility: hidden;
    min-width: 250px;
    margin-left: -125px;
    background-color: #ddffdd;
    border: 2px solid #4CAF50;
    color: Black;
    text-align: center;
    border-radius: 23px;
    padding: 16px;
    position: fixed;
    z-index: 1;
    left: 50%;
    top: 30px;
    font-size: 17px;

}

#snackbar.show {
    visibility: visible;
    -webkit-animation: fadein 0.5s, fadeout 0.5s 5.5s;
    animation: fadein 0.5s, fadeout 0.5s 5.5s;
}

@-webkit-keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 30px; opacity: 1;}
}

@keyframes fadein {
    from {top: 0; opacity: 0;}
    to {top: 30px; opacity: 1;}
}

@-webkit-keyframes fadeout {
    from {top: 30px; opacity: 1;}
    to {top: 0; opacity: 0;}
}

@keyframes fadeout {
    from {top: 30px; opacity: 1;}
    to {top: 0; opacity: 0;}
}
</style>
</head>
<body>

<h2>Snackbar / Toast</h2>
<p>Snackbars are often used as a tooltips/popups to show a message at the bottom of the screen.</p>
<p>Click on the button to show the snackbar. It will disappear after 3 seconds.</p>

<button onclick="myFunction()">Show Snackbar</button>

<div id="snackbar">Some text some message..</div>

<script>
function myFunction() {
    var x = document.getElementById("snackbar")
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 6000);
}
</script>

</body>
</html>