<!DOCTYPE html>
<?php
    include("util.php");
    
    // If no ID given, redirect to attendance.php page
    if (!isset($_GET["id"])) {
        header("Location: attendance.php");
        exit();
    }
    

    // Load the member
    $member = Member::SQL_Load_Member_ID($_GET["id"]);

    // Loads attendance records for the member
    $sql = SQL::get_connection();
    $attendanceEntriesResult = $sql->query(
        "SELECT *
        FROM AttendanceData
        WHERE memberID = $member->sql_id
        ORDER BY entryDate DESC
        LIMIT 100;"
    );
?>

<html>
<head>
	<title><?php echo $member->firstName." ".$member->lastName; ?></title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
	<?php include("header.php"); ?>

	<div id="tablebody">
		<h1>Attendance Records</h1>


		<?php
			// If mysqli->query() returned false, there was an invalid query
			if ($attendanceEntriesResult === false)
				die ("<p class=\"error\">User not found</p>");

			// Add a hint telling the number of results found
			echo ("<p class=\"hint\">");
			if ($attendanceEntriesResult->num_rows === 0)
				die ("No results found</p>");
			else
				echo ($attendanceEntriesResult->num_rows." results found</p>");
		?>


		<table class="data">
			<tr>
				<td>Date</td>
				<td>Time in</td>
				<td>Time out</td>
			</tr>

			<?php
				// Iterate through every Attendance Entry
				while($entry = $attendanceEntriesResult->fetch_assoc())
				{
					// Prints the table row
					printf("\t\t\t<tr><td> %s </td><td> %s </td><td> %s </td></tr>\n",
						$entry["entryDate"], $entry["io"], $member->lastName);
				}
			?>
		</table>
	</div>

</body>
</html>
