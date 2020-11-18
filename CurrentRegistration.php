<?php
session_start();

if (isset($_SESSION['sess_student_id']) == null) {
    header('location:Login.php');
}

include_once("config.php");


if (isset($_POST['Submit'])) {

    if (!empty($_POST['coursecode'])) {
        foreach ($_POST['coursecode'] as $value) {
            $StudentId = $_SESSION['sess_student_id'];
            $CourseCode = $value;

            $sql = "DELETE FROM registration WHERE StudentId = :StudentId and CourseCode = :CourseCode";
            $stmt = $dbConn->prepare($sql);
            $stmt->bindValue(':StudentId', $StudentId);
            $stmt->bindValue(':CourseCode', $CourseCode);
            $stmt->execute();
            return $stmt->rowCount();
        }
    }
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


    <div class="container">
        <br />
        <h2 style="padding-left: 5%;">Course Selection</h2>
        <p>Helo <b>Wei Gong!</b> (not you? change user <a href="logout.php">here</a>). the folowings are your current registrations</p>

        <form method="post" name="form2">
            <div style="clear:both"></div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <br>
                        <br>
                        <tr>
                            <th>Year</th>
                            <th>Term</th>
                            <th>Course Code</th>
                            <th>Course Title</th>
                            <th>Hours</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $StudentId = $_SESSION['sess_student_id'];
                        $sqlregistration = "SELECT course.*,registration.*  FROM course,registration 
                        WHERE registration.StudentId IN (" . $StudentId . ") and course.CourseCode=	registration.CourseCode  
                        ";
                        $query = $dbConn->prepare($sqlregistration);
                        $query->execute();
                        $result = $query->fetchAll();
                        $total_row = $query->rowCount();

                        $sqlsemester = "SELECT semester.*,registration.*  FROM semester,registration 
                        WHERE registration.StudentId IN (" . $StudentId . ") and semester.semesterCode=	registration.semesterCode  
                        ";
                        $querysemester = $dbConn->prepare($sqlsemester);
                        $querysemester->execute();
                        $resultsemester = $querysemester->fetchAll();
                        foreach ($resultsemester as $rows) {
                            $Term[] = $rows["Term"];
                            $Year[] = $rows["Year"];
                        }
                        $i = 0;
                        $output = '';
                        $WeeklyHour = null;
                        $msg = "Total Weekly Housrs";
                        if ($total_row > 0) {
                            foreach ($result as $row) {
                                if ($i != 0)
                                    if ($Term[$i] != $Term[$i - 1] || $Year[$i] != $Year[$i - 1]) {
                                        $WeeklyHour = $WeeklyHour + $row["WeeklyHours"];
                                        $output .= '
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>' . $msg . '</td>
                                        <td>' . $WeeklyHour . '</td>
                                        <td></td>
                                        ';
                                    }

                                $output .= '
                                    <tr>
                                        <td>' . $Term[$i] . '</td>
                                        <td>' . $Year[$i] . '</td>
                                        <td>' . $row["CourseCode"] . '</td>
                                        <td>' . $row["Title"] . '</td>
                                        <td>' . $row["WeeklyHours"] . '</td>
                                        <td><input type="checkbox" name="coursecode[]" value=' . $row["CourseCode"] . '></td>
                                    </tr>
                                    ';

                                $i++;
                            }
                        } else {
                            $output .= '
                                    <tr>
                                        <td colspan="5" align="center">No Data Found</td>
                                    </tr>
                                    ';
                        }

                        echo $output;
                        ?>
                    </tbody>
                </table>

            </div>
            <div style="padding-left: 16%; position: absolute; right: 12.5%">
                <input style="width: 200px; background-color: #4058df; color: white;  padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;" type="submit" name="Submit" value="Delete Selected">
                <input style="width: 200px; background-color: #4058df; color: white;  padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;" type="submit" name="Clear" value="Clear">
            </div>
        </form>
    </div>




    <div class="footer">
        <p>&#169;Algonquin College 2010-2017. All Rights reserved</p>
    </div>

</body>



</html>