<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="10; URL=liveAttendance.php">
<head>
	<title>In shop?</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>

<?php
	// Creates as SQL connection, queries for all member IDs
	include("util.php");
	$sql = SQL::get_connection();
	$idList = $sql->query("SELECT id FROM members");


	// If mysqli->query() returned false, there was an invalid query
	if ($idList === false)
		die ("<p class=\"error\">Internal query error</p>");
	
	// If no users are found, leave an error
	else if ($idList->num_rows === 0)
		die ("<p class=\"error\">No users in database</p>");
?>

<body>
	<div id="formbox_noanim">
		<h1>Sign In/Out</h1>
		
		<form action="addAtt.php" method="post">
			Member: 
			<select name="memID">
				<?php
				$membersResult = $sql->query("SELECT * FROM members ORDER BY id ASC");

				if($membersResult === FALSE) echo("<option value='-1'>Error</option>");
				else if ($membersResult->num_rows == 0) echo("<option value='-1'>No members</option>");
				else {
					while($memberEntry = $membersResult->fetch_assoc()) {
						if($memberEntry === false) continue;

						echo "<option value='";
						echo $memberEntry["id"];
						echo "'>";
						echo $memberEntry["fName"];
						echo " ";
						echo $memberEntry["lName"];
						echo "</option>";
					}
				}
				?>
			</select> <br/>
			Output Type:
			<select name="output">
				<option value="redirectLive">Normal</option>
				<option value="redirect">Back to Full Attendance</option>
				<option value="formatted">Arduino</option>
				<option value="">None</option>
				<option value="json">JSON</option>
			</select>
			<input type="submit" value="Sign In/Out">
		</form>
	</div>

	<div id="tablebody">
		<h1>Who's in the shop?</h1>

    	<table class="data">
			<tr>
				<td>Name</td>
				<td>Time in</td>
			</tr>

			<?php
				// Loops through all IDs and loads members, outputing them to the table
				// if they are signed in.
				while($memberID = $idList->fetch_assoc()['id'])
				{
					$member = Member::SQL_Load_Member_ID($memberID);
					$member->Refresh_Sign_Status();
					if($member->signed_in == 1) {
						echo "<tr> <td>$member->firstName</td> <td>$member->lastName</td> </tr>";
					}
				}
			?>
		</table>
	</div>
</body>
</html>
