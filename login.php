<?php
$msg = '';
$msgClass = '';

session_start();

if (isset($_POST['submit'])) {

	include 'database.php';

	$s_name = mysqli_real_escape_string($conn, $_POST['s_name']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error Handlers
	//Check if inputs are inputy
	if (empty($s_name) OR empty($pwd)){
		$msg = 'Please fill in all fields';
		$msgClass = 'alert-danger';
	} else {
		$sql = "SELECT * FROM schools WHERE school_name='$s_name'";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			$msg = 'Incorrect Username/Password';
			$msgClass = 'alert-danger';
		} else {
			if ($row = mysqli_fetch_assoc($result)){
				//De-hashing the password
				$hashedPwdCheck = password_verify($pwd, $row['school_pwd']);
				if ($hashedPwdCheck === false) {
					$msg = 'Incorrect Username/Password';
					$msgClass = 'alert-danger';
				} elseif ($hashedPwdCheck === true) {
					//Login the user here
					$_SESSION['s_id'] = $row['school_id'];
					$_SESSION['s_email'] = $row['school_email'];
					$_SESSION['s_name'] = $row['school_name'];
					$msg = 'Logged In';
					$msgClass = 'alert-success';
				}
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
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
	      	<label>Password</label>
	      	<input type="password" name="pwd" value="<?php echo isset($_POST['pwd']) ? $pwd : ''; ?>"> <!-- In the code if an error occurs for one field than all the fields inputted data is deleted. So the value attribute checks whether some data has been inputted and if yes than the data is shown if no then it is blank (the input box is blank)-->
	      </div>
	      <br>
	      <button type="submit" name="submit">Submit</button>
      </form>
    </div>
</body>
</html>
