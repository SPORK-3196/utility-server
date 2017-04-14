<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();

	$result=mysql_query("SELECT * FROM `BatteryData` ORDER BY `entryDate` DESC",$link);
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
      <form action="add.php" method="post">
         Class: <input type="text" name="class" maxlength="1">
         ID: <input type="number" name="id" min="0" max="15"> <br>
         Status: <input type="radio" name="status" value=0> Good
              <input type="radio" name="status" value=1> Needs Charge <br>
         Charge: <input type="number" name="charge" min="0" max="255"> <br>
         V0: <input type="number" name="v0" min="0" max="16.384" step="0.001"> <br>
         V1: <input type="number" name="v1" min="0" max="16.384" step="0.001"> <br>
         V2: <input type="number" name="v2" min="0" max="16.384" step="0.001"> <br>
         RInt: <input type="number" name="rint" min="0" max="0.001" step="0.001"> <br>
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
   </div>

</body>
</html>
