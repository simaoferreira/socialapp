<?php

require_once('settings.php');

$file = $SETTINGS['main_db']->real_escape_string($_FILES['photo']['name']);
$namePhoto = validateUserInput($_POST['namePic'], $SETTINGS['main_db']);
$description = validateUserInput($_POST['description'], $SETTINGS['main_db']);
$id = $_SESSION['id'];
$name = $SETTINGS['server_images_path'] . $file;
  switch($_FILES['photo']['type']){
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
    $date = time();
    // date('d/m/Y H:i:s', strtotime($time));

    $sql= $SETTINGS['main_db']->prepare('INSERT INTO pics (nome,title,description,publisher,datePublished,comment) VALUES (?, ?, ?, ?, ?, NULL)');
    $sql->bind_param('sssss', $file, $namePhoto, $description, $_SESSION['username'],$date);
    
    if($sql->execute()){ // execute query
    	move_uploaded_file($_FILES['photo']['tmp_name'], $name);

    	$stmt = $SETTINGS['main_db']->prepare("SELECT * FROM pics ORDER BY datePublished ASC");
	    if(!$stmt){
	        die($SETTINGS['main_db']->error);
	    }else{
	        $stmt->execute();
	        $result = $stmt->get_result();
	        echo '<script>alert("'.$result->num_rows.'")';
	        if($result->num_rows > 0){
		        while( $row = $result->fetch_assoc()){
		        	echo '<script>alert("'.$row['nome'].'")';
		        }
			}else{
				
			}
	        $stmt->close();  
	    }
  	}else{
  		echo '<script>$("#labelSnackbar").text("Something went wrong! Try again!")</script>';
  	}

  }
	

?>