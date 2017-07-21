#!/bin/bash

# This function asks the user a yes/no question
user_decision(){
	read -r -p "$1 [y/N] " RESP
	case "$RESP" in
		[yY][eE][sS]|[yY])
			return 0
			;;
		*)
			return 1
			;;
	esac
}


# Check for the site files and verify their integrity
if ! sh VerifySite.bash; then
	echo "Site folder is missing files. Check log.txt for details"
fi


# Make sure dependencies are in place
if ! sh VerifyDep.bash; then
	echo "All dependencies must be installed for setup to run"
	exit 1
fi





# Get the MySQL username and password
echo ""
echo "Setting up SQL..."
read -r -p "  SQL Username: " SQL_USER
read -r -p "  SQL Password: " SQL_PASS
if [ ${#SQL_USER} -eq 0 ]; then SQL_USER="root"
fi
if [ ${#SQL_PASS} -eq 0 ]; then	SQL_PASS="password"
fi

# Attempt to access MySQL using the given username and password
if ! mysql -u $SQL_USER -p"$SQL_PASS" -e ";"; then
	echo "  Unable to sign in to MySQL"
	exit 1
fi

# Get name of Database, and attempt to create it
read -r -p "  What would you like to call your database? [default='Workshop'] " DB_NAME
if [ ${#DB_NAME} -eq 0 ]; then
	DB_NAME="Workshop"
fi

mysql -u $SQL_USER -p"$SQL_PASS" -e "

CREATE DATABASE $DB_NAME;

USE $DB_NAME;

CREATE TABLE members(id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tagID INT(11) UNSIGNED NOT NULL, fName VARCHAR(16) NOT NULL, lName VARCHAR(16) NOT NULL);

CREATE TABLE AttendanceData(id SMALLINT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY, entryDate TIMESTAMP NOT NULL, memberID int(10) UNSIGNED NOT NULL, io TINYINT(1) UNSIGNED NOT NULL);

CREATE TABLE tags(id SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, tagID INT(11) UNSIGNED NOT NULL);

CREATE TABLE BatteryData(entryDate TIMESTAMP PRIMARY KEY, class CHAR(1) NOT NULL, id TINYINT(1) UNSIGNED NOT NULL, status VARCHAR(16) NOT NULL, charge TINYINT(3) UNSIGNED NOT NULL, v0 DECIMAL(5,3) NOT NULL, v1 DECIMAL(5,3) NOT NULL, v2 DECIMAL(5,3) NOT NULL, rint DECIMAL(4,3) NOT NULL, event VARCHAR(16) NOT NULL);"



# Update the connection.php file to reflect the username and password and database
echo ""
echo "Saving PHP connection values..."
echo "  Creating backup"
sed -i.backup -e s/PLACEHOLDER_User/$SQL_USER/ -e s/PLACEHOLDER_Pass/$SQL_PASS/ -e s/PLACEHOLDER_DB/$DB_NAME/ connect.php





# Moves the site files to the var/www/html/ directory
echo ""
if user_decision "Would you like to create a symlink to the current file location within the /var/www/ directory? "; then
	echo "  Creating Symlink in /var/www/"
	sudo ln -s -f $PWD /var/www
	BASENAME=`sudo basename "$PWD"`
	sudo mv /var/www/$BASENAME /var/www/html
else
	exit 0
fi
