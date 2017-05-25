<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();
?>

<html>
<head>
	<title>Matches for "<?php echo $q ?>" </title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
<?php include("header.php"); ?>
<div id="tablebody">
	<?php
		$q = _GET['q'];

		// If there was no query, print message
		if ($q == "")
		{
			echo "<h1> Query Not Found </h1>";
			echo "<p> Nothing does not exist in our databases </p>";
			exit();
		}
	?>



	<?php
		// Get any members matching the search
		$results = mysql_query("SELECT * FROM `members` WHERE fName LIKE '%$q%' OR lName LIKE '%$q%';");

		// Print any members
		if ($results !== FALSE) { ?>

			<h1> Members </h1>
			<table class="data">
			<tr>
				<td> Name </td>
			</tr>

			<?php while($row = mysql_fetch_array($results)) {
				printf("<tr><td> %s </td></tr>",
				$row["fName"] . " " . $row["lName"]);
			}
			mysql_free_result($results);

			?> </table> <?php
		}
	?>


	<?php
		// Get any Attendence instances matching the search
	?>

	<?php mysql_close(); ?>
</div>
</body>
</html>
