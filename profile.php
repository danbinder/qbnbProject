<!-- 
TO DO: - add member rating (query)
Suggestion: List properties owned by the member?

Changes made: log in session gets member type	(difference menu bar)
		- if member type is admin (or session id) then delete account, edit profile, accommodations, activity

LYDIA: - line 76: *Create a button to get back to accom info
	*- have all delete, edit, accomodations, activity button to the right side rather than left (separate from back to accom button)
	*- add bio section 
	*- if possible, format lines 85-90 so that you don't need to constantly switch between php and html
	
Yaas im done!!
-->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Results</title>
  
    </head>
<body>

 <form name='profile' id='profile' action='profile.php' method='post'>
 
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
	if(isset($_GET['profile'])){
		//Destroy the user's session.
		$_SESSION['user_id']=$_SESSION['ID'];
	}
 
  $user_id = $_SESSION['user_id'];

	$searchQuery = " SELECT id, first_name, last_name, email, phone_number, year, faculty, degree_type, bio 
	FROM member
	WHERE id = $user_id";

$result = $con->query($searchQuery);
$row = mysqli_fetch_array($result);
echo "<br>";
echo "<table>";
echo $row['first_name'] . " " . $row['last_name'];
echo "<tr>" . "<td>" . "E-mail: " . "</td>" . "<td>" . $row['email'] . "</td>" . "</tr>";
echo "<tr>" . "<td>" . "Phone Number: " . "</td>" . "<td>" . $row['phone_number'] . "</td>" . "</tr>";
echo "<tr>" . "<td>" . "Year: " . "</td>" . "<td>" . $row['year']. "</td>" . "</tr>";
echo "<tr>" . "<td>" . "Faculty: " . "</td>" . "<td>" . $row['faculty'] . "</td>" . "</tr>";
echo "<tr>" . "<td>" . "Degree Type: " . "</td>" . "<td>" . $row['degree_type'] . "</td>" . "</tr>";
echo "<tr>" . "<td>" . "Bio: " . "</td>" . "<td>" . $row['bio'] . "</td>" . "</tr>";


if($user_type == 'admin'|| $row['id']== $_SESSION['ID']){
	// only visible to user and admin
    echo "<tr>" . "<td>" . " " . "</td>" . "<td>" . " " . "</td>" . "<td>" . "<input type='submit' class='button' name='deleteBtn' value='Delete Account' />" . "</td>" . "</tr>"; 
 	echo "<tr>" . "<td>" . " " . "</td>" . "<td>" . " " . "</td>" . "<td>" . "<a href='bookings.php'>Activity</a><br/> " . "</td>" . "</tr>";
    echo "<tr>" . "<td>" . " <a href='accommodationInfo.php'>Back to Accommodation Information</a><br/> " . "</td>" . "<td>" . " " . "</td>" . "<td>" .  "<a href='myAccommodations.php'>Accommodations</a><br/>" . "</td>" . "</tr>";

 }
 
// only visible to user
if($row['id']== $_SESSION['ID']){
	echo "<tr>" . "<td>" . " " . "</td>" . "<td>"  . " " . "</td>" . "<td>" . "<a href='editProfile.php'>Edit Profile</a><br/>" . "</td>" . "</tr>";
}

 echo "</table>";

 ?>
 
<br>
<?php
 if(isset($_POST['deleteBtn'])) {
	// Delete the user and update the database
		// if the user redirect to memberSearch page
		// if the user is not an admin redirect to homepage
 	$_SESSION['ID']=null;
	session_destroy();
	header("Location: index.php");
	die();
 }
 

?>

</form>
</body>
</html>