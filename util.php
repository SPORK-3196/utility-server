<?php


/**
 * The SQL class provides static public functions to allow communication with
 * the MySQL database on the system.
 */
class SQL
{
	/**
	 * Forms a connection to the local SPORKdata database
	 *
	 * @return mysqli 	Returns a mysqli object that is connected to the SQL
	 * 					database
	 */
	static function get_connection() {
		// Create a new MySQL Connection
		$mysqli = new mysqli("localhost", "root", "pass", "SPORKdata");

		// If there was a connection error
		if ($mysqli->connect_errno)
			die ("Failed to connect to SQL database");

		return $mysqli;
	}
}

/**
 * The Member class is used to represent a team member in the SQL database
 */
class Member
{
	var $sql_id;	// Member's unique id in MySQL Database
	var $tag_id;	// The unique MySQL id of the member's tag
	var $tag_value;	// The scanner value of the user's tag

	var $firstName;
	var $lastName;

	var $signed_in;	// Whether the member is signed in or not



	/**
	 * Initializes all members to null
	 */
	function __construct() {
		$this->sql_id		= null;
		$this->tag_id		= null;
		$this->tag_value	= null;
		$this->firstName	= null;
		$this->lastName		= null;
		$this->signed_in	= null;
	}



	/**
	 * Reads the last attendance log to determine whether the member is
	 * currently signed in or out
	 * 
	 * @return void				Changes member variable signed_in to hold the
	 * 							appropriate value
	 */
	function Refresh_Sign_Status()
	{
		// Get a SQL connection, query for last attendance record
		$sql = SQL::get_connection();
		$lastEntry = $sql->query(
			"SELECT io
			FROM AttendanceData
			WHERE memberID=$this->sql_id
			ORDER BY entryDate DESC
			LIMIT 1;");
		
		// If no record was found, set signed_in to null to indicate unknown
		if ($lastEntry === false || $lastEntry->num_rows == 0)
			$this->signed_in = null;

		// Set the user's signed in status according to the io enum (can either
		// be 'in' or 'out')
		else
			$this->signed_in = $lastEntry->fetch_assoc()['io'] == 'in';
		

		
		// Update members' cached copy? TODO: Determine if needed and fix
		//mysql_query("UPDATE members SET inShop='$io' WHERE id=$memberID");
	}



	/**
	 * Loads a Member from the SQL database. Searches by member ID
	 *
	 * @param uint $id			Member's ID
	 * @return Member			Returns a Member variable populated with all
	 * 							the member's information. Returns false is no
	 * 							match was found.
	 */
	static function SQL_Load_Member_ID($ID)
	{
		// Get a SQL connection
		$sql = SQL::get_connection();
		
		// Query for user with id
		$user_matches = $sql->query("SELECT * FROM members WHERE id=$ID;");

		// False if no matches
		if ($user_matches->num_rows == 0)
			return false;

		// Take the first match, return it
		$entry = $user_matches->fetch_assoc();
		$mem = new Member;

		$mem->sql_id 		= $entry["id"];
		$mem->firstName 	= $entry["fName"];
		$mem->lastName		= $entry["lName"];
		$mem->signed_in		= $entry["inShop"];

		return $mem;
	}

	/**
	 * Loads a Member from the SQL database. Searches by member name fragments
	 * using wildcards, i.e. '%name%'. If two names are evnely matched by the
	 * wildcards, the member with the lower id is preferred
	 *
	 * @param string $first		Member's first name
	 * @param string $last		Member's last name
	 * @return Member			Returns a Member variable populated with all
	 * 							the member's information. Returns false if no
	 * 							match was found.
	 */
	static function SQL_Load_Member_Name($first, $last)
	{
		// Get a SQL connection
		$sql = SQL::get_connection();

		// Query for users with like names
		$user_matches = $sql->query(
			"SELECT *
			FROM members
			WHERE fName LIKE '%$first%' AND lName LIKE '%$last%'
			LIMIT 1;");

		// False if no matches
		if ($user_matches->num_rows == 0)
			return false;

		// Take the first match, return it
		$entry = $user_matches->fetch_assoc();
		$mem = new Member;

		$mem->sql_id 		= $entry["id"];
		$mem->firstName 	= $entry["fName"];
		$mem->lastName		= $entry["lName"];
		$mem->signed_in		= $entry["inShop"];

		return $mem;
	}

	/**
	 * Loads a Member from the SQL database. Searches by associated tag value.
	 *
	 * @param int $tagValue		The value of the tag member should be looked for by
	 * @return Member			Returns a Member variable populated with all
	 * 							the member's information. Returns false if no
	 * 							match was found.
	 */
	static function SQL_Load_Member_Tag($tagValue)
	{
		// Get a SQL connection
		$sql = SQL::get_connection();

		// Query for the matching tag
		$tag_matches = $sql->query("SELECT * FROM tags WHERE tagValue=\"$tagValue\";");

		// False if a tag couldn't be found
		if ($tag_matches->num_rows == 0)
			return false;

		// Set the member's tag information
		$tagEntry = $tag_matches->fetch_assoc();
		$mem = new Member;

		$mem->tag_id		= $tagEntry["tagID"];
		$mem->tag_value		= $tagEntry["tagValue"];
		$mem->sql_id		= $tagEntry["memberID"];


	
		// Query for the member represented by the id
		$user_matches = $sql->query("SELECT * FROM members WHERE id=\"$mem->sql_id\";");

		// False if we don't find a matching member
		if ($user_matches->num_rows !== 1)
			return false;

		// Set the member's personal information
		$membEntry = $user_matches->fetch_assoc();

		$mem->firstName		= $membEntry["fName"];
		$mem->lastName		= $membEntry["lName"];
		$mem->signed_in		= $membEntry["inShop"];

		return $mem;
	}
}
?>



<!-- Some debug html and css -->
<?php if (true) { ?>
<!DOCTYPE html>
<html>
<head>
</head>

<body>

	<?php
		$user = Member::SQL_Load_Member_Tag(3122121131);
		$user->signed_in = null;
		$user->Refresh_Sign_Status();
		echo ("User 1: ".$user->sql_id." ".$user->firstName." ".$user->lastName." ".$user->signed_in."<br/>");


		$user2 = Member::SQL_Load_Member_Name("p", "a");
		//if ($user2 === false) die("RIPPERONI");
		echo ("User 2: ".$user2->sql_id." ".$user2->firstName." ".$user2->lastName." ".$user2->signed_in."<br/>");

		$user3 = Member::SQL_Load_Member_ID(3);
		echo ("User 3: ".$user3->sql_id." ".$user3->firstName." ".$user3->lastName." ".$user3->signed_in);
	?>

</body>
</html>
<?php } ?>
