<?php


/**
 * The SQL class provides static public functions to allow communication with
 * the MySQL database on the system.
 */
class SQL
{
	/**
	 * Forms a connection to the local SPORKdata database. Program will die if
	 * this function fails.
	 *
	 * @return mysqli 	Returns a mysqli object that is connected to the SQL
	 * 					database.
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

	var $firstName;
	var $lastName;

	var $signed_in;	// Whether the member is signed in or not



	/**
	 * Initializes all members to null
	 */
	function __construct() {
		$this->sql_id		= null;
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
			ORDER BY id DESC
			LIMIT 1;");
		
		// If no record was found, set signed_in to null to indicate unknown
		if ($lastEntry === false || $lastEntry->num_rows == 0)
			$this->signed_in = null;

		// Set the user's signed in status according to the io enum (can either
		// be 'in' or 'out')
		else
			$this->signed_in = $lastEntry->fetch_assoc()['io'] == 'in';
	}

	/**
	 * Signs out this user by generating a new Attendance record. If the user
	 * is already signed out, nothing will happen.
	 *
	 * @return void
	 */
	function Sign_Out()
	{
		// Get a SQL connection
		$sql = SQL::get_connection();

		// Make sure data is up-to-date
		$this->Refresh_Sign_Status();

		// If the user is signed in, create a new record to sign them out
		if ($this->signed_in) {
			$sql->query(
				"INSERT INTO AttendanceData
					(memberID, io)
				VALUES
					($this->sql_id,	'out');");

			// Resync user's sign status with database
			$this->Refresh_Sign_Status();
		}
	}
	
	/**
	 * Signs this user in by generating a new Attendance record. If the user
	 * is already signed in, nothing will happen.
	 *
	 * @return void
	 */
	function Sign_In()
	{
		// Get a SQL connection
		$sql = SQL::get_connection();

		// Make sure data is up-to-date
		$this->Refresh_Sign_Status();

		// If the user is signed out, create a new record to sign them in
		if (!$this->signed_in) {
			$sql->query(
				"INSERT INTO AttendanceData
					(memberID, io)
				VALUES
					($this->sql_id,	'in');");

			// Resync user's sign status with database
			$this->Refresh_Sign_Status();
		}
	}



	/**
	 * Loads a Member from the SQL database. Searches by member ID
	 *
	 * @param uint $id			Member's ID
	 * @return Member			Returns a Member variable populated with all
	 * 							the member's information. Returns false if no
	 * 							match was found.
	 */
	static function SQL_Load_Member_ID($ID)
	{
		// Get a SQL connection
		$sql = SQL::get_connection();
		
		// Query for user with id
		$user_matches = $sql->query(
			"SELECT *
			FROM members
			WHERE id=$ID
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
			"SELECT id
			FROM members
			WHERE fName LIKE '%$first%' AND lName LIKE '%$last%'
			LIMIT 1;");
		
		// False if no matches
		if ($user_matches->num_rows == 0)
			return false;

		// Get the member's ID, then pass it to SQL_Load_Member_ID and return
		// the results
		$id = $user_matches->fetch_assoc()['id'];
		return Member::SQL_Load_Member_ID($id);
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
		$tag_matches = $sql->query(
			"SELECT memberID
			FROM tags
			WHERE tagValue=\"$tagValue\"
			LIMIT 1;");

		// False if a tag couldn't be found
		if ($tag_matches->num_rows == 0)
			return false;

		// Get the member's ID, load the member by that ID and return results
		$id = $tag_matches->fetch_assoc()['memberID'];
		return Member::SQL_Load_Member_ID($id);
	}
}
?>



<?php if ($_GET["debug"]) { ?>
<!-- Some debug html and php.  -->
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


		$user2 = Member::SQL_Load_Member_Name("j", "c");
		//if ($user2 === false) die("RIPPERONI");
		echo ("User 2: ".$user2->sql_id." ".$user2->firstName." ".$user2->lastName." ".$user2->signed_in."<br/>");

		$user3 = Member::SQL_Load_Member_ID(3);
		echo ("User 3: ".$user3->sql_id." ".$user3->firstName." ".$user3->lastName." ".$user3->signed_in."<br/>");
	?>

</body>
</html>
<?php } ?>
