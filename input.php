<?php
    if (isset($_POST['addemployee'])) {

        require_once("dbconn.php");

        $bdate = $_POST['bdate'];
        $fname = $_POST['firstName'];
        $lname = $_POST['lastName'];
        $gender = $_POST['gender'];
        $hdate = $_POST['hdate'];

        $query = $conn->query('SELECT MAX(emp_no) FROM employees WHERE emp_no < 1000');

        $id = (int) $query->fetch()[0];

        $sql = "INSERT INTO employees.employees (emp_no, birth_date, first_name, last_name, gender, hire_date) VALUES (:count, :bdate, :fname, :lname, :gender, :hdate)";

        $result = $conn->prepare($sql);
        $result->bindValue(':count', ++$id);
        $result->bindValue(':bdate', $bdate);
        $result->bindValue(':fname', $fname);
        $result->bindValue(':lname', $lname);
        $result->bindValue(':gender', $gender);
        $result->bindValue(':hdate', $hdate);
        $result->execute();

        $count = $result->rowCount();

        print("Created $count rows.\n");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>New Employee to the Table</title>
    <!--BOOTSTRAP - CDN-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="validation.js"></script>
    <style>
        table, th, tr, td { text-align: center; border: solid 2px black;}
        body {margin: 2%;}
    </style>
</head>
<body>
        <form id="addnewemp" name="empdata" method="post" action="input.php" onsubmit = "return addvalidate()" >

            <p><label>Birth Date: <input type="date" name="bdate" id="bdate" /></label></p>
            <p><label>First Name (Uppercase first letter): <input type="text" name="firstName" id="firstName" /></label></p>
            <p><label>Last Name (Uppercase first letter): <input type="text" name="lastName" id="lastName" /></label></p>
            <p><label>Hire Date: <input type="date" name="hdate" id="hdate" /></label></p>
            <p>Gender: </p>
            <fieldset>
                <input type="radio" id="gender" name="gender" value="M">
                <label for="male">Male </label>
                <input type="radio" id="gender" name="gender" value="F">
                <label for="female">Female </label>
            </fieldset>
            <p><input name="addemployee" type="submit" class="btn btn-primary" id="submit" value="Submit" /></p>
            <p><a href="search.php" class="btn btn-primary">Back</a></p>
        </form>
        <div id = "warning"></div>
    </body>
</html>