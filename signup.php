<?php
$msg = '';
$msgClass = '';

if(isset($_POST['submit'])){

	include_once 'database.php';

	$s_name = mysqli_real_escape_string($conn, $_POST['s_name']);
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error Handlers

	if(empty($s_name) OR empty($email) OR empty($pwd)){
		$msg = 'Please fill in all fields';
		$msgClass = 'alert-danger';
	} else{

		// Check if email is valid
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			$msg = 'Please enter valid Email';
			$msgClass = 'alert-danger';
		} else{
			$sql = "SELECT * FROM schools WHERE school_name='$s_name'";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck > 0) {
				$msg = 'This school has already been registered';
				$msgClass = 'alert-danger';
			} else{
				//Hashing the password
				$hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
				//Insert the user into the databse
				$sql = "INSERT INTO schools (school_name, school_email, school_pwd) VALUES ('$s_name', '$email', '$hashedPwd');";
				mysqli_query($conn, $sql);
				$msg = 'Registered';
				$msgClass = 'alert-success';
			}
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div>	
    	<?php if($msg != ''): ?>
    		<div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
    	<?php endif; ?>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	      <div>
		      <label>Schoolname</label>
		      <input type="text" name="s_name" value="<?php echo isset($_POST['s_name']) ? $s_name : ''; ?>">
	      </div>
	      <div>
	      	<label>E-mail</label>
	      	<input type="text" name="email" value="<?php echo isset($_POST['email']) ? $email : ''; ?>"> <!-- In the code if an error occurs for one field than all the fields inputted data is deleted. So the value attribute checks whether some data has been inputted and if yes than the data is shown if no then it is blank (the input box is blank)-->
	      </div>
	      <div>
	      	<label>Password</label>
	      	<input type="password" name="pwd" values="<?php echo isset($_POST['pwd']) ? $pwd : ''; ?>">
	      </div>
	      <br>
	      <button type="submit" name="submit">Submit</button>
      </form>
    </div>
</body>
</html>
