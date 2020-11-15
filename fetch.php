<?php
session_start();

if (isset($_SESSION['sess_student_id']) == null) {
    header('location:Login.php');
}

include_once("config.php");

if($_POST["sql"] != '')
{
	$search_array = explode(",", $_POST["sql"]);
	$search_text = "'" . implode("', '", $search_array) . "'";
	$sql = "
	SELECT course.*,courseoffer.CourseCode  FROM course,courseoffer 
	WHERE courseoffer.SemesterCode IN (".$search_text.") and course.CourseCode=	courseoffer.CourseCode";
}
else
{
	$sql = "SELECT * FROM course";
}

$query = $dbConn->prepare($sql);

$query->execute();

$result = $query->fetchAll();

$total_row = $query->rowCount();

$output = '';

if($total_row > 0)
{
	foreach($result as $row)
	{
		$output .= '
		<tr>
			<td>'.$row["CourseCode"].'</td>
			<td>'.$row["Title"].'</td>
			<td>'.$row["WeeklyHours"].'</td>
			<td><input type="checkbox" name="coursecode[]" value='.$row["CourseCode"].'></td>
		</tr>
		';
	}
}
else
{
	$output .= '
	<tr>
		<td colspan="5" align="center">No Data Found</td>
	</tr>
	';
}

echo $output;
