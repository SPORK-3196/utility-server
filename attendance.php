<!DOCTYPE html>
<?php
	include("util.php");

	$sql = SQL::get_connection();

	$attendanceEntriesResult = false;

	// If a member ID is given to the page, load only attendance records from
	// that particular member. Otherwise, load records for all members.
	$WHERE_cond = ((isset($_GET["memID"])) ?
					"WHERE memberID=$_GET[memID] " : " ");
	$attendanceEntriesResult = $sql->query(
		"SELECT *
		FROM AttendanceData
		$WHERE_cond
		ORDER BY id DESC;"
	);
	unset($WHERE_cond);
?>

<html>
<head>
	<title>Attendance Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
	<?php include("header.php"); ?>

	<form action="attendance.php" method="get">
		Search: <input type="text" name="s">
	</form>

	<div id="formbox">
		<h1>Sign In/Out</h1>
		
		<form action="addAtt.php" method="post">
			Member ID: <input type="number" name="memID" min="0"> <br/>
			Output Type:
			<select name="output">
				<option value="redirect">Normal</option>
				<option value="formatted">Arduino</option>
				<option value="">None</option>
				<option value="json">JSON</option>
			</select>
			<input type="submit">
		</form>
	</div>

	<div id="tablebody">
		<h1>Attendance Log</h1>

		<?php
			// If mysqli->query() returned false, there was an invalid query
			if ($attendanceEntriesResult === false)
				die ("<p class=\"error\">Invalid query</p>");

			// Add a hint telling the number of results found
			echo ("<p class=\"hint\">");
			if ($attendanceEntriesResult->num_rows === 0)
				die ("No results found</p>");
			else
				echo ($attendanceEntriesResult->num_rows." results found</p>");
		?>



		<table class="data">
			<tr>
				<td>Timestamp</td>
				<td>First Name</td>
				<td>Last Name</td>
				<td>In/Out</td>
			</tr>

			<?php
				// Iterate through every Attendance Entry
				while($entry = $attendanceEntriesResult->fetch_assoc())
				{
					// Loads member associated with the ID in the entry
					$member = Member::SQL_Load_Member_ID($entry["memberID"]);

					// Skip to next member if this one's invalid
					if($member === false)
						continue;

					// Prints the table row
					printf("\t\t\t<tr><td> %s </td><td> %s </td><td> %s </td><td> %s </td></tr>\n",
						$entry["entryDate"], $member->firstName, $member->lastName, ucfirst($entry["io"]));
				}
			?>
		</table>
	</div>

</body>
</html>
