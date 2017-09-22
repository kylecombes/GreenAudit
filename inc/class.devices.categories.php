<?php

/**
 * Handles device category requests
 *
 * @author Kyle Combes
 * @copyright 2014 Kyle Combes
 */

class DevCats {
	
	/**
	 * Checks for a database object and creates one if none is found
	 *
	 * @param object $db
	 * @return void
	 */
	public function __construct($db=NULL) {
		if (is_object($db)) {
			$this->_db = $db;
		} else {
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	
	/**
	 * returnParentId is used to know which element to add result under
	 */
	public function getCategories($parentId, $returnParentId) {
		
		$sql = "SELECT id, name, extras FROM dev_cats WHERE parent_id"
			. ( ($parentId == null) ? " IS NULL" : "=".$parentId )
			. " ORDER BY name;";
		if ($stmt = $this->_db->prepare($sql)) {
			$stmt->execute();
			
			$list = "";
			while ($row = $stmt->fetch()) {
				$extras = json_decode($row['extras'], true);
				$list .= '<li class="dev-cat-li" data-id="'.$row['id'].'" data-def-on-usage="'
					.$extras['def_on_usage'].'"><span>'.$row['name'].'</span></li>';
			}
			if ($returnParentId) {
				$res = array('parentId' => $parentId, 'children' => $list);
				$json = json_encode($res);
				return $json;
			}
			return $list;
		}
	}
}

?>