<!-- 
David: change menu bar 
To do: delete accommodations
-->
<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Results</title>
  
    </head>
<body>

 <form name='searchResults_allAcc' id='searchResults_allAcc' action='searchResults_allAcc.php' method='post'> 
<?php 
	// check if user is logged in 
	session_start();
	if(isset($_SESSION['ID'])){
    include_once 'config/connection.php'; 
        $query = "SELECT first_name FROM member WHERE id=?";
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
 ?>
 
 <?php include 'menubar.php' ?>
 
 Logged in as:  <?php echo $myrow['first_name']; ?>, <a href="index.php?logout=1">Log Out</a><br/>

 <input type='submit' id='profileBtn' name='profileBtn' value='Profile' /> 
 <input type='submit' id='browseBtn' name='browseBtn' value='Browse' />

  <?php
  if(isset($_POST['profileBtn'])){
	header("Location: editProfile.php");
	die();
  }
  if(isset($_POST['browseBtn'])){
	header("Location: defaultSearch.php");
	die();
  }
  ?>
 <!-- <a href="defaultSearch.php?logout=1">Search Again</a><br/>
-->
 <?php

$search = $_SESSION['search'];

if(isset($_POST['ratingBtn'])){
	$addQ = " ORDER BY avg(property_rating) DESC";
	echo "rating";
	$_SESSION['search'] = $search;
	$_SESSION['add'] = $addQ;
	header("Location: searchResults_allAcc.php");
	die();
} elseif(isset($_POST['priceBtn'])) {
	$addQ = " ORDER BY price";
	echo "price";
	$_SESSION['search'] = $search;
	$_SESSION['add'] = $addQ;
	header("Location: searchResults_allAcc.php");
	die();
} else{
	$addQ ="";
}

$addQ ="";

$addQ = $_SESSION['add'];

$searchQuery = $search.$addQ;

$result = $con->query($searchQuery);
echo "<table>";
echo "<tr>" . PHP_EOL; 
echo "<td>" . "Delete " . "</td>" . PHP_EOL;
echo "<td>" . "Accommodation: ". "</td>" . PHP_EOL;
echo "<td>" . " <input type='submit' id='priceBtn' name='priceBtn' value='Price/night' />" . "</td>" . PHP_EOL;
echo "<td>" . " <input type='submit' id='ratingBtn' name='ratingBtn' value='Rating' /> " . "</td>" . PHP_EOL;
echo "</tr>" . PHP_EOL;
echo "<tr>" . "<td>" . "" . "</td>" . "</tr>" . PHP_EOL;

echo "<form action='searchResults_allAcc.php' method='post'>";

while ($row = mysqli_fetch_array($result)) {
	echo "<tr>" . PHP_EOL;
	$id = $row['address'];
	echo "<td>" . " <input type='checkbox' name='deleted[]' value='$id' />" . "</td>" . PHP_EOL;
	echo "<td>" . " <input type='submit' id='$id' name='accName' value='$id' />" . "</td>" . PHP_EOL;
	echo "<td>" . "$" . $row['price'] . "</td>" . PHP_EOL;
	echo "<td>" . $row['avg(property_rating)']. "</td>".PHP_EOL;
	echo "<td>" . " <a href=accBooking.php>Booking</a><br/>". "</td>" . "\r\n" . PHP_EOL; // make buttons for each booking only visible to admin
	echo "</tr>" . PHP_EOL;
	
} // end while 
 echo "</table>";
 echo "<input type='submit' name='deleteSubmit' value='Delete Selected' />
		</form>";
// send property_id of clicked address
if(isset($_POST['accName'])) {
	$addressName = $_POST['accName'];
	$pidQuery = "SELECT property_id FROM property WHERE address = '$addressName'";
	$pidresult = $con->query($pidQuery);
	$pidRow = mysqli_fetch_array($pidresult);
	$property_id = $pidRow['property_id'];
	$_SESSION['pid'] = $property_id;
	header("Location: accommodationInfo.php");
	die();
}
if(isset($_POST['deleteSubmit'])) {
	if (empty($_POST['deleted'])) {
		echo "You did not select any accommodations to delete";
	}
	else {
		$deleteAcc = $_POST['deleted'];
		$N = count($deleteAcc);
		echo "<br>";
		echo("You selected $N accommodation(s): ");
		echo "<br>";
		for($i=0; $i < $N; $i++) {
		  // Delete the selected accommodation(s) and refresh the page and update database
		  echo "$deleteAcc[$i]";
		  echo "<br>";
		}
	}
}

 $con->close();


?>
<br>
 </form>
</body>
</html>