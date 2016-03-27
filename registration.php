<!-- To Do: Check that all boxes (except for bio) have an input value -->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Registration Page</title>
		<link rel="icon" type="image/png" href="../resources/calc.png">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" ></script>
	    <script src="js/validator.js"></script>
		
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		
		<noscript><h2 class="error">Warning: This site requires JavaScript."</h2></noscript>
  
    </head>
<body>

 <?php
  //Create a user session or resume an existing one
 session_start();
 ?>

  <?php
 //Check if the user is already logged in and has an active session
if(isset($_SESSION['ID'])){
	//Redirect the browser to the profile editing page and kill this page.
	header("Location: defaultSearch.php");
	die();
}
 //Cancel registration brings user back to home page
 if(isset($_POST['cancelBtn'])){
 	header("Location: index.php");
	die();
 }
 
  ?>


 <?php

 if(isset($_POST['createAccBtn'])) {

	include_once 'config/connection.php'; 
	
	// Check if account already exists
	$query1 = "SELECT email FROM member WHERE email=?";
	
	if($stmt1 = $con->prepare($query1)){
		$stmt1->bind_Param("s", $_POST['email']);
		$stmt1->execute();
		$result1 = $stmt1->get_result();
		$num = $result1->num_rows;
		if($num==0){
			// Create account (Must have input in all boxes except for bio)
			$query2 = "INSERT into Member (password, user_type, first_name, last_name, email, phone_number, year, faculty, degree_type, bio) VALUES(?,'member',?,?,?,?,?,?,?,?)";
			$stmt2 = $con->prepare($query2);
			$stmt2->bind_Param("sssssssss", $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['phone_number'], $_POST['year'], $_POST['faculty'], $_POST['degree_type'], $_POST['bio']);
			$stmt2->execute();
			$result2 = $con->query($query2);
			header("Location: defaultSearch.php");
			die();
		} else {
			echo "Account with this email already exists.";
		}
	} else {
		echo "Failed to prepare the SQL";
	}
 } 
  ?>

  
 <form name='registration' id='registration' action='registration.php' method='post' >
    <table border='0'>
        <tr>
            <td>E-mail</td>
            <td><input type='text' name='email' id='email' /></td>
        </tr>
        <tr>
            <td>Password</td>
             <td><input type='password' name='password' id='password' onkeyup="checkFilled(this)" /></td>
        </tr>
        <tr>
            <td>First Name</td>
            <td><input type='text' name='first_name' id='first_name' onkeyup="checkFilled(this)"/></td>
        </tr>
        <tr>
            <td>Last Name</td>
            <td><input type='text' name='last_name' id='last_name' onkeyup="checkFilled(this)"/></td>
        </tr>
        <tr>
            <td>Phone number</td>
            <td><input type='text' name='phone_number' id='phone_number' onkeyup="checkNumber(this)"/></td>
        </tr>
        <tr>
            <td>Year</td>
            <td><input type='text' name='year' id='year' onkeyup="checkFilled(this)"/></td>
        </tr>
        <tr>
            <td>Faculty</td>
            <td><select name = 'faculty' form = 'registration'>
				<option>Arts & Science</option>
				<option>Applied Science & Engineering</option>
				<option>Business</option>
				<option>Law</option>
				<option>Education</option>
				<option>Medicine</option>
				<option>Health Science</option>
				<option>Computing</option>
			</select></td>
        </tr>
        <tr>
            <td>Degree Type</td>
            <td><select name = 'degree_type' form = 'registration'>
				<option>BA</option>
				<option>BSc</option>
				<option>BEd</option>
				<option>BPHE</option>
				<option>BCom</option>
				<option>BComp</option>
				<option>BFA</option>
				<option>BMus</option>
				<option>BNSc</option>
				<option>BAH</option>
				<option>LLB/JD</option>
				<option>PHD</option>
				<option>LLM</option>
				<option>MA</option>
				<option>MDiv</option>
				<option>MSc</option>
				<option>MEd</option>
				<option>MD</option>
				<option>MPL</option>
				<option>MPA</option>
				<option>MIR</option>
				<option>MBA</option>
				<option>EMBA</option>
				<option>MBAst</option>
				<option>NMBA</option>
				<option>LLD</option>
				<option>DSc</option>
				<option>DDiv</option>
			</select></td>
        </tr>
        <tr>
            <td>Bio</td>
            <td><textarea rows ="8" cols="50" name='bio' id='bio' /></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td>
				<input type='submit' id='cancelBtn' name='cancelBtn' value='Cancel' /> 
                <input type='submit' id='createAccBtn' name='createAccBtn' value='Create Account' disabled = "disabled"/> 
            </td>
        </tr>
    </table>
</form>
</body>
</html>
