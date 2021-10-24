<?php
    require_once("dbconn.php");

    $emp_no = $_POST['emp_no'];
    $sql = "SELECT * FROM employees.employees WHERE emp_no = :emp_no";

    $result = $conn->prepare($sql);
    $result->bindParam(':emp_no', $emp_no, PDO::PARAM_INT);
    $result->execute();

    $data = $result->fetch(PDO::FETCH_ASSOC);
    $bdate = $data['birth_date'];
    $fname = $data['first_name'];
    $lname = $data['last_name'];
    $gender = $data['gender'];
    $hdate = $data['hire_date'];

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
        body {margin: 2%;}
    </style>
</head>
    <body>
        <h1>Update Employee Data</h1>
        <form id="updemp" name="empdata" enctype="multipart/form-data" method="post"  onsubmit = "return updvalidate()" action="search.php">
            <input name= "emp_no" type="hidden" class="btn btn-primary" id="submit" value="<?php echo $data['emp_no'];?>"/>
            <p><label>Birth Date: <input type="date" name="bdate" id="bdate" value="<?php echo $bdate; ?>" /></label></p>
            <p><label>First Name (Uppercase first letter): <input type="text" name="firstName" id="firstName" value="<?php echo $fname; ?>" /></label></p>
            <p><label>Last Name (Uppercase first letter): <input type="text" name="lastName" id="lastName" value="<?php echo $lname; ?>" /></label></p>
            <p><label>Hire Date: <input type="date" name="hdate" id="hdate" value="<?php echo $hdate; ?>" /></label></p>
            <p>Gender: </p>
            <input type="radio" id="male" name="gender" value="M" <?php if ($gender == "M"){ echo 'checked';}?>>
            <label for="male">Male </label>
            <input type="radio" id="female" name="gender" value="F" <?php if ($gender == "F"){ echo 'checked';}?>>
            <label for="female">Female </label>
            <p>Employee picture:<input type="file" name="usrFle" /></p>
            <p><input name="updemp" type="submit" class="btn btn-primary" id="submit" value="Submit" /></p>
            <p><a href="search.php" class="btn btn-primary">Back</a></p>
        </form>
        <div id = "warning"></div>
    </body>
</html>