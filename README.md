# Gutsy
A class to connect to a MySQL or SQLite database using PHP's secure PDO connection.

## How to Use

-------------

### Configuring Gutsy
To configure Gutsy initialise the following lines within gutsy.class.php

	<?php
		private $host = 'localhost';	// Database Host
	 	private $user = 'username';		// Database username
	 	private $pass = 'password';		// Database Password
	 	private $name = 'name';			// Database name
	 	private $type = 'type';			// Database type (mysql or sqlite)
	?>

### Initialising Gutsy

By initialising Gutsy you also make a connection to the SQL database defined above.

	<?php 
		include_once('gutsy.class.php');
		$gutsy = new Gutsy();
	?>
	
### Gutsy's Functions

#### Preparing a query

	<?php
		$sql = "SELECT * FROM Table";
		$gutsy->query($sql);
	?>
	
#### Binding the query's placeholders
	
Gutsy will automatically determine the type of value and set the PDO parameter `PDO::PARAM_STR`.
	
	<?php
		$id = 1;
		$category = "carModel";
		$isAccessible = true;
		
		$gutsy->bind(':id', $id);
		$gutsy->bind(':model', $model);
		$gutsy->bind(':accessAllowed', $isAccessible);
	?>
		
#### Returning results
		
To return a single row use the `single` function.
		
	<?php
		$gutsy->single();
	?>

To return an array of results use the `resultset` function.

	<?php
		$gutsy->resultset();
	?>
	
#### Return Row Count

To return a count on any Update, Insert or Delete Queries use `rowCount`.

	<?php
		$gutsy->rowCount();
	?>