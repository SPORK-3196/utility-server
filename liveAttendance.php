<!DOCTYPE html>
<html>
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
?>

<body>
	<div id="formbox_noanim">
		<h1>Sign In/Out</h1>
		
		<form action="addAtt.php" method="post">
			Member: 
			<select name="memID">
				<?php
				$membersResult = $sql->query("SELECT * FROM members ORDER BY lName ASC");

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

		<iframe src="live.php" width="500" height="1600" style="border:none;"></iframe>

	</div>
</body>
</html>
