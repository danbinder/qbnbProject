<!-- To do: Check if input values are valid
			- phone number: number and exactly 10 digits 
			- fix quotation input 
-->

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

 if(isset($_POST['updateBtn']) && isset($_SESSION['ID'])){
  // include database connection
    include_once 'config/connection.php'; 
	
	//Check if input values are valid

	$query = "UPDATE member SET password=?,first_name=?,last_name=?,phone_number=?,bio=? WHERE ID=?";
 
	$stmt = $con->prepare($query);
	$stmt->bind_param('ssssss', $_POST['password'], $_POST['first_name'], $_POST['last_name'],$_POST['phone_number'],$_POST['bio'],$_SESSION['ID']);
	// Execute the query
		if($stmt->execute()){
			echo "Record was updated. <br/>";
		}else{
			echo 'Unable to update record. Please try again. <br/>';
		}
 }
 
 ?>
  <?php
if(isset($_SESSION['ID'])){
   // include database connection
    include_once 'config/connection.php'; 
	
	// SELECT query
        $query = "SELECT email, password, first_name, last_name, phone_number, bio FROM member WHERE id=?";
 
        // prepare query for execution
        $stmt = $con->prepare($query);
		
        // bind the parameters. This is the best way to prevent SQL injection hacks.
        $stmt->bind_Param("s", $_SESSION['ID']);

        // Execute the query
		$stmt->execute();
		
 
		// results 
		$result = $stmt->get_result();
		
		// Row data
		$myrow = $result->fetch_assoc();
		
} else {
	//User is not logged in. Redirect the browser to the login index.php page and kill this page.
	header("Location: index.php");
	die();
}

?>

<?php include 'menubar.php' ?>
 
  Welcome  <?php echo $myrow['first_name']; ?>, <a href="index.php?logout=1">Log Out</a><br/>
<!-- dynamic content will be here -->
<form name='editProfile' id='editProfile' action='profile.php' method='post'>
    <table border='0'>
        <tr>
            <td>E-mail</td>
            <td><input type='text' name='email' id='email' disabled  value="<?php echo $myrow['email']; ?>"  /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='text' name='password' id='password'  value="<?php echo $myrow['password']; ?>" /></td>
        </tr>
		<tr>
            <td>First Name</td>
            <td><input type='text' name='first_name' id='first_name'  value="<?php echo $myrow['first_name']; ?>" /></td>
        </tr>
		<tr>
            <td>Last Name</td>
            <td><input type='text' name='last_name' id='last_name'  value="<?php echo $myrow['last_name']; ?>" /></td>
        </tr>
		<tr>
            <td>Phone Number</td>
            <td><input type='number_format' name='phone_number' id='phone_number'  value="<?php echo $myrow['phone_number']; ?>" /></td>
        </tr>
		<tr>
            <td>About Me</td>
            <td><textarea rows ="8" cols="50" name='bio' id='bio'  /><?php echo $myrow['bio']; ?></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type='submit' name='updateBtn' id='updateBtn' value='Update' disabled = "disabled" /> 
            </td>
        </tr>
    </table>
</form>