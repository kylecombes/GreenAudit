<?php

/**
 * Handles device list interactions within the app
 *
 * @author Kyle Combes
 * @copyright 2014 Kyle Combes
 *
 */
class DeviceListManager
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

	/**
	 * Loads all list items associated with a user ID
	 *
	 * This function both outputs <li> tags with list items and returns an
	 * array with the list ID, list URL, and the order number for a new item.
	 *
	 * @return array    an array containing the room name and devices (as JSON)
	 */
	public function getRoomData($id)
	{
		$sql = "SELECT name, devices FROM rooms WHERE ID=:id";
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();
			$res = $stmt->fetch();
			$name = $res['name'];
			$devices_json = $res['devices'];
			$stmt->closeCursor();
			$data = array( 'id'=> $id,
				'name'=> $name,
				'devices'=> $devices_json );
			return $data;
		}
/*
			// If there aren't any list items saved, no list ID is returned
			if(!isset($LID))
			{
				$sql = "SELECT ListID, ListURL
						FROM lists
						WHERE UserID = (
							SELECT UserID
							FROM users
							WHERE Username=:user
						)";
				if($stmt = $this->_db->prepare($sql))
				{
					$stmt->bindParam(':user', $_SESSION['Username'], PDO::PARAM_STR);
					$stmt->execute();
					$row = $stmt->fetch();
					$LID = $row['ListID'];
					$URL = $row['ListURL'];
					$stmt->closeCursor();
				}
			}
		}
		else
		{
			echo "tttt<li> Something went wrong. ", $db->errorInfo, "</li>n";
		}

		return array($LID, $URL, $order);*/
	}
	
	public function getRenderedHTML($id) {
		
		$raw = $this->getRoomData($id);
		$name = $raw['name'];
		$dev_array = json_decode($raw['devices'], true);
		
		$res = "";
		foreach ($dev_array as $dev) {
			
			$name = $dev['n'];
			$watts = $dev['w'];
			$hours = $dev['h'];
			$res .= '<li class="device-list-item" data-watts="'.$watts.'" data-hours="'.$hours.'">'
			. '<table><tr><td><div class="reorder-handle"></div></td>'
			. '<td><span class="device-name">'.$name.'</span></td>'
			. '<td><span class="device-wattage">'.$watts.'</span>W</td>'
			. '<td><span class="device-hours">'.$hours.'</span> hours</td>'
			. '<td><button class="remove-button">Remove</button></td></tr></table>'
			. '</li>';
		}
		return $res;
	}
	
	/**
	 * Adds a room to the database
	 *
	 * @return mixed    ID of the new room on success, error message on failure
	 */
	public function addNewRoom()
	{
		$sql = "INSERT INTO rooms
					(UserID, name, devices)
				VALUES ( (SELECT ID FROM users WHERE Email=:user), :name, :json)";
		try
		{
			$stmt = $this->_db->prepare($sql);
			$user = $_SESSION['Email'];
			$name = $_POST['name'];
			$json = $_POST['devices'];
			$stmt->bindParam(':user', $user, PDO::PARAM_STR);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':json', $json, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();

			return $this->_db->lastInsertId();
		}
		catch(PDOException $e)
		{
			return $e->getMessage();
		}
	}
	
	/**
	 * Update a room in the database
	 *
	 * @return mixed    nothing on success, error message on failure
	 */
	public function updateRoom()
	{
		$sql = "UPDATE rooms
				SET name=:name, devices=:json
				WHERE ID=:id";
		try {
			$stmt = $this->_db->prepare($sql);
			$id = $_POST['id'];
			$name = $_POST['name'];
			$json = $_POST['devices'];
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':json', $json, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();
			
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
}
?>