<!-- To do: Check if input values are valid
			- phone number: number and exactly 10 digits 
			- fix quotation input 
-->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to mysite</title>
	<script src="../js/editProfile.js"></script>
    </head>
<body>
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 <?php

 if(isset($_POST['updateBtn']) && isset($_SESSION['ID'])){
  // include database connection
    include_once 'config/connection.php'; 
	
	//Check if input values are valid

	$query = "UPDATE member SET password=?,first_name=?,last_name=?,phone_number=?,bio=? WHERE ID=?";
 
	$stmt = $con->prepare($query);
	$stmt->bind_param('ssssss', $_POST['password'], $_POST['first_name'], $_POST['last_name'],$_POST['phone_number'],$_POST['bio'],$_SESSION['ID']);
	// Execute the query
		if($stmt->execute()){
			echo "Record was updated. <br/>";
		}else{
			echo 'Unable to update record. Please try again. <br/>';
		}
 }
 
 ?>
  <?php
if(isset($_SESSION['ID'])){
   // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT email, password, first_name, last_name, phone_number, bio FROM member WHERE id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['ID']);

        // Execute the query
		$stmt->execute();
		
 
		// results 
		$result = $stmt->get_result();
		
		// Row data
		$myrow = $result->fetch_assoc();
		
} else {
	//User is not logged in. Redirect the browser to the login index.php page and kill this page.
	header("Location: index.php");
	die();
}

?>
 <?php include 'menubar.php' ?><br>
  Welcome  <?php echo $myrow['first_name']; ?>, <br>
<!-- dynamic content will be here -->
<form data-toggle="validator" role="form" name='editProfile' id='editProfile' action='editProfile.php' method='post'>
	<div class="form-group">
		<label>E-mail</label>
		<input type='text' name='email' id='email' disabled  value="<?php echo $myrow['email']; ?>"  />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Password</label>
		<input type='text' name='password' id='password' required  value="<?php echo $myrow['password']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>First Name</label>
		<input type='text' name='first_name' id='first_name' required  value="<?php echo $myrow['first_name']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Last Name</label>
		<input type='text' name='last_name' id='last_name' required  value="<?php echo $myrow['last_name']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Phone Number<label></label>
		<input type='number_format' name='phone_number' required id='phone_number' value="<?php echo $myrow['phone_number']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>About Me</label>
		<textarea rows ="8" cols="50" name='bio' id='bio'  /><?php echo $myrow['bio']; ?></textarea>
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<input type='submit' name='updateBtn' id='updateBtn' value='Update' /> 
		<button type="button" onclick="javascript:window.location='profile.php';" formnovalidate >Cancel</button>
		<div class="help-block with-errors"></div>
	</div>
	
</form>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="dist/validator.min.js"></script>
</body>
</html>