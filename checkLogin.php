<?php

    session_start();
    ob_start();

    require_once("dbconn.php");

    $loginUser = $_POST['loginUser'];
    $loginPwd = $_POST['loginPwd'];

    $loginUser = stripslashes($loginUser);
    $loginPwd = stripslashes($loginPwd);

    $password = password_hash($loginPwd, PASSWORD_BCRYPT);

    try {
        $sql = "CALL login(:loginUser)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':loginUser', $loginUser, PDO::PARAM_STR);
        $stmt->execute();

        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $hash = $row['password'];

            if (password_verify($loginPwd, $hash)) {
                echo 'Password is valid!';
                $_SESSION['LoggedInUser'] = $loginUser;
                header('location:search.php');
            } else {
                echo 'Invalid password.';
                echo "<h1>Incorrect Login</h1><br/>";
                echo "<a href='index.html'>Try again</a>";
            }
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    
    $conn = null;

    ob_end_flush();