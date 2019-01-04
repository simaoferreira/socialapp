<?php
  require_once('settings.php');

  $file = $SETTINGS['main_db']->real_escape_string($_FILES['file']['name']);
  $name = $SETTINGS['server_images_path'] . $file;
  switch($_FILES['file']['type']){
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

    $sql = "UPDATE accounts SET avatar ='$file' WHERE email='$email'";
    if($SETTINGS['main_db']->query($sql)===True){ 
        move_uploaded_file($_FILES['file']['tmp_name'], $name);
        echo '<div id="snackbarGreen">Profile picture updated!</div>';
        echo '<script>$("#profilePic").attr("src", "'.$name.'");</script>';
        echo '<script>document.getElementById(\'fotoModal\').style.display=\'none\'</script>';
    }else{
        echo 'Something went wrong! Try again!';
    }
    
  }
?>