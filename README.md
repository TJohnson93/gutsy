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
		
#### Execute Query/Returning results

To execute a query without expecting a result (eg. INSERT, UPDATE or DELETE queries) use the `execute` query.

	<?php
		$gutsy->execute();
	?>
		
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
	
### Complete Example
	
Below is an example of the total code needed to use Gutsy.
	
	<?php
		include_once('gutsy.class.php');
		$gutsy = new Gutsy();
			
		$carMake = 'Ford';
		$carModel = 'Mustang';
		$kms = 67180;
			
		$sql = 'INSERT INTO car (carMake, carModel, kilometers) 
			VALUES (:carMake, :carModel, :kms)';
			
		$gutsy->query($sql);
		$gutsy->bind(':kms', $kms);
		$gutsy->bind(':carMake', $carMake);
		$gutsy->bind(':carModel', $carModel);
		$gutsy->execute();
		$gutsy->rowCount();
	?>