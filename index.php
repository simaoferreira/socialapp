<!DOCTYPE html>
<?php
// Include the configurations file
require_once('settings.php');

// Check if the screen is valid
$_SESSION['current_screen'] = "home";
if(isset($_GET['screen'])){
  if(in_array($_GET['screen'], $SETTINGS['allowed_screens'])){
    $_SESSION['current_screen'] = $_GET['screen'];
  }else{
    header("Location: ?screen=" . $SETTINGS['default_screen']);
  }
}

// Reset session feedback variables
$_SESSION['registration_feedback'] = '';
$_SESSION['messageSnackbar'] = '';
$_SESSION['messageLogin'] = '';
$_SESSION['profileImage'] = '';

// Handles with user registration
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])){
  if(!isset($_POST['firstname']) || !isset($_POST['lastname']) || !isset($_POST['username']) || !isset($_POST['emailRegisto']) || !isset($_POST['password']) || !isset($_POST['confirmPassword'])){
    $_SESSION['registration_feedback'] = 'Please fill in all the required information.';
  }else if($_POST['password'] != $_POST['confirmPassword']){
    $_SESSION['registration_feedback'] = 'The provided passwords do NOT match.';
  }else if(strlen($_POST['firstname']) < 2 || strlen($_POST['firstname']) > 25){
    $_SESSION['registration_feedback']= 'You have provided an invalid First Name (2-25 caracters).';
  }else if(strlen($_POST['lastname']) < 2 || strlen($_POST['lastname']) > 25){
    $_SESSION['registration_feedback']= 'You have provided an invalid Last Name (2-25 caracters).';
  }else if(strlen($_POST['emailRegisto']) < 2 || strlen($_POST['emailRegisto']) > 100 || !filter_var($_POST['emailRegisto'], FILTER_VALIDATE_EMAIL)){
    $_SESSION['registration_feedback']= 'You have provided an invalid Email Address.';
  }else if(strlen($_POST['password']) < 6 || strlen($_POST['password']) > 20){
    $_SESSION['registration_feedback']= 'The password must have between 6 and 20 caracters.';
  }else{

    // Save information and sanitize input
    $firstName = validateUserInput($_POST['firstname'], $SETTINGS['main_db']);
    $lastName = validateUserInput($_POST['lastname'], $SETTINGS['main_db']);
    $username = validateUserInput($_POST['username'], $SETTINGS['main_db']);
    $email = validateUserInput($_POST['emailRegisto'], $SETTINGS['main_db']);
    $password = validateUserInput($_POST['password'], $SETTINGS['main_db']);
    $password = hash('ripemd128', $password);
    $session = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $email);

    // Build SQL query
    $sql = $SETTINGS['main_db']->prepare('INSERT INTO accounts (firstname, lastname, username, email, password, avatar, session) VALUES (?, ?, ?, ?, ?, DEFAULT(avatar), ?)');
    $sql->bind_param('ssssss', $firstName, $lastName, $username, $email, $password, $session);
    
    if($sql->execute()){ // execute query
      // Subject of confirmation email.
      $conf_subject = 'Registration Sucessfull!';

      // Who should the confirmation email be from?
      $conf_sender = 'Our Themes <no-reply@gmail.com>';

      $msg = $_POST['username'] . ",\n\nThank you for your registration. We hope we will have fun with us!";

      mail( $_POST['emailRegisto'], $conf_subject, $msg, 'From: ' . $conf_sender );

      $_SESSION['email'] = $email;
      $_SESSION['firstname'] = $firstName;
      $_SESSION['lastname'] = $lastName;
      $_SESSION['username'] = $username;
      $_SESSION['userAvatar'] = $SETTINGS['server_images_path'] . "default.png";
      $_SESSION['security_check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $email);
      $_SESSION['registrationFeedback']='Registration sucessfull!';
      $sql->close(); // close prepared statement object (say thanks and goodbye)   
      header("Location: ?screen=home"); // Redirect the user
    }else{
      $_SESSION['registrationFeedback']='Registration NOT successfull due to a database issue.';
      $sql->close(); // close prepared statement object (say thanks and goodbye)   
      header("Location: ?screen=home"); // Redirect the user
    } 
    
  }
}

  // Handles login form
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){
  if(!isset($_POST['usernameLogin']) || !isset($_POST['password'])){
    $_SESSION['registration_feedback'] = 'Please fill in all the required information.';
  }else{
    $username = validateUserInput($_POST['usernameLogin'], $SETTINGS['main_db']);
    $password = validateUserInput($_POST['password'], $SETTINGS['main_db']);
    loginUser($username, $password, $SETTINGS['main_db']);
  }
}

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="t.png">
	<title><?php print ucfirst($_SESSION['current_screen']); ?></title>
	<link rel="stylesheet" href="w3.css">
  <script src="index.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="animations.css" type="text/css">
  <script type="text/javascript" src="https://platform.linkedin.com/badges/js/profile.js" async defer></script>
</head>

<body class="w3-flat-belize-hole" style="animation: changeColor 50s infinite alternate;">

<div class="w3-top">
  <div class="w3-bar" id="myNavbar">
    <a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right"  onclick="toggleFunction()" title="Toggle Navigation Menu">
      <i class="fa fa-bars"></i>
    </a>
    <a href="?screen=home#main" class="w3-bar-item w3-button w3-hover-none w3-bottombar w3-hover-border-green">HOME</a>
    <a href="?screen=about#main" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green"> <i class="fa fa-user"></i> ABOUT</a>
    <a href="#portfolio" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green"> <i class="fa fa-th"></i> NEWS</a>
    <a href="#contact" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green"> <i class="fa fa-envelope"></i> CONTACT</a>
    <a class="w3-bar-item"><input class="w3-input w3-animate-input w3-bottombar w3-hover-border-green w3-border w3-sand" placeholder="Search..." type="text" style="margin-top:0px;width:50%;height: 35px;"></a>

    <?php if(isset($_SESSION['username'])): ?>
    <a href="logout.php" style="display:block;" id="buttonLogout" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-right w3-bottombar w3-hover-border-green"><i class="fa fa-sign-in"></i></a>
  	<?php endif; ?>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
    <a href="#about" class="w3-bar-item w3-button" onclick="toggleFunction()">ABOUT</a>
    <a href="#portfolio" class="w3-bar-item w3-button" onclick="toggleFunction()">NEWS</a>
    <a href="#contact" class="w3-bar-item w3-button" onclick="toggleFunction()">CONTACT</a>
    <a href="#" class="w3-bar-item w3-button">SEARCH</a>
    <?php if(isset($_SESSION['username'])): ?>
    <a href="logout.php" class="w3-bar-item w3-button w3-right w3-mobile"><i class="fa fa-sign-in"></i></a>
    <?php endif; ?>
  </div>
</div>

<div id="home" >
  <div class="w3-display-middle " style="white-space:nowrap;">
    <span class="w3-center w3-padding-large w3-black w3-xxlarge w3-wide " style="box-shadow: 5px 10px 10px 1px #202123;">OUR THEMES
    </span>
    <span class="w3-padding-large w3-black w3-large w3-wide" style="box-shadow: 5px 10px 10px 1px #202123;"><?php print ucfirst($_SESSION['current_screen']); ?>
    </span>
  </div>
</div>

<div id="main ">
<?php include("".$_SESSION['current_screen'].".php"); ?>

<?php if(isset($_SESSION['username'])): ?>
    <div class="w3-container w3-rest w3-margin-bottom" id="profile" style="display:block;margin-top: 35px;">
      <div class="animatedParent">
        <h2 class="animated bounceInRight go" >My Profile</h2>
      </div>
        <div class="w3-margin-top ">
          <div class=" w3-half " style="width: 100%;">
            <div class="animatedParent">
              <div class="w3-card w3-display-container slow animated bounceInRight go">
                <div class="">
                  <?php 
                  $username = $_SESSION['username'];

                  $sql = "SELECT * FROM accounts WHERE username='$username'";
                  $result = $SETTINGS['main_db']->query($sql);
                  $row = $result->fetch_assoc();

                  if (!$result) {
                    trigger_error('Invalid query: ' . $SETTINGS['main_db']->error);
                  }else{
                    $userAvatar = $SETTINGS['server_images_path'] . $row['avatar'];
                    $_SESSION['userAvatar'] = $userAvatar;
                  }
                  ?>
                  <img id="profilePic" src=<?= "'" .$_SESSION['userAvatar']. "'"?> alt="Person" style="width: 100%;height: 300px;object-fit: cover;object-position: center;">
                  <button class="w3-btn w3-block w3-teal w3-margin-bottom" onclick="document.getElementById('fotoModal').style.display='block'">Chose an image</button>
                </div>
              </div>
            </div>
            <div class="animatedParent">
              <div class=" w3-card w3-grey w3-container slower animated bounceInRight go">
                <h4 id="nameProfile"><b><?= ucfirst($_SESSION['firstname']) ?> <?= ucfirst($_SESSION['lastname']) ?></b></h4>
                <h4>Age: </h4>
                <h4>Birthday Day: </h4>
                <div class="w3-row">
                  <a target="_blank" href="dashboard.php" class=" w3-btn w3-teal w3-margin-bottom" style="width: 80%;">Dashboard</a>
                  <button class="w3-rest w3-btn w3-teal w3-right" style="margin-left: 2px;"><i class="fa fa-cog" ></i></button>
                </div>
                </div>
              </div>
            </div>
          </div> 
      </div>
    </div>
  <?php endif; ?>

  <div id="fotoModal" class="w3-modal">
    <div class="w3-modal-content">
      <div class="w3-container w3-grey">
        <span onclick="document.getElementById('fotoModal').style.display='none'" class="w3-btn w3-teal w3-display-topright">&times;</span>
        <h2>Upload new profile picture</h2>
        <form method="post" action='index.php' enctype='multipart/form-data'>
          File:
          <label style="cursor: pointer;" class="w3-btn w3-teal" for="filename">Browse...</label>
          <input type="file" name="filename" id="filename" style="opacity: 0;z-index: -1;" size="10"  accept="image/x-png,image/gif,image/jpeg"/>
          <p>
            <label id="labelSnackbar" class="w3-text-red"></label>
        </form>
        <div id="uploaded_image"></div>        
      </div>
    </div>
  </div>



  <?php if(!isset($_SESSION['username'])): ?>
  <div id="rightData" class="w3-container w3-quarter" style="display:block;margin-top: 35px;">
    
    <div class="animatedParent">
      <div id="login" class="w3-display-container w3-margin borderForm w3-middle animated bounceInRight go">
        <h2>Login</h2>
              <form method="post" id="loginForm" class="w3-container w3-flat-belize-hole w3-card" action="?screen=home#login">
              <p>
              <input class="w3-input costum-form" id="usernameLogin" name="usernameLogin" type="text" placeholder="Username" required="" autocomplete="off"></p>
              <p>
              <input class="w3-input costum-form" id="password" name="password" type="password" placeholder="Password" required="" autocomplete="off"></p>
              <p>
              <input class="w3-check" id="checkboxLogin" type="checkbox">
              <label>Remember me next time i come</label>
              <p>
              <label class="w3-text-red"><?= $_SESSION['messageLogin'] ?></label>
              <p>
              <button name="login" type="submit" class="w3-btn w3-brown ">Login</button></p>
              </form>
              <div id="login_submited"></div> 
      </div>
    </div>

    <br>
    <div class="animatedParent">
      <div id="registo" class="w3-display-container w3-margin slow animated bounceInRight go">
          <h2>Registo</h2>
            <form method="post" class="w3-container w3-flat-belize-hole w3-card" action="?screen=home#registo">
            <p>
            <input class="w3-input costum-form" name="firstname" placeholder="First Name" type="text" required="" autocomplete="off" 
            <?php if(isset($_POST['firstname'])){ echo "value='".$_POST['firstname']."'"; } ?>>
            </p>
            <p>
            <input class="w3-input costum-form" name="lastname" placeholder="Last Name" type="text" required="" autocomplete="off" 
            <?php if(isset($_POST['lastname'])){ echo "value='".$_POST['lastname']."'"; } ?>>
            </p>
            <p>
            <input class="w3-input costum-form" name="username" placeholder="Username" type="text" required="" autocomplete="off" 
            <?php if(isset($_POST['username'])){ echo "value='".$_POST['username']."'"; } ?>>
            </p>
            <select class="w3-select costum-form" id="optionGender" name="option" required="">
              <option value="" disabled selected>Choose your gender</option>
              <option value="1">Male</option>
              <option value="2">Female</option>
            </select>
            <p>
            <input class="w3-input costum-form" name="emailRegisto" placeholder="E-mail" type="email" required="" autocomplete="off" 
            <?php if(isset($_POST['emailRegisto'])){ echo "value='".$_POST['emailRegisto']."'"; } ?>>
            <p>
            <input class="w3-input costum-form" name="password" placeholder="Password" type="password" required="" autocomplete="off"></p>
            <p>
            <input class="w3-input costum-form" name="confirmPassword" placeholder="Confirm Password" type="password" required="" autocomplete="off"></p>
            <p>
            <label class="w3-text-red"><?php echo $_SESSION['registration_feedback']; ?></label>  
            <p>  
            <button name="register" type="submit" class="w3-btn w3-brown">Register</button></p>
            </form>
        </div>
      </div>
    

  </div>
  <?php endif; ?>
</div>
</div>

<footer class="w3-row w3-flat-belize-hole">
    <div class="w3-col w3-container">
      <h6 style="color: white;font-weight: bold;">&copy; 2018, RISING SEASONS COMPANY, ALL RIGHTS RESERVED</h6>
    </div>
</footer>

<?php if(isset($_SESSION['username'])): ?>
  <script>
    function dis(){
      xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","request.php",false);
      xmlhttp.send(null);
      document.getElementById("nameProfile").innerHTML=xmlhttp.responseText;
    }  
    dis();

    setInterval(function(){
      dis();
    },2000);



  </script>
<?php endif; ?>



<div id="snackbarGreen">Image uploaded</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="css3-animate-it.js"></script>

</body>
</html>

<script>

  function snackbar(id) {
    var x = document.getElementById(id)
    x.className = "show";
    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
  }
 
  $(document).ready(function(){

      $(document).on('change','#filename',function(){
        var property = document.getElementById("filename").files[0];
        var fullPath = document.getElementById("filename").value;
        var image_name = property.name;
        var image_extension = image_name.split('.').pop().toLowerCase();
        var image_size = property.size;
        if(jQuery.inArray(image_extension,['gif','png','jpg','jpeg']) == -1)
        {
          $("#labelSnackbar").text("Invalid image file!");
        }
        else if(image_size > 2000000)
        {
          $("#labelSnackbar").text("Image file size is very big! ");
        }else{
          var form_data = new FormData();
          form_data.append("file", property);
          $.ajax({
            url:"upload.php",
            method:"POST",
            data: form_data,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend:function(){
              $("#labelSnackbar").text("");
              $('#uploaded_image').html("<label class='text-success'>Uploading Image...</label>");
            },   
            success:function(data)
            {

              snackbar("snackbarGreen");
             $('#uploaded_image').html(data);
             $("#rightData, profile").hide();
            }
          });
        }
        
      });

    
});
</script>