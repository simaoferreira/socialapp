<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="animations.css" type="text/css">

        <style>
            .box {
                width:400px;
                height:400px;
                background-color:#aaaa00;
                margin:150px auto;
            }
        </style>
    </head>
        
    <body>
       <div class="animatedParent">
            <div class="animated bounceInDown box"></div>
        </div>
        
        <div class="animatedParent">
            <div class="animated fadeIn box"></div>
        </div>
        
        <div class="animatedParent">
            <div class="animated growIn box"></div>
        </div>
        
        <div class="animatedParent">
            <div class="animated bounceInRight box"></div>
        </div>
        
        <div class="animatedParent">
            <div class="animated fadeInDown box"></div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="css3-animate-it.js"></script>
    </body>
</html>