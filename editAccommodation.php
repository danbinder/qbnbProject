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
 
<?php include 'loginCheck.php'?>

 Logged in as:  <?php echo $myrow['first_name']; ?><br>
 
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
$property_id = $_SESSION['pid'];

$searchQuery = "SELECT address, district, type, price, supplier_id, guests, bedrooms, bathrooms, beds, breakfast, pool, others FROM property NATURAL JOIN features WHERE property_id=3";

$result = $con->query($searchQuery);
$row = mysqli_fetch_array($result);

 ?>
<!-- dynamic content will be here -->
<form data-toggle="validator" role="form" name='editAccommodation' id='editAccommodation' action='editAccommodation.php' method='post'>
	<div class="form-group">
		<label>Address</label>
		<input type='text' name='address' id='address' disabled  value="<?php echo $row['address']; ?>""  />
		<div class="help-block with-errors"></div>
	</div>
	<?php echo $row['type']; ?>
	<div class="form-group">
		<br>
		<label>Type</label>
		<select>
			<option>House</option>
			<option>Apartment</option>
			<option selected = "selected">Condo</option>
			<option>Room</option>
		</select>
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Price</label>
		<input type='number' name='price' id='price' required  value="<?php echo $row['price']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Availability</label>
		<input type='text' name='avail' id='avail' required  value="<?php echo $row['availability']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Guests</label>
		<input type='number' name='guests' id='guests' required  value="<?php echo $row['guests']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Bedrooms</label>
		<input type='number' name='bedrooms' id='bedrooms' required  value="<?php echo $row['bedrooms']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Bathrooms</label>
		<input type='number' name='bathrooms' id='bathrooms' required  value="<?php echo $row['bathrooms']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Beds</label>
		<input type='number' name='beds' id='beds' required  value="<?php echo $row['beds']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Breakfast</label>
		<input type='number' name='breakfast' id='breakfast' required  value="<?php echo $row['breakfast']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Pool</label>
		<input type='number' name='pool' id='pool' required  value="<?php echo $row['pool']; ?>" />
		<div class="help-block with-errors"></div>
	</div>
	
	<div class="form-group">
		<br>
		<label>Other</label>
		<textarea rows ="8" cols="50" name='bio' id='bio' /><?php echo $row['others']; ?></textarea>  
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