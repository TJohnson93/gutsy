<?php
	/**
	 * File Name: config.class.php
	 * File Creation Date: 02/06/2015
	 * File Original Author: Todd Johnson
	 *  
	 * This is the configuration file for the class Gutsy.
	 *
	 */

	// Set all Database information here.
	define('DB_HOST', 'localhost');			// Set your database host
	define('DB_USER', 'username');			// Set your database username
	define('DB_PASS', 'password');			// Set your database password
	define('DB_NAME', 'database_name');		// Set your database name
	define('DB_TYPE', 'database_type');		// Set your database type (mysql, mssql or sqlite)

	// Set debugging on/off (true/false).
	define('DEBUG', true);


	if(DEBUG){
		error_reporting(0); // Set off all errror for security reasons
	} else{
		error_reporting(1); // Set all errors on for debugging
	}

?>