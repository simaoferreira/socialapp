<div class="w3-container w3-margin-top w3-margin-bottom">
  <?php if(!isset($_SESSION['email'])) : ?>
    <div id="registo" class=" w3-card w3-row w3-grey" style="margin-left: 13.4%;width: 73.2%">
      <div class="w3-container">
        <h2 style="font-weight: bold;">Registo</h2>
      </div>
      <div class="w3-display-container w3-margin">
          <form method="post" class="w3-container w3-green" action="?screen=registo">
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
          </p>
          <p> 
          <label class="w3-text-brown"><b>Password</b></label>
          <input class="w3-input w3-border w3-sand" name="password" type="password" required=""></p>
          <p>  
          <label class="w3-text-brown"><b>Confirm Password</b></label>
          <input class="w3-input w3-border w3-sand" name="confirmPassword" type="password" required=""></p>
          <p>  
          <label class="w3-text-red"><?= $_SESSION['messageRegisto'] ?></label>
          <p>  
          <button name="registo" class="w3-btn w3-brown">Register</button></p>
          </form>
      </div>
    
    </div>
<?php endif; ?>
</div>  