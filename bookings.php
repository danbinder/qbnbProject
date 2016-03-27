<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Results</title>
  
    </head>
<body>

 <form name='bookings' id='bookings' action='bookings.php' method='post'> 
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
 
 Logged in as:  <?php echo $myrow['first_name']; ?>
 
 <?php
 $CURRENT_DATE=2016-03-30;
 $property_id = $_SESSION['pid'];
$searchQuery = "SELECT address, start_date, end_date, status, price
FROM property NATURAL JOIN books
WHERE consumer_id = 4 AND start_date>=$CURRENT_DATE";

$result = $con->query($searchQuery);
echo "<table> <tr> <td> My Bookings:  </td> <td>   </td> <td> <a href='pastBookings.php'>Past Bookings</a><br/> </td> <tr>";
// display approved bookings
echo "<tr> <td> Approved: </td> <tr>";
$row = mysqli_fetch_array($result);
$i = 1;
while ($row = mysqli_fetch_array($result)) {
	if($row['status'] == 'approved') {
		$id = $row['address'];
		echo "<tr> <td></td><td>" . $i . " ) " . "<input type='submit' id='$id' name='accName' value='$id' />" ." </td> <td> Check in: " . $row['start_date'] . "</td> <td> Check out: " . $row['end_date'] .
		" </td> <td> Price: $" . $row['price']." </td> </tr>";
		$i++;
	}
} // end while
echo "<br>";

// display rejected bookings
$result = $con->query($searchQuery);
echo "<tr> <td> Rejected: </td> <tr>";
$i = 1;
while ($row = mysqli_fetch_array($result)) {
	if($row['status'] == 'rejected') {
		$id = $row['address'];
		echo "<tr> <td>" . " <input type='checkbox' name='deleted[]' value='$id' />" . "</td><td>" . $i . " ) " . "<input type='submit' id='$id' name='accName' value='$id' />" ." </td> <td> Check in: " . $row['start_date'] . "</td> <td> Check out: " . $row['end_date'] .
		" </td> <td> Price: $" . $row['price']." </td> </tr>";
		$i++;
	}
} // end while
echo "<br>";

// display pending bookings
$result = $con->query($searchQuery);
echo "<tr> <td> Pending: </td> <tr>";
$i = 1;
while ($row = mysqli_fetch_array($result)) {
	if($row['status'] == 'pending') {
		$id = $row['address'];
		echo "<tr><td>" . " <input type='checkbox' name='deleted[]' value='$id' />" . "</td><td>". $i . " ) " . "<input type='submit' id='$id' name='accName' value='$id' />" ." </td> <td> Check in: " . $row['start_date'] . "</td> <td> Check out: " . $row['end_date'] .
		" </td> <td> Price: $" . $row['price']." </td> </tr>";
		$i++;
	}
} // end while	
echo "</table>";

echo "<input type='submit' name='deleteSubmit' value='Delete Selected' />" . PHP_EOL;

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
// check box
if(isset($_POST['deleteSubmit'])) {
	echo "<br>";
	if (empty($_POST['deleted'])) {
		echo "You did not select any bookings to delete";
	}
	else {
		$deleteBook = $_POST['deleted'];
		$N = count($deleteBook);
		echo("You selected $N bookings(s): ");
		for($i=0; $i < $N; $i++) {
		  // Delete the selected booking(s) and refresh the page and update database
		  echo "$deleteBook[$i]";
		  echo "<br>";
		}
	}
}

 $con->close();

 ?>
 </form>
</body>
</html>