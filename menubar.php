<!-- 
changes: profile(true), bookings (True), accomInfo -> myAccom (true)
		
		add query to be sent to bookings - based on user_id
		add query to be sent to my Accom - user_id = supplier_id

To Do: Change to menuBarAdmin, and create a menuBarUser
		- on each page, use query for log-in to check if user type is user of admin
		
-->
		

<div id="top">
	
	<a href="index.php"><img class="pic" src="resources/calc.png" alt="logo"></a>
	  
	<div id="menubar">  
		<a href="profile.php?profile=1">Profile</a>
		<a href="defaultSearch.php">Search</a>
		<a href="bookings.php?bookings=1">Bookings</a>
		<a href="myAccom.php?myAccom=1">My Accom.</a>
		<a href="index.php?logout=1">Log Out</a>
		
</div>