<!DOCTYPE html>
<html>
<head>
	<title>OurThemes</title>
	<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<?php  

  $allowed_screens = array("index","login","register");

  session_start();
  $_SESSION['message']='';

  $mysqli = new mysqli('localhost','root','ovelha19?','users');

  if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //two passwords are equal to each other
    if ($_POST['password'] == $_POST['confirmPassword']){

      $firstname = $mysqli->real_escape_string($_POST['firstname']);
      $lastname = $mysqli->real_escape_string($_POST['lastname']);
      $email = $mysqli->real_escape_string($_POST['email']);
      $password = md5($_POST['password']); //nd5 hash password security

      

      $sql = "INSERT INTO `accounts` (`Id`, `FirstName`, `LastName`, `Email`, `Password`) VALUES (NULL, '$firstname', '$lastname', '$email', '&password')";

      //if the query is sucessfull, redirect to welcome-php page,done!
      if($mysqli->query($sql)===True){
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['message']='Registration sucessfull!';
      }else{
        $_SESSION['message']='Registration not sucessfull!';
      }

    }else{
      $_SESSION['message']='Passwords do not match!';
    }
  }

?>

<div class="w3-bar w3-light-grey w3-border w3-large" style="z-index: 6;">
  <a id="openNav" class="w3-bar-item w3-button w3-green " onclick="w3_open()">&#9776;<span id="bannerSidebar" class="w3-badge w3-margin-left w3-red">3</span></a>
  <a href="#" class="w3-bar-item w3-button"><i class="fa fa-home"></i></a>
  <a class="w3-bar-item w3-button""><i class="fa fa-search"></i></a>
  <a href="#" class="w3-bar-item w3-button"><i class="fa fa-envelope"></i></a>
  <a href="#" class="w3-bar-item w3-button"><i class="fa fa-globe"></i></a>
  <?php if(!isset($_SESSION['email'])): ?>
    <button id="openRegister" class="w3-bar-item w3-button w3-green" onclick="open_Register()">Register</button>
  <?php endif; ?>
  <a href="logout.php" class="w3-bar-item w3-button w3-right"><i class="fa fa-sign-in"></i></a>
  <?php if(isset($_SESSION['email'])): ?>
    <label class="w3-bar-item w3-right">Welcome, <?= $_SESSION['firstname'] ?></label>
  <?php endif; ?>
  
  
</div>

<div class="w3-sidebar w3-bar-block w3-card w3-animate-left" style="display:none;z-index: 5;" id="mySidebar">
  <div class="w3-row">
    <div class="w3-quarter"><button class="w3-rest w3-bar-item w3-button w3-large" onclick="w3_close()">&#9776;</button></div>
    <div class="w3-rest"><h6>OurThemes</h6></div>
  </div>
  <ul class="w3-ul">
    <li id = "login" class="w3-container w3-card-4 w3-animate-left">
      <div class="w3-display-container"><h5 style="font-weight: bold;">Login</h5><span onclick="animateLogin()" class="w3-button w3-transparent w3-display-right">&times;</span>
     </div>
      <div class="w3-display-container w3-margin">
            <form class="w3-container w3-green" action="/action_page.php">
            <p>      
            <label class="w3-text-brown"><b>First Name</b></label>
            <input class="w3-input w3-border w3-sand" name="first" type="text" required=""></p>
            <p>      
            <label class="w3-text-brown"><b>Last Name</b></label>
            <input class="w3-input w3-border w3-sand" name="last" type="text" required=""></p>
            <p>
            <label class="w3-text-brown"><?= $_SESSION['message'] ?></label>
            <button class="w3-btn w3-brown ">Register</button></p>
            </form>
      </div>
    </li>
    <hr id="hrlogin">
    <li id = "news" class="w3-container w3-card-4 w3-animate-left">
      <div class="w3-display-container"><h5 style="font-weight: bold;">News</h5><span onclick="animateNews()" class="w3-button w3-transparent w3-display-right">&times;</span>
      </div>
      <div class="w3-display-container w3-margin">
          Este é uma parte de noticias aiush eiwenjr weirunweirn ewijrnwuer iwnewij rjweo rjo rjho w rojhw erojh qrho rqowher oqhw eruq hwrehowq reoqhujw erjoh qweojr qwjohr eojqwh rojhwr ejoqhw rejoh qwejor qojwh erojh qjoh rejhq werjh qwjoern ojqwh erjohq weojrh qjow erjoqw er.
      </div>
    </li>
    <hr id="hrnews">
    <li id = "information" style="margin-bottom: 100px;" class="w3-container w3-card-4 w3-animate-left">
      <div class="w3-display-container"><h5 style="font-weight: bold;">Information</h5><span onclick="animateInformation()" class="w3-button w3-transparent w3-display-right">&times;</span>
      </div>
      <div class="w3-display-container w3-margin">
          Informação muito útil para os utentes e inje ijew we rihjerwiur wi ghweiuh saih aoih asoidh asidb iaus hdaoihd oih dqwhdiuqw hdiuqwh dbiuwbjkdbskjdf sdi hfiskjdfskjdfkjsb fkjsdbfkjbsdkjf kjsbfojbfo fgougf euogefoquwbg fqogyf oqg fquygfuyeqfbg qofqwoef qy gfoqwiuebfg wiuefg iuwqgeoifuqwfg qwui gfoquwig fiquwg fqiuw gfquig fqouigfq igfuq gfeqoiufg oiqf goqf.
      </div>
    </li>
  </ul>
</div>

<!-- Page Content -->
<div class="w3-overlay w3-animate-opacity" onclick="w3_close()" style="cursor:pointer" id="myOverlay"></div>

<div id="main">

<div class="w3-container">
  <?php if(!isset($_SESSION['email'])) : ?>
    <div id="registo" class=" w3-card w3-row w3-grey" style="margin-left: 13.4%;width: 73.2%">
    <div class="w3-container">
      <h2 style="font-weight: bold;">Registo</h2>
    </div>
    <div class="w3-display-container w3-margin">
          <form method="post" class="w3-container w3-green" action="?screen=home">
          <p>      
          <label class="w3-text-brown"><b>First Name</b></label>
          <input class="w3-input w3-border w3-sand" name="firstname" type="text" required=""></p>
          <p>      
          <label class="w3-text-brown"><b>Last Name</b></label>
          <input class="w3-input w3-border w3-sand" name="lastname" type="text" required=""></p>
          <p>
          <label class="w3-text-brown"><b>E-mail</b></label>
          <input class="w3-input w3-border w3-sand" name="email" type="text" required=""></p>
          <p> 
          <label class="w3-text-brown"><b>Password</b></label>
          <input class="w3-input w3-border w3-sand" name="password" type="password" required=""></p>
          <p>  
          <label class="w3-text-brown"><b>Confirm Password</b></label>
          <input class="w3-input w3-border w3-sand" name="confirmPassword" type="password" required=""></p>
          <p>  
          <label class="w3-text-red"><?= $_SESSION['message'] ?></label>
          <p>  
          <button class="w3-btn w3-brown">Register</button></p>
          </form>
    </div>
    
</div>
<?php endif; ?>
</div>



<footer class="w3-row" style="background-color: #459645;">
    <div class="w3-col w3-container">
      <h6 style="color: white;font-weight: bold;">&copy; 2018, RISING SEASONS COMPANY, ALL RIGHTS RESERVED</h6>
    </div>
</footer>


</div>

<script>
function w3_open() {
  document.getElementById("mySidebar").style.width = "25%";
  document.getElementById("mySidebar").style.display = "block";
  document.getElementById("myOverlay").style.display = "block";
  document.getElementById("openNav").style.display = 'none';
  document.getElementById("bannerSidebar").style.display = 'none';
}
function w3_close() {
  document.getElementById("main").style.marginLeft = "0%";
  document.getElementById("mySidebar").style.display = "none";
  document.getElementById("myOverlay").style.display = "none";
  document.getElementById("openNav").style.display = "inline-block";
}

function open_Register() {
  document.getElementById("mySidebarRegister").style.width = "25%";
  document.getElementById("mySidebarRegister").style.display = "inline";
  document.getElementById("openRegister").style.display = "none";
  
}

function animateLogin() {
    var element = document.getElementById("login");
    element.classList.add("w3-animate-fading");
    setTimeout(function() {
    document.getElementById('login').style.display = 'none'
    document.getElementById('hrlogin').style.display = 'none'
    }, 400);   
}

function animateNews() {
    var element = document.getElementById("news");
    element.classList.add("w3-animate-fading");
    setTimeout(function() {
    document.getElementById('news').style.display = 'none'
    document.getElementById('hrnews').style.display = 'none'
    }, 400);   
}

function animateInformation() {
    var element = document.getElementById("information");
    element.classList.add("w3-animate-fading");
    setTimeout(function() {
    document.getElementById('information').style.display = 'none'
    }, 400);   
}

</script>
	

</body>
</html>