<!-- To do: delete accommodations
-->
<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Results</title>
  
    </head>
<body>

 <form name='memberResults' id='memberResults' action='members.php' method='post'> 
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
 ?>
 
 <?php
	$user_type = $myrow['user_type'];
	if($user_type == 'member'){
		include 'menubar.php';
	} else {
		include 'menubarAdmin.php';
	} ?>
 Logged in as:  <?php echo $myrow['first_name']; ?>,<br/>


 <!-- <a href="defaultSearch.php?logout=1">Search Again</a><br/>
-->
 <?php

//$search = $_SESSION['memberSearch'];


$searchQuery = "SELECT distinct member.ID, member.first_name, member.last_name, member.faculty, member.degree_type,
(SELECT AVG(supplier_rating)
     FROM property left OUTER JOIN comments_on on property.property_id = comments_on.property_id
 where property.supplier_id = member.ID
GROUP BY supplier_id
) AS rating
FROM member left outer join property on member.id = property.supplier_id";

$result = $con->query($searchQuery);
echo "<table>";
echo "<tr>" . PHP_EOL; 
echo "<td>Delete</td>" . PHP_EOL;
echo "<td>Member Name</td>" . PHP_EOL;
echo "<td>Rating</td>".PHP_EOL;
echo "</tr>" . PHP_EOL;
echo "<tr>" . "<td>" . "" . "</td>" . "</tr>" . PHP_EOL;

echo "<form action='members.php' method='post'>";

while ($row = mysqli_fetch_array($result)) {
	echo "<tr>" . PHP_EOL;
	$id = $row['first_name']." ".$row['last_name'];
	$rating = $row['rating'];
	echo "<td>" . " <input type='checkbox' name='deleted[]' value='$id' />" . "</td>" . PHP_EOL;
	echo "<td>" . " <input type='submit' id='$id' name='accName' value='$id' />" . "</td>" . PHP_EOL;
	
	echo "<td>$rating</td>".PHP_EOL;
	echo "<td>" . " <a href=accBooking.php>Booking</a><br/>". "</td>" . "\r\n" . PHP_EOL; // make buttons for each booking only visible to admin
	echo "</tr>" . PHP_EOL;
	
} // end while 
 echo "</table>";
 echo "<input type='submit' name='deleteSubmit' value='Delete Selected' />
		</form>";
if(isset($_POST['accName'])) {
	header("Location: profile.php");
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