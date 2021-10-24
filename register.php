<?php 

require_once("dbconn.php");

if (isset($_POST['username']) && isset($_POST['password']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if(!exists($username) && checkIsStrongPassword($password)) {
		try {
			$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

			$stmt = $conn->prepare($sql);
			$stmt->bindParam(':username', $username);
	  		$stmt->bindParam(':password', $password);

	  		$password = password_hash($password, PASSWORD_BCRYPT);
	  		$stmt->execute();

	        echo "New records created successfully";
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}
}

function exists($username)
{
	GLOBAL $conn;

	$sql = "SELECT * FROM users WHERE username = :username";

	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':username', $username, PDO::PARAM_STR);
	$stmt->execute();

	$count = $stmt->rowCount();

	if ($count > 0) {
		return true;
	}

	return false;
}


function checkIsStrongPassword($password)
{
	// Validate password strength
	$uppercase = preg_match('@[A-Z]@', $password);
	$lowercase = preg_match('@[a-z]@', $password);
	$number    = preg_match('@[0-9]@', $password);
	$specialChars = preg_match('@[^\w]@', $password);

	if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
	    return false;
	}
	
	return true;
}

$conn = null;

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>
	<form method="post">
		<fieldset>
			<legend>Create User</legend>
			<label for="">Username</label>
			<input name="username" type="text">

			<label for="">Password</label>
			<input name="password" type="password">

			<button name="save">save</button>
		</fieldset>
	</form>
</body>
</html>