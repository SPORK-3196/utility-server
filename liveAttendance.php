<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="1; URL=liveAttendance.php">
<head>
	<title>Attendance Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>

<body>
   <div id="tablebody">
      <h1>Live Attendance</h1>

      <table class="data">
		<tr>
			<td>&nbsp;First Name&nbsp;</td>
			<td>&nbsp;Last Name&nbsp;</td>
		</tr>

         <?php
		include("connect.php");

		$link = Connection();

		$mResults = mysql_query("SELECT * FROM members");

		if($mResults == FALSE) { die("Error"); }

		while($mRow = mysql_fetch_array($mResults)) {
			if($mRow["inShop"] == "1" && !($mRow["fName"] == "Master" && $mRow["lName"] == "Key")) {
				echo "<tr> <td>&nbsp;$mRow[fName]&nbsp;</td> <td>&nbsp;$mRow[lName]&nbsp;</td> </tr>";
			}
		}
         ?>

      </table>
   </div>

</body>
</html>
