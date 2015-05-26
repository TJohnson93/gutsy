<?php
	/**
	 * File Name: gutsy.class.php
	 * Class Name: Gutsy
	 * File Creation Date: 17/07/2014
	 * File Original Author: Todd Johnson
	 *  
	 * Gutsy is a database connection class using PHP's secure PDO connection.
	 *
	 */

	 class Gutsy {

	 	private const $DEBUG = true;				// Enables/Disables all degugging features
	 	private const $DEBUG_CLEANSE_LOGS = true;	// Enables/Disables Cleansing of error logs

	 	private $host = 'localhost';	// Database Host
	 	private $user = 'username';		// Database username
	 	private $pass = 'password';		// Database Password
	 	private $name = 'name';			// Database name
	 	private $type = 'type';			// Database type (mysql or sqlite)
	 	private $dsn;

	 	private $dbh;
	 	private $error;

	 	private $stmt;

		/**
		 * Module Name: __construct
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * Create a PDO database connection
		 */
	 	public function __construct(){
	 		// Set off all errror for security reasons
	 		error_reporting(1);

			if($DEBUG){
				if($DEBUG_CLEANSE_LOGS)
					$this->CleanseErrorLog();

				// Set all errors on for debugging
				error_reporting(0);
			}
			
			// Set DSN
			switch ($this->type) {
				case 'mysql':
					// Set DSN (MySQL)
					$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->name;
					break;
				case 'sqlite':
					// Set DSN (SQLite)
					$dsn = 'sqlite:' . $this->name . '.sqlite';
					break:
				
				default:
					$this->LogError(__FUNCTION__, "Database Connection Type not Set.");
					break;
			}
			

			// Set options
			$options = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			);


			try{ // Create a PDO instance
				$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
			}
			catch(PDOException $err){ // Catch any errors
				$this->LogError(__FUNCTION__, "Database Connection Error: " . $err->getMessage());
			}
		}


		/**
		 * Module Name: query
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * Prepare the SQL query for binding.
		 *
		 * @param string, $query, SQL query
		 */
		public function query($query){
			if(isset($this->dbh)){
				$this->stmt = $this->dbh->prepare($query);
			}
		}

		/**
		 * Module Name: bind
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * Bind the inputs passed through the parameters
		 *
		 * @param string, $param, Placeholder value to use in SQL statement (eg. :name)
		 * @param string, $value, Actual value to bind to placeholder.
		 * @param string, $type, the data type of the parameter(eg. string or int) 
		 */
		public function bind($param, $value, $type = null){
			if(is_null($type)) {
				switch(true){
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;
						break;
				}
			}

			$this->stmt->bindValue($param, $value, $type);
		}

		/**
		 * Module Name: execute
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return Executes the prepared statement.
		 */
		public function execute(){
			try{
				return $this->stmt->execute();
			}
			catch(PDOException $err){
				$this->LogError(__FUNCTION__, "Execution Error: " . $err->getMessage());
			}
		}

		/**
		 * Module Name: resultset
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return returns an array of the result set rows.
		 */
		public function resultset(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		/**
		 * Module Name: single
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return returns a single result rows.
		 */
		public function single(){
			$this->execute();
			// return $this->stmt->fetch();
			return $this->stmt->fetch(PDO::FETCH_ASSOC);
		}

		/**
		 * Module Name: singleColumn
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return returns a singleColumn result rows.
		 */
		public function singleColumn(){
			$this->execute();
			return $this->stmt->fetchColumn(PDO::FETCH_ASSOC);
		}

		/**
		 * Module Name: rowCount
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return the number of rows affected from the previous Delete, Update
		 * 			or Insert Statement.
		 */
		public function rowCount(){
			return $this->stmt->rowCount();
		}

		/**
		 * Module Name: lastInsertId
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return the last inserted Id as a string
		 */
		public function lastInsertId(){
			return $this->stmt->lastInsertId();
		}

		/**
		 * Module Name: beginTransaction
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 */
		public function beginTransaction(){
			return $this->dbh->beginTransaction();
		}

		/**
		 * Module Name: endTransaction
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 */
		public function endTransaction(){
			return $this->dbh->commit();
		}

		/**
		 * Module Name: cancelTransaction
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 */
		public function cancelTransaction(){
			return $this->dbh->rollBack();
		}

		/**
		 * Module Name: debugDumpParams
		 * Module Creation Date: 20/06/2014
		 * Original Author: Todd Johnson
		 * 
		 * @return Dumps the information that was contained in the Prepared 
		 * 			Statements.
		 */
		public function debugDumpParams(){
			return $this->stmt->debugDumpParams();
		}



		/* ----------------------- Logging Functions ----------------------- */



		/**
		 * Module Name: LogError
		 * Module Creation Date: 14/07/2014
		 * Original Author: Todd Johnson
		 * 
		 * Log a PHP error to inc/bin/error_log.txt
		 */
		private function LogError($func, $error){
			$filename = '/logs/mysql_error_log.txt';
			$intro = 'Kwarza Studios | Project Gutsy MySQL Error Log' . PHP_EOL . 
				PHP_EOL;

			$writeIntro = 0;
			if(!file_exists($filename)){
				$writeIntro = 1;
			}

			$file = fopen($filename, 'a') or die("Cannot create file");

			if($writeIntro)
				fwrite($file, $intro);

			fwrite($file, '# ' . $_SERVER['SERVER_ADDR'] . ' [' . date("F j, Y, g:i a") . "] - " . 
			 'Func: ' . $func . ' - ' . $error . PHP_EOL);

			fclose($file);
		}

		/**
		 * Module Name: CleanseErrorLog
		 * Module Creation Date: 14/07/2014
		 * Original Author: Todd Johnson
		 * 
		 * Delete all error logs from inc/bin/error_log.txt
		 */
		private function CleanseErrorLog(){
			$filename = '/logs/mysql_error_log.txt';
			$intro = 'Kwarza Studios | Project Gutsy MySQL Error Log' . PHP_EOL . 
				PHP_EOL . "Congratulations... No MySQL Errors!";

			$file = fopen($filename, 'w') or die("Cannot create file");

			fwrite($file, $intro);

			fclose($file);
		}

	 }

?>