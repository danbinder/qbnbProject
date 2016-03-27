<!--
Changes made: log in session gets member type	(difference menu bar)

TO DO: line 60: add button back to accomInfo
	
-->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Points of Interest</title>
  
    </head>
<body>

 <form name='pointOfInterest' id='pointOfInterest' action='pointOfInterest.php' method='post'> 
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
 
 Logged in as:  <?php echo $myrow['first_name']; ?>, <a href="index.php?logout=1">Log Out</a><br/>
 <br>
 <?php

 $districtName = $_SESSION['district'];
$searchQuery = "SELECT name FROM point_of_interest WHERE district = '$districtName'";
$result = $con->query($searchQuery);
$districtButton = "<input type='submit' class='button' name='districtBtn' value='$districtName' />";
echo "District: " . $districtButton . "<br>" . "Points of Interest: "; // 
$i = 1;
while ($row = mysqli_fetch_array($result)) {
	echo "<br>" . PHP_EOL;
	$poi = $row['name'];
	echo $i . ") " . $poi;
	$i++;
} // end while
echo "<br>" . PHP_EOL;
echo "<br>" . PHP_EOL;

echo " <a href='accommodationInfo.php'>Back to Accommodation Information</a><br/> " . PHP_EOL;


if(isset($_POST['districtBtn'])) {
	$search= "Select address, price, avg(property_rating)
					from property left outer join comments_on on property.property_id = comments_on.property_id
					where availability = 'ON' AND district = '$districtName'
					group by property.property_id";
	$addQ = " ORDER BY price";
	$_SESSION['search'] = $search;
	$_SESSION['add'] = $addQ;
	$searchQuery = $search.$addQ;
	//echo $searchQuery;
	// go to search results displaying all accommodations in that district
	header("Location: searchResults_allAcc.php");
	die();
}
?>

