<!DOCTYPE html>
<?php
	// Get a SQL connection
	include("util.php");
	$sql = SQL::get_connection();

	$searchQuery = $_GET['q'];
?>

<html>
<head>
	<title>Matches for "<?php echo $searchQuery ?>" </title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
	<?php include("header.php"); ?>

	<div id="tablebody">
		<?php
			// If there was no query, print message
			if ($searchQuery === null)
			{
				echo "<h1> No Query Given </h1>";
				echo "<p class=\"error\"> This page needs to be given a query </p>";
				exit();
			}

			// TODO: Move this functionality to the Member class?
			// Get any members matching the search
			$results = $sql->query(
				"SELECT *
				FROM members
				WHERE fName LIKE '%$searchQuery%'
					OR lName LIKE '%$searchQuery%';");



			// Print a message if query failed
			if ($results === false) {
				echo "<h1> No Matches Found </h1>";
				echo "<p class=\"error\"> Try a smaller search </p>";
				exit();
			}

			
			// Print title and a little hint
			echo ("<h1> Members </h1>");
			echo ("<p class=\"hint\"> Showing all $results->num_rows matches for
			\"$searchQuery\" </p>");

			// Opens the table and prints the title column
			echo ("<table class=\"data\">
				<tr><td> Name </td></tr>");

			// Populates the table with matching members
			while($row = $results->fetch_assoc()) {
				printf("\n\t\t\t<tr><td> %s </td></tr>",
					$row["fName"]." ".$row["lName"]);
			}

			// Closes the table
			echo ("</table>");


			// TODO: Get any Attendence instances matching the search
		?>
	</div>
</body>
</html>
