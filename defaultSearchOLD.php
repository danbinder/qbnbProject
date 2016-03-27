<!--

// 
David: Check for	1) district
					2) start date
					3) end date
					4) number of guests
					5) min rating
					6) max price
		- if true, create a variable and save string in variable (just use an empty string for now)


To DO: Accept empty search parameters
			Order By - on search results
			All properties

Select address, avg(property_rating), price
from property left outer join comments_on on property.property_id = comments_on.property_id
where district = 'North York' and price <= 200 and availability = 'ON' AND address not in 
(SELECT DISTINCT address
FROM property NATURAL JOIN books 
WHERE status = 'approved' AND (('2016-03-09'< end_date AND '2016-03-09' >= start_date) 
OR ('2016-03-11'> start_date AND '2016-03-11' <= end_date) 
OR ('2016-03-09' <= start_date AND '2016-03-11'>= end_date)))
AND address in
(SELECT DISTINCT address
FROM property NATURAL JOIN features 
WHERE guests = 4)
group by property.property_id
HAVING avg(property_rating) >= 4

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

Logged in as:  <?php echo $myrow['first_name']; ?>, <a href="index.php?logout=1">Log Out</a><br/>
City: Toronto 
 <?php
 if(isset($_POST['allAccBtn']) || isset($_POST['searchBtn'])) {
 
	$start = strtotime($_REQUEST['checkin']);
	$startDate = date('Y-m-d',$start);
	
	$end = strtotime($_REQUEST['checkout']);
	$endDate = date('Y-m-d',$end);
	
	$district = $_REQUEST['district'];
	$price = (string)($_REQUEST['price']);
	$guests = (string)($_REQUEST['guests']);
	$rating = (string)($_REQUEST['minRating']);
 
	$searchQuery = "Select address, avg(property_rating), price
					from property left outer join comments_on on property.property_id = comments_on.property_id
					where district = '$district' and (price<=$price) and (availability = 'ON') AND address not in 
						(SELECT DISTINCT address
						FROM property NATURAL JOIN books 
						WHERE status = 'approved' AND ((end_date> '$startDate' AND start_date<='$startDate') 
							OR (start_date<'$endDate' AND end_date>='$endDate') 
							OR (start_date>='$startDate' AND end_date<='$endDate')))
					AND address in
						(SELECT DISTINCT address
						FROM property NATURAL JOIN features 
						WHERE guests=$guests)
					group by property.property_id
					HAVING avg(property_rating)>=$rating";
	//echo $searchQuery;
	
	// Results on same page
	$result = $con->query($searchQuery);
	$num = $result->num_rows;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<li> Address: ".$row['address']."</li>";
			echo "<li> Price: ".$row['price']."</li>";
			echo "<li> Rating: ".$row['avg(property_rating)']."</li>";
		}
	}
	
	/*
	$_SESSION['search'] = $searchQuery;
	header("Location: searchResults_allAcc.php");
	die();
	*/
 }
  ?>
  
  
 <?php
// Drop down menu for district names
include('config/connection.php'); 
// SELECT query
$query = "SELECT name FROM district";
 
// prepare query for execution
if($stmt = $con->prepare($query)){

// Execute the query
$stmt->execute();

// Get Results
$result = $stmt->get_result();
$con->close();
}
?>


 <form name='defaultSearch' data-toggle='validator' id='defaultSearch' action='defaultSearch.php' method='post'>
	<div class="form-group">
		<label for='district' class = 'control-label'>District</label>
		<?php
		if ($result->num_rows > 0) {
			// output data of each row
		   echo "<select name='district' id='district' class='form-control'>";
		   while($row = $result->fetch_assoc()) {
				echo "<option>".$row['name']."</option>";
			} 
			echo "</select>";
		} else {
				echo "0 results";
		}
		?>
		<br>
	</div>
	
	<div class="form-group has-feedback">
		<label for='checkin' class = 'control-label' >Check in </label> 
		<input type='date' name='checkin' id='checkin' />
		<br>
	</div>
	
	<div class="form-group">
		<label for='checkout' class = 'control-label' >Check out</label> 
		<input type='date' name='checkout' id='checkout' />
		<br>
	</div>
	
	<div class="form-group">
		<label for='guests' class = 'control-label' >Number of Guests </label> 
		<input type='number' name='guests' id='guests'/>
		<br>
	</div>

	<div class="form-group">
		<label for='minRating' class = 'control-label' >Minimum Rating </label> 
		<select name = 'minRating' form = 'defaultSearch'>
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
		<label for='price' class = 'control-label' >Maximum Price/Night</label> 
		<input type='number' name='price' id='price'/>
		<br>
    </div>
	
	
	<input type='submit' class="btn btn-primary" id='searchBtn' name='searchBtn' value='Search' /> 
	<input type='submit' id='allAccBtn' name='allAccBtn' value='All Accommodations' /> 

	<br>
</form>
</body>
</html>
