<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="1; URL=live.php">
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
	<div id="tablebody">

		<?php
		// If mysqli->query() returned false, there was an invalid query
		if ($idList === false)
			die ("<p class=\"error\">Internal query error</p>");
	
		// If no users are found, leave an error
		else if ($idList->num_rows === 0)
			die ("<p class=\"error\">No users in database</p>");
		?>

    	<table class="data">
			<tr>
				<td>First Name</td>
				<td>Last Name</td>
			</tr>

			<?php
				// Loops through all IDs and loads members, outputing them to the table
				// if they are signed in.
				while($memberRow = $idList->fetch_assoc())
				{
					// Loads the member according to the id
					$member = Member::SQL_Load_Member_ID($memberRow['id']);

					// Display error if member couldn't be loaded
					if ($member === false) {
						echo "<tr> <td><p class=\"error\">Member not found</p></td> <td></td> </tr>";
						continue;
					}

					// Refresh sign-in status and output name is present
					$member->Refresh_Sign_Status();
					if($member->signed_in == true)
						echo "<tr> <td>$member->firstName</td> <td>$member->lastName</td> </tr>";
				}
			?>
		</table>
	</div>
</body>
</html>
