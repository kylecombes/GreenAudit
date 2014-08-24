<?php

/**
 * Handles changes to an individual room.
 *
 * @author Kyle Combes
 * @copyright 2014 Kyle Combes
 *
 */
class RoomManager
{
	private $_db;

	public function __construct()
	{
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		$this->_db = new PDO($dsn, DB_USER, DB_PASS);
	}
	
	/** ---------- Reading from Database ---------- **/
	
	/** ----- High-Level ----- **/
	
	public function getRoomsAsListHTML() {
	
		if (isset($_SESSION['Email']) && isset($_SESSION['LoggedIn']) && $_SESSION['LoggedIn'] == 1) {
		$sql = "SELECT ID, name, devices FROM rooms WHERE UserID=(SELECT ID FROM users WHERE Email=:id)";
		if($stmt = $this->_db->prepare($sql))
		{
			$userID = $_SESSION['Email'];
			$stmt->bindParam(':id', $userID, PDO::PARAM_INT);
			$html = '';
			if ($stmt->execute()) {
				while ($room = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$id = $room['ID'];
					$name = $room['name'];
					$usage = $this->getkWhTotal($room['devices']);
					$html .= '<li class="room-list-item" data-id="'.$id.'">'
						. '<table><tr><td><div class="reorder-handle"></div></td>'
						. '<td><span class="room-name">'.$name.'</span></td>'
						. '<td><span class="room-usage">'.$usage.'</span> kWh</td>'
						. '<td><button class="edit-button">Edit</button></td>'
						. '<td><button class="remove-button">Remove</button></td></tr></table>'
						. '</li>';
				}
			}
			$stmt->closeCursor();
			return $html;
		} else {
			return "<li><h1>SQL error</h1></li>";
		}
		
		} else {
			return "<li><h1>Error: Not logged in?</h1></li>";
		}
	}
	
	private function getkWhTotal($json) {
		if ($json == null) { return 0; } // Empty room
		
		$dev_array = json_decode($json, true);
		$totalWh = 0;
		foreach ($dev_array as $dev) {
			$totalWh += ($dev['w'] * $dev['h']);
		}
		return $totalWh / 1000;
	}
	
	/** ----- Individual Rooms ----- **/
	
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
	}
	
	public function getRoomName($id) {
		$sql = "SELECT name FROM rooms WHERE ID=:id";
		if($stmt = $this->_db->prepare($sql))
		{
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();
			$res = $stmt->fetch();
			$name = $res['name'];
			$stmt->closeCursor();
			return $name;
		}
		
		
	}
	
	public function getDevicesAsListHTML($id) {
		if ($id == NULL) { return false; }
		
		$raw = $this->getRoomData($id);
		
		if (strlen($raw['devices']) == 0) return "";
		
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
	
	/** ---------- Writing to Database ---------- **/
	
	/**
	 * Adds a room to the database
	 *
	 * @return mixed    ID of the new room on success, error message on failure
	 */
	public function addNewRoom()
	{
		$sql = "INSERT INTO rooms
					(UserID, name)
				VALUES ( (SELECT ID FROM users WHERE Email=:user), :name)";
		try
		{
			$stmt = $this->_db->prepare($sql);
			$user = $_SESSION['Email'];
			$name = $_POST['name'];
			$stmt->bindParam(':user', $user, PDO::PARAM_STR);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
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
				SET devices=:json
				WHERE ID=:id";
		try {
			$stmt = $this->_db->prepare($sql);
			$id = $_POST['id'];
			$json = $_POST['devices'];
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':json', $json, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();
			error_log("id: ".$id." Devices: ".$json, 3, "/var/www/log/error.log");
			
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
	
	/**
	 * Delete a room in the database
	 *
	 * @return mixed    nothing on success, error message on failure
	 */
	public function deleteRoom()
	{
		$sql = "DELETE FROM rooms WHERE ID=:id";
		try {
			$stmt = $this->_db->prepare($sql);
			$id = $_POST['id'];
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt->closeCursor();
			
		} catch(PDOException $e) {
			return $e->getMessage();
		}
	}
	
	
	
}