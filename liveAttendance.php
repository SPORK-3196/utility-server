<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="2; URL=liveAttendance.php">
<head>
	<title>In shop?</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>

<body>
	<div id="tablebody">
		<h1>Who's in the shop?</h1>

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
					if($member->signed_in) {
						echo "<tr> <td>$member->firstName</td> <td>$member->lastName</td> </tr>";
					}
				}
			?>
		</table>
	</div>
</body>
</html>
