<?php
/*
Author: Javed Ur Rehman
Website: https://htmlcssphptutorial.wordpress.com
*/
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Registration</title>
<link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php
	require('includes/connect.php');
    // If form submitted, insert values into the database.
    if (isset($_POST['username'])){
        $username = $_POST['username'];
		$email = $_POST['email'];
        $password = $_POST['password'];
		$username = stripslashes($username);
		$email = stripslashes($email);
		$password = md5(stripslashes($password));
		$trn_date = date("Y-m-d H:i:s");
        $query = $myPDO->prepare("INSERT INTO users (username, password, email, trn_date) VALUES (:username, :password, :email, :trn_date)");
		$query->bindParam(':username', $username, PDO::PARAM_STR);
		$query->bindParam(':password', $password, PDO::PARAM_STR);
		$query->bindParam(':email', $email, PDO::PARAM_STR);
		$query->bindParam(':trn_date', $trn_date, PDO::PARAM_STR);
        //$result = $myPDO->query($query);
		$result = $query->execute();
        if($result) {
            echo "<div class='form'><h3>You are registered successfully.</h3><br/>Click here to <a href='login.php'>Login</a></div>";
        }
		else if($result === false) {
			echo "\nPDO::errorInfo():\n";
			print_r($myPDO->errorInfo());
		}
		else 
			echo "Nope";
    }else{
?>
<div class="form">
	<h1>Registration</h1>
	<form name="registration" action="" method="post">
		<input type="text" name="username" placeholder="Username" required />
		<input type="email" name="email" placeholder="Email" required />
		<input type="password" name="password" placeholder="Password" required />
		<input type="submit" name="submit" value="Register" />
	</form>
</div>
<?php } ?>
</body>
</html>
