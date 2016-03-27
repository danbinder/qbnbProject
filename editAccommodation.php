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

	$query = "UPDATE property SET type=?,price=?,availability=? WHERE property_id=?";
 
	$stmt = $con->prepare($query);
	$stmt->bind_param('ssss', $_POST['type'], $_POST['price'], $_POST['availability'],$_SESSION['property_id']);
	// Execute the query
		if($stmt->execute()){
			echo "Record was updated. <br>";
		}else{
			echo 'Unable to update record. Please try again. <br>';
		}
 }
 
 ?>
 <?php 
	// check if user is logged in 
	session_start();
	if(isset($_SESSION['ID'])){
    include_once 'config/connection.php'; 
        $query = "SELECT first_name, user_type FROM member WHERE id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_Param("s", $_SESSION['ID']);
		$stmt->execute();
		$result = $stmt->get_result();
		$myrow = $result->fetch_assoc();
	} else {
		//User is not logged in. Redirect the browser to the login index.php page and kill this page.
		header("Location: index.php");
		die();
	}
	
	// display menu bar
	$user_type = $myrow['user_type'];
	if($user_type == 'member'){
		include 'menubar.php';
	} else {
		include 'menubarAdmin.php';
	}
 ?>
 
 Logged in as:  <?php echo $myrow['first_name']; ?><br>
 
 <?php
$property_id = $_SESSION['pid'];

$searchQuery = "SELECT address, district, type, price, supplier_id, guests, bedrooms, bathrooms, beds, breakfast,
				pool, others FROM property NATURAL JOIN features WHERE property_id=3";

$result = $con->query($searchQuery);
$row = mysqli_fetch_array($result);
echo $row['address'] . $row['district'] . $row['type'];

 ?>
<!-- dynamic content will be here -->
<form data-toggle="validator" role="form" name='editAccommodation' id='editAccommodation' action='editAccommodation.php' method='post'>
	<div class="form-group">
		<label>Address</label>
		<input type='text' name='address' id='address' disabled  value="<?php echo $myrow['address']; ?>"  />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Type</label>
		<input type='text' name='type' id='type' required  value="<?php echo $myrow['type']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Price</label>
		<input type='number' name='price' id='price' required  value="<?php echo $myrow['price']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Availability</label>
		<input type='text' name='avail' id='avail' required  value="<?php echo $myrow['availability']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<input type='submit' name='updateBtn' id='updateBtn' value='Update' /> 
		<button type="button" onclick="javascript:window.location='accommodationInfo.php';">Cancel</button>
		<div class="help-block with-errors"></div>
	</div>
	
</form>

<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="dist/validator.min.js"></script>
</body>
</html>