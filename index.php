<!DOCTYPE html>
<?php

	include("connect.php"); 	
	
	$link=Connection();

	$result=mysql_query("SELECT * FROM `BatteryData` ORDER BY `entryDate` DESC",$link);
?>

<html>
<head>
	<title>Battery Log</title>
	<link rel="stylesheet" type="text/css" href="/theme.css" />
</head>



<body>
<?php include("/header.php"); ?>

   <h1>Battery Log</h1>

   <table class="data">
		<tr>
			<td>&nbsp;Timestamp&nbsp;</td>
			<td>&nbsp;Class&nbsp;</td>
			<td>&nbsp;ID&nbsp;</td>
			<td>&nbsp;Status&nbsp;</td>
			<td>&nbsp;Charge&nbsp;</td>
			<td>&nbsp;V0&nbsp;</td>
			<td>&nbsp;V1&nbsp;</td>
			<td>&nbsp;V2&nbsp;</td>
			<td>&nbsp;RInt&nbsp;</td>
			<td>&nbsp;Event&nbsp;</td>
		</tr>

      <?php 
		  if($result!==FALSE){
		     while($row = mysql_fetch_array($result)) {
		        printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td></tr>", 
		           $row["entryDate"], $row["class"], $row["id"], $row["status"], $row["charge"], $row["v0"], $row["v1"], $row["v2"], $row["rint"], $row["event"]);
		     }
		     mysql_free_result($result);
		     mysql_close();
		  }
      ?>

   </table>
</body>
</html>
