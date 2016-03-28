<!--

// 
David: Check in and check out dates must be filled
		Check in date must not be from the past
		Check out date must be after check in date
		

-->


<!DOCTYPE HTML>
<html>
    <head>
        <title>Search Page</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" ></script>
	    <script src="js/validator.js"></script>
		<noscript><h2 class="error">Warning: This site requires JavaScript."</h2></noscript>
  
    </head>
<body>



 
 
  <?php
  if(isset($_POST['searchBtn'])){
	header("Location: members.php");
	die();
  }
  if(isset($_POST['allMembersBtn'])){
	header("Location: members.php");
	die();
  }
  
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
 
 if(isset($_POST['allMembersBtn']) || isset($_POST['searchBtn'])) {
	if(isset($_POST['allMembersBtn'])){
	$search= "SELECT distinct id, first_name, last_name, faculty, degree_type,
(SELECT avg(supplier_rating)
 FROM property left outer join comments_on on property.property_id = comments_on.property_id
 WHERE property.supplier_id = 1) AS member_rating
FROM member left outer join property on member.ID = property.supplier_id
WHERE member.id = 1 AND first_name = 'Lydia' AND last_name = 'Noureldin' AND faculty = 'Computing' AND degree_type = 'BComp'";
	}//all accommodations
	
	if(isset($_POST['searchBtn'])){
	
	$faculty = $_POST['faculty'];
	if(strcmp($faculty, "- - - - - - -") !=0){
		echo $faculty;
		$facultyQ = "faculty = '$faculty' and ";
	} else {
		$facultyQ = "";
	}
	
	$price = (string)($_POST['price']);
	if($price){
		$priceQ = "price<=$price";
	}else {
		$priceQ = "";
	}
	
	$guests = (string)($_POST['guests']);
	if($guests){
		$guestsQ = "AND address in
						(SELECT DISTINCT address
						FROM property NATURAL JOIN features 
						WHERE guests=$guests)";
	}else {
		$guestsQ = "";
	}
	
	$rating = (string)($_POST['minRating']);
	if($rating){
		$ratingQ = "HAVING avg(property_rating)>=$rating";
	}else {
		$ratingQ = "";
	}
	
	$search = "Select address, price, avg(property_rating)
					from property left outer join comments_on on property.property_id = comments_on.property_id
					where $districtQ $priceQ (availability = 'ON') AND address not in 
						(SELECT DISTINCT address
						FROM property NATURAL JOIN books 
						WHERE status = 'approved' AND ((end_date> '$startDate' AND start_date<='$startDate') 
							OR (start_date<'$endDate' AND end_date>='$endDate') 
							OR (start_date>='$startDate' AND end_date<='$endDate')))
					$guestsQ group by property.property_id $ratingQ" ;
	//echo $searchQuery;
	}//search query selection
 } 
	
  ?>
  
<?php
	$user_type = $myrow['user_type'];
	if($user_type == 'member'){
		include 'menubar.php';
	} else {
		include 'menubarAdmin.php';
	} ?>

Logged in as:  <?php echo $myrow['first_name']; ?><br>

<?php
// Drop down menu for district names
include('config/connection.php'); 
// SELECT query
$query = "SELECT faculty, degree_type FROM member";
 
// prepare query for execution
if($stmt = $con->prepare($query)){

// Execute the query
$stmt->execute();

// Get Results
$result = $stmt->get_result();
$con->close();
}
?>

 <form name='memberSearch' data-toggle='validator' id='memberSearch' action='members.php' method='post'>
		
	<div class="form-group has-feedback">
		<label for='first_name' class = 'control-label' >First Name </label> 
		<input type='text' name='first_name' id='first_name' />
		<br>
	</div>
	
	<div class="form-group">
		<label for='last_name' class = 'control-label' >Last Name</label> 
		<input type='text' name='last_name' id='last_name' />
		<br>
	</div>
	
	<div class="form-group">
		<label for='memberId' class = 'control-label' >Member ID </label> 
		<input type='number' name='memberId' id='memberId'/>
		<input type="checkbox" name="isSupplier" value="isSupplier">Supplier?
	</div>

	<div class="form-group">
		<label for='minRating' class = 'control-label' >Minimum Rating </label> 
		<select name = 'minRating' form = 'memberSearch'>
			<option>0</option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
		
		<label for='maxRating' class = 'control-label' >Maximum Rating </label> 
		<select name = 'maxRating' form = 'memberSearch'>
			<option>0</option>
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select>
		<br>
	</div>
	
	<div class="form-group">
		<label for='faculty' class = 'control-label' >Faculty</label> 
		<?php
		if ($result->num_rows > 0) {
			// output data of each row
		   echo "<select name='faculty'>";
		   echo "<option> - - - - - - - </option>";
		   while($row = $result->fetch_assoc()) {
				echo "<option>".$row['faculty']."</option>";
			} 
			echo "</select>";
		} else {
				echo "0 results";
		}
		?>
		<br>
    </div>
	
	<div class="form-group">
		<label for='degreeType' class = 'control-label' >Degree Type</label> 
		<?php
		if ($result->num_rows > 0) {
			// output data of each row
		   echo "<select name='degree_type'>";
		   echo "<option> - - - - - - - </option>";
		   while($row = $result->fetch_assoc()) {
				echo "<option>".$row['degree_type']."</option>";
			} 
			echo "</select>";
		} else {
				echo "0 results";
		}
		?>
		<br>
    </div>
	
	
	<input type='submit' class="btn btn-primary" id='searchBtn' name='searchBtn' value='Search' /> 
	<input type='submit' id='allMembersBtn' name='allMembersBtn' value='All Members' /> 

	<br>
</form>
</body>
</html>
