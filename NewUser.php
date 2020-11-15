<?php
include_once("config.php");
session_start();

$StudentIdError = null;
$NameError = null;
$PhoneError = null;
$PasswordError = null;
$PasswordAgainError = null;

$StudentId = null;
$Name = null;
$Phone = null;
$Password = null;
$PasswordAgain = null;


function ValidateStudentId($StudentId)
{
    $StudentId = trim($StudentId);
    if ($StudentId != "") {
        return true;
    } else {
        return false;
    }
}

function ValidateName($Name)
{
    $Name = trim($Name);
    if ($Name != "") {
        return true;
    } else {
        return false;
    }
}

function ValidatePhone($Phone)
{
    if (preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $Phone)) {
        return true;
    } else {
        return false;
    }
}

function ValidatePassword($Password)
{
    if (strlen($Password) <= 5) {
        return false;
    } elseif (!preg_match("#[0-9]+#", $Password)) {
        return false;
    } elseif (!preg_match("#[A-Z]+#", $Password)) {
        return false;
    } elseif (!preg_match("#[a-z]+#", $Password)) {
        return false;
    } else {
        return true;
    }
}

function ValidatePasswordAgain($PasswordAgain)
{
    $Password = $_POST['Password'];
    if ($PasswordAgain != $Password) {
        return false;
    } else {
        return true;
    }
}


if (isset($_POST['Submit'])) {
    $StudentId = $_POST['StudentId'];
    $Name = $_POST['Name'];
    $Phone = $_POST['Phone'];
    $Password = $_POST['Password'];
    $PasswordAgain = $_POST['PasswordAgain'];

    if (ValidateStudentId($StudentId) == true && ValidateName($Name) == true && ValidatePhone($Phone) == true && ValidatePassword($Password) == true && ValidatePasswordAgain($PasswordAgain) == true) {
        try {
            $sql = "INSERT INTO student(StudentId, Name, Phone, Password) VALUES(:StudentId, :Name, :Phone, :Password)";
            $query = $dbConn->prepare($sql);

            $query->bindparam(':StudentId', $StudentId);
            $query->bindparam(':Name', $Name);
            $query->bindparam(':Phone', $Phone);
            $query->bindparam(':Password', $Password);
            $query->execute();

            header('location:CourseSelection.php');
        } catch (PDOException $e) {
            $StudentIdError = "A student with this id has aleady signed up";
            echo $StudentIdError;
        }
    } else {
        if (ValidateStudentId($StudentId) == false) {
            $StudentIdError = "Student id is not blank";
        }
        if (ValidateName($Name) == false) {
            $NameError = "Name is not blank";
        }
        if (ValidatePhone($Phone) == false) {
            $PhoneError = "Phone number has wrong format.";
        }
        if (ValidatePassword($Password) == false) {
            $PasswordError = "At least 6 characters long, contains at least one upper case, one lowercase and one digit.";
        }
        if (ValidatePasswordAgain($PasswordAgain) == false) {
            $PasswordAgainError = "Two password has to be same size";
        }
    }
}
if (isset($_POST['Clear'])) {
    header('location:NewUser.php');
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
                        <li><a href="Login.php">Login</a></li>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
            <!--/.container-fluid -->
        </nav>
    </div>


    <div style="padding-left: 20%; padding-top: 2%;">
        <h2 style="padding-left: 5%;">Sign Up</h2>
        <h5>All fields are required</h5><br>
        <form method="post" name="form1">
            <label for="studentid">Student ID:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="text" id="studentid" name="StudentId" Value="<?php echo $StudentId; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $StudentIdError; ?></p>
            </div>

            <label for="name">Name:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="text" id="name" name="Name" Value="<?php echo $Name; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $NameError; ?></p>
            </div>

            <label for="phone">Phone Number:<br><span>(nnn-nnn-nnnn)</span></label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="text" id="phone" name="Phone" Value="<?php echo $Phone; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $PhoneError; ?></p>
            </div>

            <label for="password">Password:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="password" id="password" name="Password" Value="<?php echo $Password; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $PasswordError; ?></p>
            </div>

            <label for="cpassword">Password Again:</label>
            <div style="padding-left: 15%; display:block;">
                <input style="display: inline;" type="password" id="cpassword" name="PasswordAgain" Value="<?php echo $PasswordAgain; ?>">
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $PasswordAgainError; ?></p>
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