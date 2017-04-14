<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();

	$aResult=mysql_query("SELECT * FROM `AttendanceData` ORDER BY `entryDate` DESC",$link);
?>

<html>
<head>
	<title>Attendance Log</title>
	<link rel="stylesheet" type="text/css" href="theme.css" />
	<link rel="stylesheet" type="text/css" href="header.css" />
</head>



<body>
<?php include("header.php"); ?>

   <div id="tablebody">
      <h1>Attendance Log</h1>

      <table class="data">
		<tr>
			<td>&nbsp;Timestamp&nbsp;</td>
			<td>&nbsp;First Name&nbsp;</td>
			<td>&nbsp;Last Name&nbsp;</td>
			<td>&nbsp;In/Out&nbsp;</td>
		</tr>

         <?php
		  if($aResult!==FALSE) {
		     while($aRow = mysql_fetch_array($aResult)) {
			$mResult=mysql_query("SELECT * FROM `members` WHERE tagID=$aRow[tagID]",$link);
			if($mResult!==FALSE) {
				$mRow = mysql_fetch_array($mResult);
			}
		        printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td>",
		           $aRow["entryDate"], $mRow["fName"], $mRow["lName"], $aRow["io"]);
		     }
		     mysql_free_result($result);
		     mysql_close();
		  }
         ?>

      </table>
   </div>

</body>
</html>
