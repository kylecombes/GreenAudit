<?php

/**
 * Handles the list of rooms, including adding and deleting them.
 *
 * @author Kyle Combes
 * @copyright 2014 Kyle Combes
 *
 */
class RoomManager
{
	/**
	 * The database object
	 *
	 * @var object
	 */
	private $_db;

	/**
	 * Checks for a database object and creates one if none is found
	 *
	 * @param object $db
	 * @return void
	 */
	public function __construct($db=NULL)
	{
		if(is_object($db))
		{
			$this->_db = $db;
		}
		else
		{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	
	
	
}
?>