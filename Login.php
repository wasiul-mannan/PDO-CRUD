<?php
include_once("config.php");
session_start();

$msg = null;
$StudentIdError = null;
$PasswordError = null;

$StudentId = null;
$Password = null;

function ValidateStudentId($StudentId)
{
    $StudentId = trim($StudentId);
    if ($StudentId != "") {
        return true;
    } else {
        return false;
    }
}

function ValidatePassword($Password)
{
    $Password = trim($Password);
    if ($Password != "") {
        return true;
    } else {
        return false;
    }
}
if (isset($_POST['Submit'])) {

    $StudentId = $_POST['StudentId'];
    $Password = $_POST['Password'];

    if (ValidateStudentId($StudentId) == true && ValidatePassword($Password) == true) {
        try {
            $sql = "select * from student where StudentId=:StudentId  and Password=:Password";
            $query = $dbConn->prepare($sql);

            $query->bindParam('StudentId', $StudentId, PDO::PARAM_STR);
            $query->bindValue('Password', $Password, PDO::PARAM_STR);
            $query->execute();
            $count = $query->rowCount();

            $row   = $query->fetch(PDO::FETCH_ASSOC);
            if ($count == 1 && !empty($row)) {

                $_SESSION['sess_student_id'] = $row['StudentId'];

                header('location:CourseSelection.php');
            } else {
                $msg = "Incorrect StudentId and/or Password!";
            }
        } catch (PDOException $e) {
        }
    } else {
        if (ValidateStudentId($StudentId) == false) {
            $StudentIdError = "Student ID is not blank";
        }
        if (ValidatePassword($Password) == false) {
            $PasswordError = "Password is not blank";
        }
    }
}
if (isset($_POST['Clear'])) {
    header('location:Login.php');
}

?>



<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit Calculator</title>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <link href="//fonts.googleapis.com/css?family=Lato&subset=latin,latin-ext" rel="stylesheet" />
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="example3">
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a class="navbar-brand" href="index.php"><img src="img/AC.png">
                    </a>
                </div>
                <div id="navbar3" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-left">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="CourseSelection.php">Course Selection</a></li>
                        <li><a href="CurrentRegistration.php">Current Registration</a></li>
                        <li class="active"><a href="#">Login</a></li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
            <!--/.container-fluid -->
        </nav>
    </div>


    <div style="padding-left: 20%; padding-top: 2%;">
        <h2 style="padding-left: 5%;">Log In</h2>
        <h5>You need to<a href="NewUser.php"> sign up</a> if you are a new user</h5><br>
        <p style="color: #FF0000;"><?php echo $msg; ?></p><br>
        <form method="post" name="form2">
            <label for="studentid">Student ID:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="text" id="studentid" name="StudentId" Value="<?php echo $StudentId; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $StudentIdError; ?></p>
            </div>

            <label for="password">Password:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="password" id="password" name="Password" Value="<?php echo $Password; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $PasswordError; ?></p>
            </div>

            <div style="padding-left: 16%;">
                <input type="submit" name="Submit" value="Submit">
                <input type="submit" name="Clear" value="Clear">
            </div>
        </form>
    </div>




    <div class="footer">
        <p>&#169;Algonquin College 2010-2017. All Rights reserved</p>
    </div>

</body>



</html>