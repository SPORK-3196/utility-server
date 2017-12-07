<?php
include "connect.php";

class SQL
{
	static function get_connection() {
		// Create a new MySQL Connection
		$mysqli = new mysqli("localhost", "root", "godbot", "SPORKdata");

		// If there was a connection error, set error message and return NULL
		if ($mysqli->connect_errno)
			die ("Failed to connect to SQL database");

		// Otherwise, return the connection object
		return $mysqli;
	}
}

class Member
{
	var $sql_id;	// Member's unique id in MySQL Database
	var $tag_id;	// The unique MySQL id of the member's tag
	var $tag_value;	// The scanner value of the user's tag

	var $firstName;
	var $lastName;

	var $signed_in;	// Whether the member is signed in or not



	// Initialize to all NULL
	function __construct() {
		$this->sql_id		= NULL;
		$this->tag_id		= NULL;
		$this->tag_value	= NULL;
		$this->firstName	= NULL;
		$this->lastName		= NULL;
		$this->signed_in	= NULL;
	}



	// Loads member according to name
	static function SQL_Load_Member_Name($first, $last)
	{
		// Get a connection
		$sql = SQL::get_connection();

		$user_matches = $sql->query("SELECT * FROM members WHERE fName LIKE \"%$first%\" OR lName LIKE \"%$last%\";");

		$entry = $user_matches->fetch_assoc();
		$mem = new Member;

		$mem->sql_id 		= $entry["id"];
		$mem->firstName 	= $entry["fName"];
		$mem->lastName		= $entry["lName"];
		$mem->signed_in		= $entry["inShop"];

		return $mem;
	}

	// Loads a member according to tag value
	static function SQL_Load_Member_Tag($tagValue)
	{
		//Get a connection
		$sql = SQL::get_connection();

		$tag_matches = $sql->query("SELECT * FROM tags WHERE tagValue=\"$tagValue\";");

		$tagEntry = $tag_matches->fetch_assoc();
		$mem = new Member;

		$mem->tag_id		= $tagEntry["tagID"];
		$mem->tag_value		= $tagEntry["tagValue"];
		$mem->sql_id		= $tagEntry["memberID"];


		$user_matches = $sql->query("SELECT * FROM members WHERE id=\"$mem->sql_id\";");

		$memEntry = $user_matches->fetch_assoc();

		$mem->firstName		= $memEntry["fName"];
		$mem->lastName		= $memEntry["lName"];
		$mem->signed_in		= $memEntry["inShop"];

		return $mem;
	}
}
?>



<?php if (false) { ?>
<html>
<head>
</head>

<body>

	<?php
		$user = Member::SQL_Load_Member_Tag(3122121131);
		echo ($user->sql_id." ".$user->firstName." ".$user->lastName." ".$user->signed_in);
	?>

</body>
</html>
<?php } ?>
