<?php
session_start();

if (isset($_SESSION['sess_student_id']) == null) {
    header('location:Login.php');
}

include_once("config.php");

$sql = "SELECT SemesterCode,Term,Year FROM semester";
$statement = $dbConn->prepare($sql);
$statement->execute();
$result = $statement->fetchAll();

$StudentIdhour = $_SESSION['sess_student_id'];
$totalHour = 16;
$bookedhour = null;
$sqlhour = " SELECT course.*,registration.CourseCode  FROM course,registration 
WHERE  StudentId='$StudentIdhour' and course.CourseCode=registration.CourseCode ";
$statementhour = $dbConn->prepare($sqlhour);
$statementhour->execute();
$resulthour = $statementhour->fetchAll();
foreach ($resulthour as $sumhour) {
    $bookedhour = $bookedhour + $sumhour["WeeklyHours"];
}
$lefthour = $totalHour - $bookedhour;

$errormsg = null;
$bookedwweklyhour = 0;

if (isset($_POST['Submit'])) {
    $registeredCourses = null;
    $SemesterCode = null;

    if (!empty($_POST['coursecode'])) {


        foreach ($_POST['coursecode'] as $value) {
            $StudentId = $_SESSION['sess_student_id'];
            $CourseCode = $value;


            $sqlwweklyhour = "SELECT WeeklyHours From course WHERE CourseCode='$CourseCode' ";
            $statementwweklyhour = $dbConn->prepare($sqlwweklyhour);
            $statementwweklyhour->execute();
            $resultwweklyhour = $statementwweklyhour->fetchAll();
            foreach ($resultwweklyhour as $sumwweklyhour) {
                $bookedwweklyhour = $bookedwweklyhour + $sumwweklyhour["WeeklyHours"];
            }
        }
        if ($bookedwweklyhour <= $lefthour) {

            foreach ($_POST['coursecode'] as $value) {

                try {
                    $StudentId = $_SESSION['sess_student_id'];
                    $CourseCode = $value;

                    $sqlqueryone = "SELECT SemesterCode FROM courseoffer WHERE CourseCode='$CourseCode' ";
                    $statementone = $dbConn->prepare($sqlqueryone);
                    $statementone->execute();
                    $resultone = $statementone->fetchAll();
                    foreach ($resultone as $rowone) {
                        $SemesterCode = $rowone["SemesterCode"];
                        $mystring = $_SESSION['sess_search_text'];
                        if (strpos($mystring, $SemesterCode) !== false) {

                            $sqlquery = "INSERT INTO registration(StudentId,CourseCode,SemesterCode) VALUES(:StudentId, :CourseCode, :SemesterCode)";
                            $query = $dbConn->prepare($sqlquery);

                            $query->bindparam(':StudentId', $StudentId);
                            $query->bindparam(':CourseCode', $CourseCode);
                            $query->bindparam(':SemesterCode', $SemesterCode);
                            $query->execute();
                        }
                    }
                    header('location:CourseSelection.php');
                } catch (PDOException $e) {
                    echo $e;
                }

                // if ($registeredCourses == null)
                //     $registeredCourses = $value;
                // else
                //     $registeredCourses = $registeredCourses . ',' . $value;
            }
        }else{
            $errormsg="Your selection exceed the max weekly hour.";
        }
    } else {
        $errormsg = "You need to select at least one course.";
    }
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />

    <link href="css/bootstrap-select.min.css" rel="stylesheet" />
    <script src="js/bootstrap-select.min.js"></script>
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
                        <li class="active"><a href="#">Course Selection</a></li>
                        <li><a href="CurrentRegistration.php">Current Registration</a></li>
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
        <p>Welcome Wei Gong! (not you? change user <a href="logout.php">here</a>)</p>
        <p>Your have registered <b><?php echo $bookedhour; ?></b> hour for the selected semester.</p>
        <p>Your can register <b><?php echo $lefthour; ?></b> hours course(s) for the semester.</p>
        <p>Please note that the courses you have registered will not displayed in the list.</h5><br>

            <form method="post" name="form2">
                <div style="width: 20%; position: absolute; right: 12.5%;">
                    <select name="multi_search_filter" id="multi_search_filter" multiple class="form-control selectpicker">
                        <?php
                        foreach ($result as $row) {
                            echo '<option value="' . $row["SemesterCode"] . '">' . $row["Year"] . ' ' . $row["Term"] . '</option>';
                        }
                        ?>
                    </select>

                </div>
                <input type="hidden" name="semester_code" id="semester_code" />
                <div style="clear:both"></div>
                <br />
                <p style="color: #FF0000; display:inline; padding-left:2px"><?php echo $errormsg; ?></p>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <br>
                            <br>
                            <tr>
                                <th>Code</th>
                                <th>Course</th>
                                <th>Hours</th>
                                <th>Select</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
                <div style="padding-left: 16%; position: absolute; right: 12.5%">
                    <input style="width: 200px; background-color: #4058df; color: white;  padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;" type="submit" name="Submit" value="Submit">
                    <input style="width: 200px; background-color: #4058df; color: white;  padding: 14px 20px; margin: 8px 0; border: none; border-radius: 4px; cursor: pointer;" type="submit" name="Clear" value="Clear">
                </div>
            </form>
    </div>
</body>

</html>


<script>
    $(document).ready(function() {

        load_data();

        function load_data(sql = '') {
            $.ajax({
                url: "fetch.php",
                method: "POST",
                data: {
                    sql: sql
                },
                success: function(data) {
                    $('tbody').html(data);
                }
            })
        }

        $('#multi_search_filter').change(function() {
            $('#semester_code').val($('#multi_search_filter').val());
            var sql = $('#semester_code').val();
            load_data(sql);
        });

    });
</script>