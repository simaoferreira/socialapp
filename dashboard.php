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

?>

<!DOCTYPE html>
<html>
<head>
	<title>My Dahsboard</title>
	<link rel="icon" href="t.png">
	<link rel="stylesheet" href="w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  	<link rel="stylesheet" href="animations.css" type="text/css">
  	

</head>
<body class="w3-flat-belize-hole" style="animation: changeColor 50s infinite alternate;">

<div class="w3-row">
  <div class="chat animatedParent ">
  	<div class="w3-card w3-white animated bounceInLeft" style="margin-top:10px;margin-bottom: 10px;">
		  	<div class="w3-cell" style="width:70px;height:70px;">
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
			  <img src=<?= "'" .$_SESSION['userAvatar']. "'"?> id="imageStatus" onclick="changeStatus()" class="w3-bar-item w3-hide-small"  style="width: 70px;height: 70px;object-fit: cover;object-position: center;;border: 3px solid #f44336;">
			</div>

			<div class=" w3-rest w3-cell w3-cell-middle " style="padding-left: 3%;width: 100%;">
				
		  		<div style="font-size: 17px;">
		  			<u>Simão Ferreira</u>
				</div>

				<div style="font-size: 12px;">
				  Updating our site! :D
				</div>
				
			</div>


		</div>


	<div class="w3-card w3-white slow animated bounceInLeft" style="height: 95%;">

	</div>
  
  </div>

  <div class=" w3-container dashboard"><h3 class="w3-center" style="color: black;font-weight: bold">All Themes</h3>
  	<form method="post" action='index.php' enctype='multipart/form-data' style="">
          <label style="cursor: pointer;" class="w3-btn w3-teal" for="filename">Public new photo</label>
          <input type="file" name="filename" id="filename" onclick="document.getElementById('imageToUpload').style.display='block'" onchange="readURL(this);" style="opacity: 0;z-index: -1;" size="10"  accept="image/x-png,image/gif,image/jpeg"/>
          <p>
    </form>
    

    <div id="fotoModal" class="w3-modal">
	    <div class="w3-modal-content">
	      <div class=" w3-grey">
	        <span onclick="document.getElementById('fotoModal').style.display='none'" class="w3-btn w3-teal w3-display-topright">&times;</span>
	        	<div class="w3-row">
				  <div class= "w3-red w3-half" style="width: 50%;">
				    <img id="imageToUpload" src="image_uploads/new.png" alt="your image" style="display:none;width: 100%; height:400px;object-fit: cover;object-position: center;"/>
				  </div>

				  <div class=" w3-container w3-green w3-rest" style="height: 400px;">
				    <h2>Details:</h2>
				   	<form method="post" action='dashboard.php' enctype='multipart/form-data'>
				   	  <p>
			          <input class="w3-bottombar" style="background-color: transparent;outline: none;border:none;" id="namePhoto" name="namePhoto" type="text" placeholder="Name..."  autocomplete="off">
			      	  </p>
			      	  <p>
			          <textarea style="background-color: transparent;outline:none;border:solid 3px #ccc;" id="descriptionPhoto" name="descriptionPhoto" type="text" placeholder="Simple description..."  autocomplete="off"></textarea>
			          <p>
			            <button name="share" type="submit" class="w3-btn w3-brown ">Share</button>
			          </p>
			          <p>
            			<label id="labelSnackbar" class="w3-text-red"></label>
			        </form>
			        <div id="share_image"></div> 
				    </p>
				  </div>
				</div>    
	      </div>
	    </div>
	  </div>

	  <?php

	  	$stmt = $SETTINGS['main_db']->prepare("SELECT * FROM pics ORDER BY datePublished DESC");
	    if(!$stmt){
	        die($SETTINGS['main_db']->error);
	    }else{
	        $stmt->execute();
	        $result = $stmt->get_result();
	        if($result->num_rows > 0){
		        while( $row = $result->fetch_assoc()){
		        	echo '<div class="animatedParent">';
		        	echo '<div class="w3-container w3-white w3-card w3-block animated bounceInLeft" style="margin-top:10px;margin-bottom:20px;width: 100%;">';
		        	echo '<div class="w3-display-container" style="height:50px;">';
		        	echo '<h5 class="w3-display-left" style="margin-top:0px;font-weight: bold;">'.$row['publisher'].'</h5>';
		        	echo '<div class="w3-display-right"><img id="natureIcon" class="" src="image_uploads/nature.png" alt="your image" style="width: 30px;height: 30px;object-fit: cover;object-position: center;"/>(nature)</div>';
		        	echo '</div>';
		        	echo '<img id="profilePic" src="image_uploads/'.$row['nome'].'" alt="Person" style="width: 100%;">';
		        	echo '<div class="w3-bar">
	  			<div class="w3-bar-item">Published 20 mins ago</div>
	  			<div class="w3-bar-item w3-right"><a><u>Like</u></a> <a><u>Share</u></a></div>
			</div>
		</div>
	</div>';
		        }
			}else{
				
			}
	        $stmt->close();  
	    }


	  ?>

    <!--===================================================================================================================   
    <div class="animatedParent">
	  	<div class="w3-container w3-white w3-card w3-block animated bounceInLeft" style="margin-top:10px;margin-bottom:20px;width: 100%;">
			<div class="w3-display-container" style="height:50px;">
				<h5 class="w3-display-left" style="margin-top:0px;font-weight: bold;">Simão Ferreira</h5>
				<div class="w3-display-right"><img id="natureIcon" class="" src="image_uploads/nature.png" alt="your image" style="width: 30px;height: 30px;object-fit: cover;object-position: center;"/>(nature)</div>
			</div>
			<img id="profilePic" src="image_uploads/snow.jpg" onclick="document.getElementById('picture').style.display='block'" alt="Person" style="width: 100%;">

			<div id="picture" class="w3-modal" onclick="this.style.display='none'">
			    <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
			    <div class="w3-modal-content w3-animate-zoom">
			    	<img src="image_uploads/snow.jpg" class=".img-responsive">
			    </div>
			 </div>

			<div class="w3-bar">
	  			<div class="w3-bar-item">Published 20 mins ago</div>
	  			<div class="w3-bar-item w3-right"><a><u>Like</u></a> <a><u>Share</u></a></div>
			</div>
		</div>
	</div>

	===================================================================================================================  
	<div class="animatedParent">
		<div class="w3-container  w3-white w3-card w3-block animated bounceInDown" style="margin-top:10px;margin-bottom:20px;width: 100%;">
			<div class="w3-display-container" style="height:50px;">
				<h5 class="w3-display-left" style="margin-top:0px;font-weight: bold;">Simão Ferreira</h5>
				<div class="w3-display-right"><img id="natureIcon" class="" src="image_uploads/nature.png" alt="your image" style="width: 30px;height: 30px;object-fit: cover;object-position: center;"/>(nature)</div>
			</div>
			<img id="profilePic" src="image_uploads/snow.jpg" onclick="document.getElementById('picture1').style.display='block'" alt="Person" style="width: 100%;">

			<div id="picture1" class="w3-modal" onclick="this.style.display='none'">
			    <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
			    <div class="w3-modal-content w3-animate-zoom">
			    	<img src="image_uploads/snow.jpg" class=".img-responsive">
			    </div>
			 </div>

			<div class="w3-bar">
	  			<div class="w3-bar-item">Published 20 mins ago</div>
	  			<div class="w3-bar-item w3-right"><a><u>Like</u></a> <a><u>Share</u></a></div>
			</div>
		</div>
	</div>

	===================================================================================================================
	<div class="animatedParent">
		<div class="w3-container w3-white w3-card w3-block animated fadeInDown" style="margin-top:10px;margin-bottom:20px;width: 100%;">
			<div class="w3-display-container" style="height:50px;">
				<h5 class="w3-display-left" style="margin-top:0px;font-weight: bold;">Simão Ferreira</h5>
				<div class="w3-display-right"><img id="natureIcon" class="" src="image_uploads/nature.png" alt="your image" style="width: 30px;height: 30px;object-fit: cover;object-position: center;"/>(nature)</div>
			</div>
			<img id="profilePic" src="image_uploads/snow.jpg" onclick="document.getElementById('picture2').style.display='block'" alt="Person" style="width: 100%;">

			<div id="picture2" class="w3-modal" onclick="this.style.display='none'">
			    <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
			    <div class="w3-modal-content w3-animate-zoom">
			    	<img src="image_uploads/snow.jpg" class=".img-responsive">
			    </div>
			 </div>

			<div class="w3-bar">
	  			<div class="w3-bar-item">Published 20 mins ago</div>
	  			<div class="w3-bar-item w3-right"><a><u>Like</u></a> <a><u>Share</u></a></div>
			</div>
		</div>
	</div>

	===================================================================================================================
	<div class="animatedParent">
		<div class="w3-container  w3-white w3-card w3-block animated bounceInRight" style="margin-top:10px;margin-bottom:20px;width: 100%;">
			<div class="w3-display-container" style="height:50px;">
				<h5 class="w3-display-left" style="margin-top:0px;font-weight: bold;">Simão Ferreira</h5>
				<div class="w3-display-right"><img id="personIcon" class="" src="image_uploads/person.png" alt="your image" style="width: 30px;height: 30px;object-fit: cover;object-position: center;"/>(Personal life)</div>
			</div>
			<img id="profilePic" src="image_uploads/me.jpg" onclick="document.getElementById('picture3').style.display='block'" alt="Person" style="width: 100%;">

			<div id="picture3" class="w3-modal" onclick="this.style.display='none'">
			    <span class="w3-button w3-hover-red w3-xlarge w3-display-topright">&times;</span>
			    <div class="w3-modal-content w3-animate-zoom">
			    	<img src="image_uploads/me.jpg" class=".img-responsive">
			    </div>
			 </div>

			<div class="w3-bar">
	  			<div class="w3-bar-item">Published 20 mins ago</div>
	  			<div class="w3-bar-item w3-right"><a><u>Like</u></a> <a><u>Share</u></a></div>
			</div>
		</div>
	</div>

	===================================================================================================================--> 
	<div class="animatedParent">
		<footer class="w3-flat-belize-hole w3-quarter animated bounceInDown" style="width: 100%;margin-top: 10px;">
	    	<div class="w3-col w3-container">
	      		<h6 style="color: white;font-weight: bold;">&copy; 2018, RISING SEASONS COMPANY, ALL RIGHTS RESERVED</h6>
	    	</div>
		</footer>
	</div>
  </div>
        
  <div class="w3-center themes animatedParent">
  	
  	<div class=" w3-card w3-white animated bounceInRight" style="height: 95%;margin-top: 10px;">
  	


	</div>

  </div>

</div>



		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="css3-animate-it.js"></script>
</body>
</html>

<script>
	function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
		            $('#imageToUpload')
		                .attr('src', e.target.result)
				document.getElementById("namePhoto").value = '';
				document.getElementById("descriptionPhoto").value = '';
		        document.getElementById('fotoModal').style.display='block';
		        };

		        reader.readAsDataURL(input.files[0]);
		    }
	}

		$('form').submit(function(event){
			event.preventDefault();
			var namePhoto = document.getElementById("namePhoto").value;
			var description = document.getElementById("descriptionPhoto").value;
			var photo = document.getElementById("filename").files[0];
			
			if(namePhoto == '' || description == ''){
				$("#labelSnackbar").text("Please fill in all the required information.");

			}else{
				$("#labelSnackbar").text("");
				var form_data = new FormData();
	          	form_data.append('namePic', namePhoto);
	          	form_data.append('description', description);
	          	form_data.append('photo',photo);
				$.ajax({
					url:"share.php",
		            method:"post",
		            data: form_data,
		            contentType: false,
		            cache: false,
		            processData: false,
					beforeSend:function(){
		              $("#labelSnackbar").text("");
		            },   
		            success:function(data)
		            {
		            	$('#share_image').html(data);
		            }
				});
			}
			
		
		
		});
	

	


</script>



