<?php
session_start();

if (isset($_SESSION['sess_student_id']) == null) {
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
                        <li class="active"><a href="#">Current Registration</a></li>
                        <?php
                        if (isset($_SESSION['sess_student_id']) != null) {
                        ?>
                            <li><a href="Logout.php">Log out</a></li>
                        <?php
                        } else {
                        ?>
                            <li><a href="Login.php">Log in</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
                <!--/.nav-collapse -->
            </div>
            <!--/.container-fluid -->
        </nav>
    </div>







    <div class="footer">
        <p>&#169;Algonquin College 2010-2017. All Rights reserved</p>
    </div>

</body>



</html>