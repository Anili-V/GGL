<?php
$msg = '';
$msgClass = '';

session_start();

if (isset($_POST['submit'])) {

	include 'database.php';

	$uid = mysqli_real_escape_string($conn, $_POST['uid']);
	$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

	//Error Handlers
	//Check if inputs are inputy
	if (empty($uid) OR empty($pwd)){
		$msg = 'Please fill in all fields';
		$msgClass = 'alert-danger';
	} else {
		$sql = "SELECT * FROM users WHERE user_uid='$uid' OR user_email='$uid'";
		$result = mysqli_query($conn, $sql);
		$resultCheck = mysqli_num_rows($result);
		if ($resultCheck < 1) {
			$msg = 'Incorrect Username';
			$msgClass = 'alert-danger';
		} else {
			if ($row = mysqli_fetch_assoc($result)){
				//De-hashing the password
				$hashedPwdCheck = password_verify($pwd, $row['user_pwd']);
				if ($hashedPwdCheck === false) {
					$msg = 'Incorrect Password';
					$msgClass = 'alert-danger';
				} elseif ($hashedPwdCheck === true) {
					//Login the user here
					$_SESSION['u_id'] = $row['user_id'];
					$_SESSION['u_first'] = $row['user_first'];
					$_SESSION['u_last'] = $row['user_last'];
					$_SESSION['u_email'] = $row['user_email'];
					$_SESSION['u_uid'] = $row['user_uid'];
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
		      <label>Username/Email</label>
		      <input type="text" name="uid" value="<?php echo isset($_POST['uid']) ? $uid : ''; ?>">
	      </div>
	      <div>
	      	<label>Email</label>
	      	<input type="text" name="pwd" value="<?php echo isset($_POST['pwd']) ? $pwd : ''; ?>"> <!-- In the code if an error occurs for one field than all the fields inputted data is deleted. So the value attribute checks whether some data has been inputted and if yes than the data is shown if no then it is blank (the input box is blank)-->
	      </div>
	      <br>
	      <button type="submit" name="submit">Submit</button>
      </form>
    </div>
</body>
</html>
