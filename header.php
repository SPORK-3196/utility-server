<?php
echo <<< HEADER_END

<div id="header">
	<ul id="navmenu">
		<li><a href="index.php">Home</a></li>
		<li><a href="batteries.php">Batteries</a></li>
		<li class="dropdown"><a href="#" class="dropbtn">Attendance</a>
			<div>
				<a href="liveAttendance.php">Live</a>
				<a href="attendance.php">History</a>
			</div>
		</li>
	</ul>

	<form action="search.php" method="GET">
		<input name="q" type="text" placeholder="Search">
	</form>
</div>

<!-- Pushes the page down to make sure the header doesn't cover content -->
<div style="height: 46px;"></div>

HEADER_END
?>
