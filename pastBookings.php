<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Results</title>
  
    </head>
<body>

 <form name='pastBookings' id='pastBookings' action='pastBookings.php' method='post'> 
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
 
 Logged in as:  <?php echo $myrow['first_name']?>
 <br>
<?php
 $CURRENT_DATE=2016-03-30;
 $property_id = $_SESSION['pid'];
 
$searchQuery = "SELECT address, start_date, end_date, supplier_id
FROM property NATURAL JOIN books
WHERE consumer_id = 4 AND end_date <= CURRENT_DATE AND status = 'approved';";

$result = $con->query($searchQuery);
echo "<table> <tr> <td>Past Bookings:  </td></tr>";
$i = 1;
while ($row = mysqli_fetch_array($result)) {
	$id = $row['address'];
	echo "<tr> <td>" . $i . " ) " . "<input type='submit' id='$id' name='accName' value='$id' />" ." </td> <td> Check in: " . $row['start_date'] . "</td> <td> Check out: " . $row['end_date'] .
	" </td> <td>" ."<input type='submit' id='$id' name='postCom' value='Post Comment' />" . " </td> </tr>";
	$i++;
} // end while
echo "<br>";
echo "</table>";


// send property_id of clicked address .. go to accommodation information page
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

if(isset($_POST['postCom'])) {
	// take user to new comment/reply page to post a comment or rating on an accommodation that they have stayed at
	header("Location: newComReply.php");
	die();

}
?>

 </form>
</body>
</html>
