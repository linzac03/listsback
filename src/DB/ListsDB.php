<?php

namespace Drupal\lists\DB;

class ListsDB {
	private $_connection;
	private static $_instance;
	
  public static function getInstance() {
		if(!self::$_instance) self::$_instance = new self();
		return self::$_instance;
	}

	public function __construct() {
		$this->_connection = new \mysqli("*****", "******", "******", "******"); 
	
		// Error handling
		if($this->_connection->connect_error) {
			\Drupal::logger('lists')->error('An Error occurred connecting to DB' . $this->_connection->error);
			echo "An error has occured";
		}
	}

	private function __clone() { }
	
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
