<?php
$title = "Login";
include("/var/www/html/header-login.php");
$error = false;

	session_start();
    // If form submitted, insert values into the database.
    if (isset($_POST['username'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
		$username = stripslashes($username);
		$password = md5(stripslashes($password));
	//Checking is user existing in the database or not
        $query = $myPDO->prepare("SELECT id FROM users WHERE username=:username and password=:password");
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$result = $query->execute();
		if ($result === false) {
			echo "\nPDO::errorInfo():\n";
			print_r($myPDO->errorInfo());
		}
		$rows = $query->rowCount();
        if($rows==1){
			$_SESSION['username'] = $username; 
			$fetch = $query->fetch(PDO::FETCH_OBJ);
			$_SESSION['userID'] = $fetch->id;
			header("Location: /"); // Redirect user to home
		} else {
			$error = true;
		}
    }
?>
<div id="login">
	<div class="login-logo">
		<img src="/images/fitai-logo-white.svg">
	</div>
	<form action="" method="post" name="login">
		<?php if ($error) : ?>
		<div class="form-error">
			Username or Password is incorrect.
		</div>
		<?php endif; ?>
		<div class="form-field">
			<i class="dripicons-user"></i>
			<input type="text" name="username" placeholder="Username" required />
		</div>
		<div class="form-field">
			<i class="dripicons-lock"></i>
			<input type="password" name="password" placeholder="Password" required /><br>
		</div>
		<div class="form-field submit">
			<input name="submit" type="submit" value="Get Started" />
		</div>
		<div class="login-help">
			<a href="">Need Help?</a>
		</div>
	</form>
	<!--<p>Not registered yet? <a href='registration.php'>Register Here</a></p>-->
</div>


<?php
include('/var/www/html/footer-login.php');
?>
