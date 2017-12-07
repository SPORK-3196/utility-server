<!DOCTYPE html>
<?php

	include("connect.php");

	$link=Connection();
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
		ID: <input type="number" name="id" min="1" max="99"> <br>
		First Name: <input type="text" name="fName" maxlength="16"> <br>
		Last Name: <input type="text" name="lName" maxlength="16">
		<input type="submit">
	</form>

      <h1>Deregister Tag</h1>
	<form action="deregisterTag.php" method="post">
		First Name: <input type="text" name="fName" maxlength="16"> <br>
		Last Name: <input type="text" name="lName" maxlength="16">
		<input type="submit">
	</form>

      <h1>Tag List</h1>

      <table class="data">
		<tr>
			<td>&nbsp;ID&nbsp;</td>
			<td>&nbsp;First Name&nbsp;</td>
			<td>&nbsp;Last Name&nbsp;</td>
			<td>&nbsp;Long ID&nbsp;</td>
		</tr>

         <?php
	     $result=mysql_query("SELECT * FROM `tags`",$link);
	     if($result!==FALSE) {
		while($row = mysql_fetch_array($result)) {
			$mResult=mysql_query("SELECT * FROM `members` WHERE tagID=$row[tagID]",$link);
			$mRow = mysql_fetch_array($mResult);
	        	printf("<tr><td> &nbsp;%s </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td><td> &nbsp;%s&nbsp; </td>",
           		   $row["id"], $mRow["fName"], $mRow["lName"], $row["tagID"]);
		}
	     }
	     mysql_free_result($result);
	     mysql_close();
         ?>

      </table>
   </div>

</body>
</html>
