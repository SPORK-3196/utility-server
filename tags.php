<!DOCTYPE html>
<?php
	include("util.php");

	$sql = SQL::get_connection();
?>

<html>
<head>
	<title>Tag List</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
<?php include("header.php"); ?>

	<div id="tablebody">
		<h1>Register Tag</h1>

		<form action="registerTag.php" method="post">
			Tag ID: <input type="number" name="tagID" min="1" max="99"> <br>
			Member: <select name="memID">
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
		</select>
			<input type="submit">
		</form>

		<h1>Deregister Tag</h1>
		<form action="deregisterTag.php" method="post">
			Tag ID: <input type="number" name="tagID" min="1" max="99">
			<input type="submit">
		</form>

		<h1>Tag List</h1>

		<table class="data">
		<tr>
			<td>ID</td><td>First Name</td><td>Last Name</td><td>Long ID</td>
		</tr>

	<?php
		$tagResult = $sql->query("SELECT * FROM tags;");
		if($tagResult === FALSE) die("Query error #1");

		while($tagRow = $tagResult->fetch_assoc()) {
			$member = Member::SQL_Load_Member_ID($tagRow["memberID"]);

			echo "<tr><td>$tagRow[id]</td><td>$member->firstName</td><td>$member->lastName</td><td>$tagRow[tagValue]</td>";
		}
	?>

      </table>
   </div>

</body>
</html>
