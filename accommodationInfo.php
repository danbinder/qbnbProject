<!-- 
Changes made: log in session gets member type	(difference menu bar)
 
-->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Accommodation Information</title>
  
    </head>
<body>
 <form name='accommodationInfo' id='accommodationInfo' action='accommodationInfo.php' method='post'> 

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

$searchQuery = "SELECT address, district, type, price, supplier_id, guests, bedrooms, bathrooms, beds, breakfast, pool, others,
	(SELECT avg(property_rating)
     FROM property left outer join comments_on on property.property_id = comments_on.property_id
     WHERE property.property_id = $property_id)AS rating,
    	(SELECT first_name
      	FROM property NATURAL JOIN member
         WHERE property.supplier_id = member.ID AND property.property_id = $property_id) AS fName,
         	(SELECT last_name
      		FROM property NATURAL JOIN member
         	WHERE property.supplier_id = member.ID AND property.property_id = $property_id) AS lName
FROM property NATURAL JOIN features
WHERE property_id = $property_id";

$result = $con->query($searchQuery);
$row = mysqli_fetch_array($result);

echo "<br>";
$id = $row['district'];
echo "<table>";
echo "<tr>";
echo "<td>" . "Supplier: " . "</td>";
$sup_name = $row['fName'] . " " . $row['lName'] ;
echo "<td>" . "<input type='submit' id='$sup_name' name='sup_name' value='$sup_name' />" . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Accommodation Name: ". "</td>" . PHP_EOL;
echo "<td>" .  $row['address'] . "</td>";
$address = $row['address'];
if($user_type == 'admin'|| $row['supplier_id']== $_SESSION['ID']){
	echo "<td>" . "<a href='accBooking.php'>$address's Bookings</a><br/>" . "</td>"; 
}
echo "</tr>";
echo "<tr>";
echo "<td>" . "Average Rating: " . "</td>";
if (empty($row['rating'])) {
	echo "<td>" . "No ratings yet!" . "</td>";
} else {
	echo "<td>" . $row['rating'] . "</td>";
}
echo "</tr>";
echo "<tr>";
echo "<td>" . "District: " . "</td>" . PHP_EOL;
echo "<td>" . "<input type='submit' id='$id' name='districtName' value='$id' />". "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Type: " . "</td>";
echo "<td>" .  $row['type'] . "</td>";
echo "<tr>";
echo "<td>" . "Price: " . "</td>";
echo "<td>" . "$" . $row['price'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Guests: ". "</td>";
echo "<td>" . $row['guests'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Number of Bedrooms: " . "</td>";
echo "<td>" . $row['bedrooms'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Number of Bathrooms" . "</td>";
echo "<td>" . $row['bathrooms'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Number of Beds: " . "</td>";
echo "<td>" . $row['bathrooms'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Breakfast included: " . "</td>";
echo "<td>" . $row['breakfast'] . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Pools available: ".  "</td>";
echo "<td>" . $row['pool'] . "</td>";
echo "</tr>";
if (empty($_POST['others'])) {
	echo "<tr>";
	echo "<td>" . "Other: " . "</td>";
	echo "<td>" . $row['others'] . "</td>";
	echo "</tr>";
}
echo "</table>";
 
 if(isset($_POST['backtoSerchBtn'])) {
 	header("Location: searchResults_allAcc.php");
	die();
 }
 if(isset($_POST['editBtn'])) {
	//$_SESSION['property'] = $property_id;
 	header("Location: editAccommodation.php");
	die();
 }
 if(isset($_POST['requestBtn'])) {
 	// Request the accommodation and change the status in the database
 	echo "Accommodation has been requested!";
 	header("Location: accommodationInfo.php");
	die();
 }

 if(isset($_POST['commentRatingBtn'])) {
 	header("Location: comments.php");
	die();
 }
 
 if(isset($_POST['sup_name'])) {
	$sidQuery = "SELECT supplier_id FROM property WHERE property_id = $property_id";
	$sidresult = $con->query($sidQuery);
	$sidRow = mysqli_fetch_array($sidresult);
	$supplier_id = $sidRow['supplier_id'];
	$_SESSION['user_id'] = $supplier_id;
	header("Location: profile.php?accomInfo=1");
	die();
}
 if(isset($_POST['districtName'])) {
	$districtName = $_POST['districtName'];
	$_SESSION['district'] = $districtName;
	header("Location: pointOfInterest.php?accomInfo=1");
	die();
}
 ?>

 <br>
 <input type='submit' id='backtoSerchBtn' name='backtoSerchBtn' value='Back to Search Results' /> 
 <input type='submit' id='editBtn' name='editBtn' value='Edit' /> <!-- only visible to supplier--> 
 <input type='submit' id='requestBtn' name='requestBtn' value='Request' /> 
 <input type='submit' id='commentRatingBtn' name='commentRatingBtn' value='Comments/Ratings' />
 <br>
 
</form>
</body>
</html>
