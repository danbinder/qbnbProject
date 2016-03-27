<!DOCTYPE HTML>
<html>
    <head>
        <title>Welcome to mysite</title>
  
    </head>
<body>
 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>
 
 
  <?php
 //check if the user clicked the logout link and set the logout GET parameter
if(isset($_GET['logout'])){
	//Destroy the user's session.
	$_SESSION['ID']=null;
	session_destroy();
}
 ?>
  <?php
 //check if the user is already logged in and has an active session
if(isset($_SESSION['ID'])){
	//Redirect the browser to the profile editing page and kill this page.
	header("Location: defaultSearch.php");
	die();
}
 ?>
  <?php
//check if the login form has been submitted
if(isset($_POST['loginBtn'])){
 
    // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT ID,email, password FROM member WHERE email=? AND password=?";
 
        // prepare query for execution
        if($stmt = $con->prepare($query)){
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("ss", $_POST['email'], $_POST['password']);
         
        // Execute the query
		$stmt->execute();
 
		// Get Results
		$result = $stmt->get_result();

		// Get the number of rows returned
		$num = $result->num_rows;
		
		if($num>0){
			//If the username/password matches a user in our database
			//Read the user details
			$myrow = $result->fetch_assoc();
			//Create a session variable that holds the user's id
			$_SESSION['ID'] = $myrow['ID'];
			//Redirect the browser to the profile editing page and kill this page.
			header("Location: defaultSearch.php");
			die();
		} else {
			//If the username/password doesn't matche a user in our database
			// Display an error message and the login form
			echo "Failed to login";
		}
		} else {
			echo "failed to prepare the SQL";
		}
 }
  // check if the register form has been submitted
 if(isset($_POST['registerBtn'])){
 	header("Location: registration.php");
	die();
 }
?>


<!-- dynamic content will be here -->
 <form name='login' id='login' action='index.php' method='post'>
    <table border='0'>
        <tr>
            <td>E-mail</td>
            <td><input type='text' name='email' id='email' /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='password' id='password' /></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' id='loginBtn' name='loginBtn' value='Log In' /> 
                <input type='submit' id='registerBtn' name='registerBtn' value='Register'/>
            </td>
        </tr>
    </table>
</form>

</body>
</html>



<!--
 	<?php
  //Create a user session or resume an existing one
 	session_start();
 	
 	include_once 'config/connection.php'; 
 	$query = "SELECT * FROM member";
 	$result = $con->query($query);
 	if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	echo "<ul>";
        echo "<li> Member ID: ".$row['ID']."</li>";
        echo "<li> Full Name: ".$row['first_name']." ".$row['last_name'];
        echo "<li> e-mail: ".$row['email']."</li>";
        echo "<li> phone number: ".$row['phone_number']."</li>";
    	echo "</ul>";
    }
	} else {
    	echo "0 results";
	}
$con->close();
?>
<form>
  First name:<br>
  <input type="text" name="firstname"><br>
  Last name:<br>
  <input type="text" name="lastname">
</form>

<form>
  <input type="radio" name="gender" value="male" checked> Male<br>
  <input type="radio" name="gender" value="female"> Female<br>
  <input type="radio" name="gender" value="other"> Other
</form>

<a href="profile.php"> Link </a> 
 
</form>
 
</body>
</html>
-->