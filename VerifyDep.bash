#!/bin/bash


# This function takes the given program name and verifies if it is installed or not
verify_program(){
	local PROG_NAME=$1
	PROG_DIR=`command -v $PROG_NAME`
	if [ ${#PROG_DIR} -gt 0 ]; then
		echo "  $PROG_NAME is installed"
		return 0
	else
		echo "  $PROG_NAME is not installed"
		return 1
	fi
}

# This function asks the user if they would to install a program
install_program(){
	local PROG_NAME=$1
	read -r -p "  Would you like to install $PROG_NAME? [y/N] " RESP
	case "$RESP" in
		[yY][eE][sS]|[yY])
			echo "  Installing..."
			return 0
			;;
		*)
			return 1
			;;
	esac
}





echo "Verifying Dependencies..."
ALL_INSTALLED=0
DEP_INSTALLED=1


# System Updates
if install_program "Updates"; then
	sudo apt-get update
	DEP_INSTALLED=0
fi


# Apache
if ! verify_program "apache2"; then
	if install_program "Apache"; then
		sudo apt-get install apache2
		DEP_INSTALLED=0
	else
		ALL_INSTALLED=1
	fi
fi


# MySQL
if ! verify_program "mysql"; then
	if install_program "MySQL Server"; then
		sudo apt-get install mysql-server
		mysql_secure_installation
		DEP_INSTALLED=0
	else
		ALL_INSTALLED=1
	fi
fi


# PHP
if ! verify_program "php5"; then
	if install_program "PHP"; then
		sudo apt-get install php5 php-pear php5-mysql
		DEP_INSTALLED=0
	else
		ALL_INSTALLED=1
	fi
fi


# Restart Apache if a installation was performed
if [ $DEP_INSTALLED -eq 0 ]; then
	echo "  Restarting Apache..."
	sudo service apache2 restart
fi


# Return whether all are installed or not
return $ALL_INSTALLED
