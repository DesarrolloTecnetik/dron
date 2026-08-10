<?php
	
	#
		# REQUIRE CONFIG FILE
	#

		require 'core/config.php';
	
	#
		# CLASS SQL
	#

		class StudiosBIT {

			// host name server
			private $host = DB_HOST; 
			private $database = DB_NAME; // name database
			private $charset = DB_CHARSET; // character decode -> UTF8
			private $database_engine = DB_ENGINE; // database engine -> InnoDB
			private $user = DB_USER; // user acces to database
			private $pass = DB_PASS; // pass acces to database
			private $isConnected = false; // keep database register connection
			private $hasExecuted = false; // prevent double execution of same query
			private $options; // options to conect SQL
			private $port; // port to MySQL where that is running
			private $stmt; // var statement to SQL
			private $dsn; // data to connection SQL
			private $_connection;							// var to new PDO

			// run SQL connection
			public function __construct() {

				// start dsn build
				$this->dsn = "mysql:host=$this->host; dbname=$this->database;";

				// if charset is null
				if(empty($this->charset)) {

					// change to -> UTF8
					$this->charset = 'UTF8';

				}

				// get options to SQL
				$this->options = array(

					PDO::ATTR_EMULATE_PREPARES => false, // prevent false data emulation
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,		// change to mode error in SQL
					PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8"	// change SQL to UTF8

				);

				// if port MySQL is different to null
				if(defined('DB_PORT') && !empty(DB_PORT)) {

					// change port  -> predefined in constant
					$this->port = DB_PORT;
					$this->dsn .= "port = $this->port;"; // change var DSN 

				}

				// running SQL connection
				$this->connect();

			}

			// connect to database if no that is running
			private function connect() {

				// if no is running connection
				if($this->isConnected == false) {

					try {

						// create connection -> new PDO
						$this->_connection = new \PDO($this->dsn, $this->user, $this->pass, $this->options);
						$this->isConnected = true;

					} catch (PDOException $e) {

						// show error -> no connect to database
						die('La conexión ha fallado: ' . $e->getMessage());

					}

				}

			}

			// add options for the users -> in array
			public function setOptions(array $options) {

				if($this->isConnected) {

					$this->CloseConnection();

				} else {

					$this->options = $options;
					$this->connect();

				}

			}
		    
			// $query  -> create connection to SQL
			public function query($query) {

				// connect SQL
				$this->connect();
				$this->stmt = $this->_connection->prepare($query);		// prepared sentence

				// $hasExecuted -> false 
				$this->hasExecuted = false;								// its a new query

			}

			// convert bind parameter  $param -> parameter  |  $value -> value to @param  |   $type -> null is no defined
			public function bind($param, $value, $type = null) {

				// if is null $type -> change the parameter
				if( is_null($type) ) {

					// change dependen case
					switch( true ) {

						// its number
						case is_int($value):
						$type = PDO::PARAM_INT;
						break;

						// its bolean
						case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;

						// its null
						case is_null($value):
						$type = PDO::PARAM_NULL;
						break;

						// default value -> string
						default:
						$type = PDO::PARAM_STR;

					}

				}

				// return bindValue correct
				$this->stmt->bindValue($param, $value, $type);

			}

			// execute sentence query SQL
			public function execute() {

				// if $hasExecuted is false  -> no double same query running
				if($this->hasExecuted == false) {

					// star execute
					$this->stmt->execute();

					// if multiplies querys in a row  -> $hasExecuted is true
					if(!$this->_connection->inTransaction()) { 

						// active $hasExecuted  
						$this->hasExecuted = true;				// same double query

					}

				}

			}

			// return one result  -> 1
			public function single() {

				$this->execute();
				return $this->stmt->fetch(PDO::FETCH_ASSOC);

			}

			// return array result -> array()
			public function resultSet() {

				$this->execute();
				return $this->stmt->fetchAll(PDO::FETCH_ASSOC);

			}
			
			// return number result the query		    
			public function rowCount() {

				return $this->stmt->rowCount();

			}

			// return last id insert in table -> id is primary key in DB
			public function lastInsertId() {

				$this->connect();
				return $this->_connection->lastInsertId();

			}

			public function lastID() {

				$this->connect();
				return $this->_connection->lastInsertId();

			}

			// return bool -> allow run multiple changes to database    
			public function beginTransaction() {

				// check type to database
				if($this->notMyISAM()) {

					$this->connect();
					return $this->_connection->beginTransaction();

				}

			}

			// return bool -> true; if database is NOT MyISAM
			private function notMyISAM() {

				// if MyISAM database
				if(strtolower($this->database_engine) == 'myisam') {

					throw new DomainException("Necesita cambiar a un motor de almacenamiento como InnoDB, ya que el motor de almacenamiento MyISAM no es compatible con las transacciones.");

				}

				return true;

			}

			// return bool; rollback a specific transaction
			public function rollBack() {

				if($this->notMyISAM()) {

					// is transaction running?
					if($this->isConnected && $this->_connection->inTransaction()) {

						// execute rollback
						return $this->_connection->rollBack();

					}

				}

			}

			// create transaction permanent (caareful whit the database)
			public function commitTransaction() {

				if($this->notMyISAM()) {

					if($this->isConnected && $this->_connection->inTransaction()) {

						return $this->_connection->commit();

					}

				}

			}

			// dumps information in Prepared Statement
			public function debugDumpParams() {

				return $this->stmt->debugDumpParams();

			}

			// close database connection SQL
			public function CloseConnection() {

				$this->_connection = null;
				$this->isConnected = false;

			}

		}

	#
		# CREATE VAR TO RUN SQL
	#
		
		global $db;
		$db = new StudiosBIT;

?>