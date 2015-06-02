# Gutsy
Gutsy is a PHP class that creates an easy to use DRY connection to a MySQL, MS SQL or SQLite database using PHP's secure PDO. Not only does it make the connection but it also allows easier creation of you SQL queries. You wont need to remember the PDO specific parameters or how to work your DSN, Gutsy does this for you. Gutsy also has a degugging function (which can be toggled in your config) that allows you to see any PHP errors whilst active and is hidden (for security reasons) in production mode. Gutsy has a function that creates a folder that contains a MySQL log so you have a record of any errors that may occur.


## How to Use

-------------

### Configuring Gutsy
To configure Gutsy for your database connection edit the `config.php` file change the definitions to your database information.		
**NOTE** - *MS SQL is currently untested!*

	<?php
		define('DB_HOST', 'localhost');			
		define('DB_USER', 'username');			
		define('DB_PASS', 'password');			
		define('DB_NAME', 'database_name');		
		define('DB_TYPE', 'database_type');		// mysql, mssql or sqlite
	?>

### Example of a Config database connection

	<?php
		define('DB_HOST', 'localhost');			
		define('DB_USER', 'root');			
		define('DB_PASS', 'root');			
		define('DB_NAME', 'automobiles_db');		
		
		If the database is a MySQL database use the following
			define('DB_TYPE', 'mysql');		// mysql
		
		If the database is a SQLite database use the following
			define('DB_TYPE', 'sqlite');	//sqlite
		
		If the database is a MS SQL database use the following
			define('DB_TYPE', 'mssql');		//mssql
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
	
Gutsy will automatically determine the type of value and set the PDO parameter `PDO::PARAM_*`.
	
	<?php
		$id = 1;
		$category = "carModel";
		$isAccessible = true;
		
		$gutsy->bind(':id', $id);
		$gutsy->bind(':model', $model);
		$gutsy->bind(':accessAllowed', $isAccessible);
	?>
		
#### Execute Query/Returning results

To execute a query without expecting a result (eg. INSERT, UPDATE or DELETE queries) use the `execute` function.

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

With an expected result of: *(a Ford Mustang with a count of 67180 kms added to the table named Car)* and an output of:

	1 row affected