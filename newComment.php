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

 <?php include 'loginCheck.php' ?>




  
 <form name='comment' id='comment' action='newComment.php' method='post' >
    <table border='0'>
        <tr>
            <td>Accommodation</td>
            <td><input type='text' name='type' id='type' disabled = "disabled" value = "ADDRESS"/></td>
        </tr>
        <tr>
            <td>Reply</td>
            <td><textarea rows ="8" cols="50" name='reply' id='reply' /></textarea></td>
        </tr>
		<tr>
			<td>Rating</td>
			<td>
				<select name = 'rating' form = 'comment'>
					<option>0</option>
					<option>1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
				</select>/5
			</td>
		</tr>
        <tr>
            <td></td>
            <td>
				<input type='submit' id='cancelBtn' name='cancelBtn' value='Cancel' /> 
                <input type='submit' id='postComment' name='postComment' value='Post Comment'/> 
            </td>
        </tr>
    </table>
</form>
</body>
</html>
