<?php  
  // Start session
  session_start();

  // Allowed screens list
  $allowed_screens = array("home","about");

  // some var
  $server_destiny_dir = "image_uploads/";
  // Check screen
  $currentScreen = "home";
  if(isset($_GET['screen'])){
    if(in_array($_GET['screen'], $allowed_screens)){
      $currentScreen = $_GET['screen'];
    }else{
      header("Location: ?screen=home");
    }
  }

  $_SESSION['messageRegisto']='';
  $_SESSION['messageLogin']='';
  $_SESSION['messageSnackbar']='';
  $_SESSION['profileImage']='';

  $mysqli = new mysqli('localhost','root','ovelha19?','users');

  if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  } 

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registo'])){

    //two passwords are equal to each other
    if ($_POST['password'] == $_POST['confirmPassword']){

      $firstname = $mysqli->real_escape_string($_POST['firstname']);
      $lastname = $mysqli->real_escape_string($_POST['lastname']);
      $email = $mysqli->real_escape_string($_POST['email']);
      $password = md5($_POST['password']); //md5 hash password security

      $sql = "INSERT INTO accounts (`Id`, `FirstName`, `LastName`, `Email`, `Password`, `avatar`) VALUES (NULL, '$firstname', '$lastname', '$email', '$password', 'default.png')";

      //if the query is sucessfull, redirect to welcome-php page,done!
      if($mysqli->query($sql)===True){
        $_SESSION['email'] = $email;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['messageRegisto']='Registration sucessfull!';
        $_SESSION['profileImage']= 'default.png';
        header("Location: ?screen=home");
      }else{
        $_SESSION['messageRegisto']='Registration not sucessfull!';
      }

    }else{
      $_SESSION['messageRegisto']='Passwords do not match!';
    }
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])){

    $email = $mysqli->real_escape_string($_POST['email']);
    $password = md5($_POST['password']);

    $sql = "SELECT * FROM accounts WHERE Email='$email' AND Password='$password'";
    $result = $mysqli->query($sql);

    if (!$result) {
      trigger_error('Invalid query: ' . $mysqli->error);
    } 
    $num_rows = $result->num_rows;

    if($num_rows > 0){
      $row = $result->fetch_assoc();
      $id = $row["Id"];
      $firstname = $row["FirstName"];
      $lastname = $row["LastName"];
      $email = $row["Email"];
      $profilePic = $row["Avatar"];

      $_SESSION['id'] = $id;
      $_SESSION['email'] = $email;
      $_SESSION['firstname'] = $firstname;
      $_SESSION['lastname'] = $lastname;
      $_SESSION['messageLogin']='Login sucessfull!';
      header("Location: ?screen=home");
      
    }else{
      $_SESSION['messageLogin']='email or pass incorrect!';
    }

  }

  if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['uploadProfilePicture'])){

    if($_FILES){
      $file = $mysqli->real_escape_string($_FILES['filename']['name']);
      $name = $server_destiny_dir . $file;
      switch($_FILES['filename']['type']){
        case 'image/jpeg':
          $ext = 'jpg';
          break;
        case 'image/gif':
          $ext = 'gif';
          break;
        case 'image/png':
          $ext = 'png';
          break;
        case 'image/tiff':
          $ext = 'tiff';
          break;
        default:
          $ext = '';
          break;
      }

      if($ext){
        $email = $_SESSION['email'];

        move_uploaded_file($_FILES['filename']['tmp_name'], $name);
        
        $sql = "UPDATE accounts SET avatar ='$file' WHERE Email='$email'";

        if($mysqli->query($sql)===True){ 
          echo '<script>alert("Image Updated!")</script>';
        }else{
          echo '<script>alert("Image not Updated!")</script>';
        }
      }else{
        echo '<script>alert("Invalid image type.")</script>';
      }
    }else{
      echo '<script>alert("An error has occured while changing your profile pic. Contact one SysAdmin.")</script>';
    }
  }
?>


<!DOCTYPE html>
<html>
<head>
	<title><?php print ucfirst($currentScreen); ?></title>
	<link rel="stylesheet" href="w3.css">
  <script src="index.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body class="w3-flat-belize-hole" style="animation: changeColor 50s infinite alternate;">




<div class="w3-top">
  <div class="w3-bar" id="myNavbar">
    <a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right" href="javascript:void(0);" onclick="toggleFunction()" title="Toggle Navigation Menu">
      <i class="fa fa-bars"></i>
    </a>
    <a href="?screen=home#main" class="w3-bar-item w3-button w3-hover-none w3-bottombar w3-hover-border-green">HOME</a>
    <a href="?screen=about#main" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green" <i class="fa fa-user"></i> ABOUT</a>
    <a href="#portfolio" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green" <i class="fa fa-th"></i> NEWS</a>
    <a href="#contact" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green" <i class="fa fa-envelope"></i> CONTACT</a>
    <a href="#" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-bottombar w3-hover-border-green"><i class="fa fa-search"></i>
    </a>
    <?php if(isset($_SESSION['email'])): ?>
    <a href="logout.php" class="w3-bar-item w3-button w3-hover-none w3-hide-small w3-right w3-bottombar w3-hover-border-green"><i class="fa fa-sign-in"></i></a>
  	<?php endif; ?>
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
    <a href="#about" class="w3-bar-item w3-button" onclick="toggleFunction()">ABOUT</a>
    <a href="#portfolio" class="w3-bar-item w3-button" onclick="toggleFunction()">NEWS</a>
    <a href="#contact" class="w3-bar-item w3-button" onclick="toggleFunction()">CONTACT</a>
    <a href="#" class="w3-bar-item w3-button">SEARCH</a>
    <?php if(isset($_SESSION['email'])): ?>
    <a href="logout.php" class="w3-bar-item w3-button w3-right w3-mobile"><i class="fa fa-sign-in"></i></a>
    <?php endif; ?>
  </div>
</div>

<div id="snackbar"><?= $_SESSION['messageSnackbar'] ?></div>

<div id="home">
  <div class="w3-display-middle" style="white-space:nowrap;">
    <span class="w3-center w3-padding-large w3-black w3-xxlarge w3-wide w3-animate-top" style="box-shadow: 5px 10px 10px 1px #202123;">OUR THEMES
    </span>
    <span class="w3-padding-large w3-black w3-large w3-wide w3-animate-top" style="box-shadow: 5px 10px 10px 1px #202123;"><?php print ucfirst($currentScreen); ?>
    </span>
    

  </div>
</div>

<div id="main">
<?php include("".$currentScreen.".php"); ?>

<?php if(isset($_SESSION['email'])): ?>
    <div class="w3-container w3-rest w3-margin-bottom" style="margin-top: 35px;">
      <h2>My Profile</h2>
        <div class="w3-margin-top w3-animate-left">
          <div class=" w3-half" style="width: 100%;">
            <div class="w3-card w3-display-container">
              <?php 
              $email = $_SESSION['email'];

              $sql = "SELECT * FROM accounts WHERE Email='$email'";
              $result = $mysqli->query($sql);
              $row = $result->fetch_assoc();

              if (!$result) {
                trigger_error('Invalid query: ' . $mysqli->error);
              }else{
                $userAvatar = $server_destiny_dir . $row['avatar'];
                echo '<img src="'.$userAvatar.'" alt="Person" style="width: 100%;height: 300px;object-fit: cover;object-position: center;">';
              }
              ?>
              <button class="w3-btn w3-block w3-teal w3-margin-bottom" onclick="document.getElementById('fotoModal').style.display='block'">Chose an image</button>
            </div>
            <div class=" w3-card w3-grey w3-container ">
              <h4><b><?= ucfirst($_SESSION['firstname']) ?> <?= ucfirst($_SESSION['lastname']) ?></b></h4>
              <h4>Age: </h4>
              <h4>Birthday Day: </h4>
              <div class="w3-row">
                <a target="_blank" href="dashboard.php" class=" w3-btn w3-teal w3-margin-bottom" style="width: 80%;">Dashboard</a>
                <a class="w3-rest w3-btn w3-teal w3-margin-bottom w3-right" style="margin-left: 2px;"><i class="fa fa-cog"></i></a>
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
          <input type="file" name="filename" size="10"/>
          <p>
            <label class="w3-text-red"><?= $_SESSION['messageSnackbar'] ?></label>
          <p>
          <input type="submit" name="uploadProfilePicture" id="uploadProfilePicture" class="w3-btn w3-teal" value="Upload"/>
        </form>        
      </div>
    </div>
  </div>



  <?php if(!isset($_SESSION['email'])): ?>
  <div class="w3-container w3-quarter" style="margin-top: 35px;">
    
    <div id="login" class="w3-display-container w3-margin borderForm w3-middle">
      <h2>Login</h2>
            <form method="post" class="w3-container w3-flat-belize-hole w3-card" action="?screen=home">
            <p>      
            <label class="w3-text-brown"><b>E-mail</b></label>
            <input class="w3-input w3-border w3-sand " name="email" type="email" required=""></p>
            <p>      
            <label class="w3-text-brown"><b>Password</b></label>
            <input class="w3-input w3-border w3-sand " name="password" type="password" required=""></p>
            <p>
            <label class="w3-text-red"><?= $_SESSION['messageLogin'] ?></label>
            <p>
            <button name="login" type="submit" class="w3-btn w3-brown ">Login</button></p>
            </form>
    </div>

    <br>
    <div id="registo" class="w3-display-container w3-margin">
        <h2>Registo</h2>
          <form method="post" class="w3-container w3-flat-belize-hole w3-card" action="?screen=home">
          <p>      
          <label class="w3-text-brown"><b>First Name</b></label>
          <input class="w3-input w3-border w3-sand" name="firstname" type="text" required="" 
          <?php if(isset($_POST['firstname'])){ echo "value='".$_POST['firstname']."'"; } ?>>
          </p>
          <p>      
          <label class="w3-text-brown"><b>Last Name</b></label>
          <input class="w3-input w3-border w3-sand" name="lastname" type="text" required="" 
          <?php if(isset($_POST['lastname'])){ echo "value='".$_POST['lastname']."'"; } ?>>
          </p>
          <p>
          <label class="w3-text-brown"><b>E-mail</b></label>
          <input class="w3-input w3-border w3-sand" name="email" type="email" required="" 
          <?php if(isset($_POST['email'])){ echo "value='".$_POST['email']."'"; } ?>>
          <p> 
          <label class="w3-text-brown"><b>Password</b></label>
          <input class="w3-input w3-border w3-sand" name="password" type="password" required=""></p>
          <p>  
          <label class="w3-text-brown"><b>Confirm Password</b></label>
          <input class="w3-input w3-border w3-sand" name="confirmPassword" type="password" required=""></p>
          <p>
          <label class="w3-text-red"><?= $_SESSION['messageRegisto'] ?></label>  
          <p>  
          <button name="registo" type="submit" class="w3-btn w3-brown">Register</button></p>
          </form>
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


</body>
</html>

<script>
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
</script>