<!DOCTYPE html>
<?php
	include("util.php");

	$sql = SQL::get_connection();

	$batteryEntryResults = $sql->query(
		"SELECT *
		FROM BatteryData
		ORDER BY entryDate DESC;");
?>

<html>
<head>
	<title>Battery Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
	<?php include("header.php"); ?>

	<div id="formbox">
		<h1>Add Entry</h1>
		
		<form action="addBatt.php" method="post">
			Class: <input type="text" name="class" maxlength="1">
			ID: <input type="number" name="id" min="0" max="15"> <br/>
			Status: <input type="radio" name="status" value=0> Good
				<input type="radio" name="status" value=1> Needs Charge <br/>
			Charge: <input type="number" name="charge" min="0" max="255"> <br/>
			V0: <input type="number" name="v0" min="0" max="16.384" step="0.001"> <br/>
			V1: <input type="number" name="v1" min="0" max="16.384" step="0.001"> <br/>
			V2: <input type="number" name="v2" min="0" max="16.384" step="0.001"> <br/>
			RInt: <input type="number" name="rint" min="0" max="0.001" step="0.001"> <br/>
			<select name="event" form="event">
				<option value=0>On charger</option>
				<option value=1>Off charger</option>
				<option value=2>On robot</option>
				<option value=3>Off robot</option>
				<option value=4>CHG->RBT</option>
				<option value=5>RBT->CHG</option>
				<option value=6>CHG->STR</option>
				<option value=7>STR->CHG</option>
				<option value=8>Checkup</option>
			</select>
			<input type="submit">
		</form>
	</div>

	<div id="tablebody">
		<h1>Battery Log</h1>


		<?php
			// If mysqli->query() returned false, there was an invalid query
			if ($batteryEntryResults === false)
				die ("<p class=\"error\">Invalid query</p>");

			// Add a hint telling the number of results found
			echo ("<p class=\"hint\">");
			if ($batteryEntryResults->num_rows === 0)
				die ("No results found</p>");
			else
				echo ($batteryEntryResults->num_rows." results found</p>");
		?>


		<table class="data">
			<tr>
				<td>Timestamp</td>
				<td>Class</td>
				<td>ID</td>
				<td>Status</td>
				<td>Charge</td>
				<td>V0</td>
				<td>V1</td>
				<td>V2</td>
				<td>RInt</td>
				<td>Event</td>
			</tr>

			<?php
				// Loop through all the results, row by row
				while($row = $batteryEntryResults->fetch_row())
				{
					// Open a table row, then iterate over the row and output
					// each field into a table cell. Finally, close the row.
					echo("<tr>");
					foreach($row as $field)
						echo ("<td>$field</td>");
					echo("</tr>");
				}
			?>
		</table>
	</div>

</body>
</html>
