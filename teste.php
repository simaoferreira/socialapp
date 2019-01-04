<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="w3.css">
</head>
<body>
<button class="w3-btn w3-block w3-teal w3-margin-bottom" onclick="document.getElementById('fotoModal').style.display='block'">Chose an image</button>
<div id="fotoModal" class="w3-modal">
    <div class="w3-modal-content">
      <div class="w3-container" style="background-color: #1f5fe0;">
        <span onclick="document.getElementById('fotoModal').style.display='none'" class="w3-btn w3-teal w3-display-topright">&times;</span>
        <h2>Upload new profile picture</h2>
        <form method="post" action='index.php' enctype='multipart/form-data'>
          File:
          <input type="file" name="filename" id="filename" size="10"/>
          <p>
            <label id="label" style="font-weight: bold" class="w3-text-red"></label>
          <p>
          <input type="submit" name="uploadProfilePicture" id="uploadProfilePicture" class="w3-btn w3-teal" value="Upload"/>
        </form>
        <span id="uploaded_image"></span>        
      </div>
    </div>
  </div>

</body>
</html>

<script>

$(document).ready(function(){
  $(document).on('change','#filename',function(){
    var property = document.getElementById("filename").files[0];
    var image_name = property.name;
    var image_extension = image_name.split('.').pop().toLowerCase();

    if(jQuery.inArray(image_extension,['gif','png','jpg','jpeg']) == -1)
    {
      $("label").text("Invalid image file");
    }else{
      $("label").text("You selected an image");
    }
    
  });
});
  
</script>