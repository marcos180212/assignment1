<?php

    require('isLoggedIn.php');
    checkIfLoggedIn();
    require_once("dbconn.php");

    if(isset($_POST['text'])) {
        $text = $_POST['text'];
    }

    if (isset($_POST['emp_delete']) && !empty($_POST['emp_no'])) {

        $empnum = $_POST['emp_no'];

        $sql = "DELETE FROM employees.employees WHERE emp_no = :empnum";

        $delete = $conn->prepare($sql);
        $delete->bindParam(':empnum', $empnum, PDO::PARAM_INT);
        $delete->execute();
        $count = $delete->rowCount();

        print("Deleted $count rows.\n");

    }

    if (isset($_POST['updemp']) && !empty($_POST['firstName']) && !empty($_POST['lastName']))
    {
        $empnum = $_POST['emp_no'];
        $birthdate = $_POST['bdate'];
        $fstname = $_POST['firstName'];
        $lstname = $_POST['lastName'];
        $hiredate = $_POST['hdate'];

        $fileTmpName = $_FILES['usrFle']['tmp_name'];
        $fileOriginalName = $_FILES['usrFle']['name'];
        $file_type = $_FILES['usrFle']['type']; //returns the mimetype
        $allowed = array("image/jpeg", "image/gif", "image/png", "image/jpg");

        if(!in_array($file_type, $allowed)) {
            $error_message = 'Only jpg, gif, and png files are allowed.';

            echo $error_message;
            exit();
        }

        $result = move_uploaded_file($fileTmpName,"uploads/".$fileOriginalName );

        $sql = "UPDATE employees.employees SET birth_date = :birthdate, first_name = :fstname, last_name = :lstname, hire_date = :hiredate WHERE emp_no = :empnum";

        
        $update = $conn->prepare($sql);
        $update->bindParam(':birthdate', $birthdate);
        $update->bindParam(':fstname', $fstname);
        $update->bindParam(':lstname', $lstname);
        $update->bindParam(':hiredate', $hiredate);
        $update->bindParam(':empnum', $empnum, PDO::PARAM_INT);
        $update->execute();

        $count = $update->rowCount();

        print("Updated $count rows.\n");
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
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
<header>
    <table>
        <tr>
            <th> <p>Employee Search: </p></th>
            <th> <p>Employee Data: </p></th>
        </tr>
    <tr>
        <td>
            <form id="myForm" method="post" action="" onsubmit = "return validate()">
                <p>Search Parameters - First, last or full name:</p>
                    <label>
                        <input id ="name" name="text" type="text">
                    </label>
                <p>
                    <input name="search" class="btn btn-primary" type="submit" value="Search">
                </p>
            </form>
            <div id = "warning"></div>
        </td>
        <td>
            <form name="LogoutForm" action="logOut.php" method="post">
                <input type="submit" class="btn btn-primary" name="logoutButton" value="Log Out" />
            </form>
            <form id="empdata" name="empdata" method="post" action="">
                <p><a href="input.php" class="btn btn-primary">Add new Employee</a></p>
            </form>
        </td>
    </tr>
    </table>
</header>
<body>
<table>
    <thead>
        <tr>
            <th>Emp #</th>
            <th>Birth Date</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Gender</th>
            <th>Hire Date</th>
            <th colspan="2">Buttons</th>
        </tr>
    </thead>
    <tbody>
    <?php
        //Search display
        if (isset($_POST['search'])){

            $text = $_POST['text'];

            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }

            $rowsperpage = 25;
            $total_pages_sql = "SELECT COUNT(*) FROM employees.employees";
            $offset = ($pageno-1) * $rowsperpage;

            $result = $conn->prepare($total_pages_sql);
            $result->execute();

            $total_rows = $result->rowCount();
            $total_pages = ceil($total_rows / $rowsperpage);

            $sql = "SELECT * FROM employees.employees WHERE first_name 
                like :first_name OR last_name like :last_name OR CONCAT(first_name,' ',last_name) LIKE :concat 
                LIMIT :offset,:rowsperpage";

            $result = $conn->prepare($sql);
            $result->bindValue(':first_name', "%$text%");
            $result->bindValue(':last_name', "%$text");
            $result->bindValue(':concat', "%$text");
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);
            $result->bindParam(':rowsperpage', $rowsperpage, PDO::PARAM_INT);
            $result->execute();

            while($row = $result->fetch(PDO::FETCH_ASSOC)):?>
                <tr>
                    <td><?php echo $row['emp_no'] ?></td>
                    <td><?php echo $row['birth_date'] ?></td>
                    <td><?php echo $row['first_name'] ?></td>
                    <td><?php echo $row['last_name'] ?></td>
                    <td><?php echo $row['gender'] ?></td>
                    <td><?php echo $row['hire_date'] ?></td>
                    <td>
                    <form method="post" action="updateE.php">
                        <label>
                            <input name= "emp_no" type="hidden" class="btn btn-primary" id="submit" value="<?php echo $row['emp_no'];?>"/>
                            <input name="update_emp" type="submit"
                                       onclick="return confirm('Making changes to Employee #: <?php echo $row['emp_no'] ?>')"
                                       class="btn btn-primary" id="submit" value="Update" />
                        </label>
                    </form>
                    </td>
                    <td>
                    <form method="post" action="updateE.php">
                        <input type="hidden" class="btn btn-primary" id="submit" value="<?php echo $row['emp_no'];?>"/>
                        <label>
                            <input type="submit" class="btn btn-primary" id="submit" value="Delete" />
                        </label>
                    </form>
                    </td>
                </tr>
            <?php endwhile;

            $conn = null;

        }else{

            //Standard display
            if (isset($_GET['pageno'])) {
                $pageno = $_GET['pageno'];
            } else {
                $pageno = 1;
            }
            $rowsperpage = 25;
            $total_pages_sql = "SELECT COUNT(*) FROM employees.employees";
            $offset = ($pageno-1) * $rowsperpage;

            $result = $conn->prepare($total_pages_sql);
            $result->execute();

            $total_rows = $result->rowCount();
            $total_pages = ceil($total_rows / $rowsperpage);

            $sql = "SELECT * FROM employees.employees LIMIT :offset, :rowsperpage";

            $result = $conn->prepare($sql);
            $result->bindParam(':offset', $offset, PDO::PARAM_INT);
            $result->bindParam(':rowsperpage', $rowsperpage, PDO::PARAM_INT);
            $result->execute();

            while($row = $result->fetch(PDO::FETCH_ASSOC)):?>
                <tr>
                    <td><?php echo $row['emp_no'] ?></td>
                    <td><?php echo $row['birth_date'] ?></td>
                    <td><?php echo $row['first_name'] ?></td>
                    <td><?php echo $row['last_name'] ?></td>
                    <td><?php echo $row['gender'] ?></td>
                    <td><?php echo $row['hire_date'] ?></td>
                    <td>
                    <form method="post" action="updateE.php">
                        <label>
                                <input name= "emp_no" type="hidden" class="btn btn-primary" id="submit" value="<?php echo $row['emp_no'];?>"/>
                            <input name="update_emp" type="submit"
                                       onclick="return confirm('Making changes to Employee #: <?php echo $row['emp_no'] ?>')"
                                       class="btn btn-primary" id="submit" value="Update" />
                        </label>
                    </form>
                    </td>
                    <td>
                    <form method="post" action="">
                        <input name= "emp_no" type="hidden" class="btn btn-primary" id="submit" value="<?php echo $row['emp_no'];?>">
                        <label>
                            <input name="emp_delete" type="submit"
                                       onclick="return confirm('Sure to delete Employee #: <?php echo $row['emp_no'] ?>?')"
                                       class="btn btn-primary" id="submit" value="Delete" />
                        </label>
                    </form>
                    </td>
                </tr>
            <?php endwhile;
            $conn = null;
        }
    ?>

    </tbody>

</table>
    <ul class="pagination">
        <li><a href="?pageno=1">First</a></li>
        <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
        </li>
        <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
        </li>
        <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
    </ul>
</body>
</html>