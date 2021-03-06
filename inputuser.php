<?php
	include_once('config.php');
	include_once('dbutils.php');
	include_once('hashutil.php');
?>

<?php
	// Generating pull down menu for employers
	
	// connect to database
	$db = connectDB($DBHost,$DBUser,$DBPasswd,$DBName);
	
	// set up my query
	$query = "SELECT EID, E_NAME FROM employer ORDER BY E_NAME;";
	
	// run the query
	$result = queryDB($query, $db);
	
	// options for employers
	$employerOptions = "";
	
	// go through all employers and put together pull down menu
	while ($row = nextTuple($result)) {
		$employerOptions .= "\t\t\t";
		$employerOptions .= "<option value='";
		$employerOptions .= $row['EID'] . "'>" . $row['E_NAME'] . "</option>\n";
	}
?>



<html>
<head>
	<title>
		<?php echo "Input user - " . $Title; ?>
	</title>

	<!-- Following three lines are necessary for running Bootstrap -->
	
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>	
</head>

<body>
<font face="Arial Black">
<nav class="navbar navbar-default">
  <div style = "background:#A9D0F5 !important" class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="Home.php"><font face="Arial Black">Stop! Wage Theft</a></font>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="Home.php"><span style="font-size:1.0em" class="glyphicon glyphicon-home"></span><font face="Arial Black"> Home</a></li></font>
	  <li><a href="jobform.php"><span style="font-size:1.0em" class="glyphicon glyphicon-briefcase"></span><font face="Arial Black"> Enter Job</a></li></font>
      <li><a href="enterhours.php"><span style="font-size:1.0em" class="glyphicon glyphicon-time"></span><font face="Arial Black"> Enter Hours</a></li></font>
      <li><a href="enterpaycheck.php"><span style="font-size:1.0em" class="glyphicon glyphicon-barcode"></span><font face="Arial Black"> Enter Paycheck</a></li></font>
      <li><a href="Makeclaim.php"><span style="font-size:1.0em" class="glyphicon glyphicon-bullhorn"></span><font face="Arial Black"> Make Claim</a></li></font>
      <li><a href="faq.php"><span style="font-size:1.0em" class="glyphicon glyphicon-question-sign"></span><font face="Arial Black"> FAQ</a></li></font>
	  <li><a href="contactus.php"><span style="font-size:1.0em" class="glyphicon glyphicon-phone-alt"></span><font face="Arial Black"> Contact Us</a></li></font>
    </ul>
  </div>
</nav>
<div class="container">

<div class="container">

<!-- Page header -->
<div class="container">
<div class="col-xs-12">
<div class="page-header">
	<h1>Sign Up for Avoid Wage-Theft</h1>
</div>
</div>
</div>
</div>



<?php
// Back to PHP to perform the search if one has been submitted. Note
// that $_POST['submit'] will be set only if you invoke this PHP code as
// the result of a POST action, presumably from having pressed Submit
// on the form we just displayed above.
if (isset($_POST['submit'])) {
//	echo '<p>we are processing form data</p>';
//	print_r($_POST);

	// get data from the input fields
	$entrylevel = $_POST['entrylevel'];
	$email = $_POST['email'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	$phone = $_POST['phone'];
	$name = $_POST['name'];
	
	// check to make sure we have an email
	
	if (!$name) {
		punt ("Please enter a name");
	}

	if (!$email) {
		punt("Please enter an email");
	}

	if (!$password1) {
		punt("Please enter a password");
	}

	if (!$password2) {
		punt("Please enter your password twice");
	}
	
	if ($password1 != $password2) {
		punt("Your two passwords are not the same");
	}
	
	if (!$phone) {
		punt ("Please enter phone number");
	}

	// check if email already in database
		// connect to database
	$db = connectDB($DBHost,$DBUser,$DBPasswd,$DBName);
	
	// set up my query
	$query = "SELECT email FROM Users WHERE email='$email';";
	
	// run the query
	$result = queryDB($query, $db);
	
	// check if the email is there
	if (nTuples($result) > 0) {
		punt("The email address $email is already in the database");
	}
	
	// generate hashed password
	$hashedPass = crypt($password1, getSalt());
	
	// set up my query
	$query = "INSERT INTO Users(entrylevel, name, phone, email, hashedPass) VALUES ('$entrylevel', '$name', '$phone', '$email', '$hashedPass');";
	
	// run the query
	$result = queryDB($query, $db);
		
	// tell users that we added the player to the database
	echo "<div class='panel panel-default'>\n";
	echo "\t<div class='panel-body'>\n";
    echo "\t\tThe user " . $email . " was added to the database\n";
	echo "</div></div>\n";
	
	header('Location: login.php');
}
?>

<!-- Form to enter club teams -->
<div class="container">
<div class="col-xs-12">

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

<div class="radio">
	<label><input type = "radio" name="entrylevel" value="Employer"> Employer</label>
</div>

<div class="radio">
	<label><input type = "radio" name="entrylevel" value="Non-Profit"> Non-Profit</label>
</div>

<div class="radio">
	<label><input type = "radio" name="entrylevel" value="Worker"> Worker</label>
</div>

<div class="form-group">
	<label for="name">Name (First,last)</label>
	<input type="name" class="form-control" name="name"/>
</div>

<div class="form-group">
	<label for="email">Email</label>
	<input type="email" class="form-control" name="email"/>
</div>

<div class="form-group">
	<label for="password1">Password</label>
	<input type="password" class="form-control" name="password1"/>
</div>

<div class="form-group">
	<label for="password2">Please enter password again</label>
	<input type="password" class="form-control" name="password2"/>
</div>

<div class="form-group">
	<label for="phone">Phone number</label>
	<input type="phone" class="form-control" name="phone"/>
</div>

<div class="form-group">
	<label for="artid">Employer</label>
	<select class="form-control" name="EID">
<?php echo $employerOptions; ?>
	</select>
</div>

<div id="browse_app">
  <p> Can't find your employer? <a class="btn btn-large btn-info" href="employerform.php">Register Employer</a> </p>
</div>


<button type="submit" class="btn btn-default" name="submit" onclick="myFunction()">Submit</button>

<script>
function myFunction(){
	alert("Your information is saved successfully!");
}
</script>

</form>

</div>
</div>

<!----------------->
<!---List users--->
<!----------------->
<div class="container">
<div class="col-xs-12">
	<h2><?php echo "Users"; ?></h2>
</div>
</div>

<div class="container">
<div class="col-xs-12">
<table class="table table-hover">

<!-- Titles for table -->
<thead>
<tr>
	<th>Email</th>
</tr>
</thead>

<tbody>
<?php
	// connect to database
	$db = connectDB($DBHost,$DBUser,$DBPasswd,$DBName);
	
	// set up my query
	$query = "SELECT email FROM Users ORDER BY email;";
	
	// run the query
	$result = queryDB($query, $db);
	
	while($row = nextTuple($result)) {
		echo "\n <tr>";
		echo "<td>" . $row['email'] . "</td>";
		echo "</tr>";
	}
	
?>

</tbody>
</table>
</div>
</div>

</div> <!-- closing bootstrap container -->
</body>
</html>
