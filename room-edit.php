<?php
	include_once "common/base.php";
	include_once "inc/class.room.manage.php";
	include_once "inc/class.devices.categories.php";
	$requiresLogin = true;
	if (isset($_GET['id']) && $_GET['id'] !== "") {
		$id = $_GET['id'];
		$rm = new RoomManager();
		$roomName = $rm->getRoomName($id);
		$pageTitle = $roomName;
	}
	include_once "common/header.php";
	include_once "inc/constants.php"; ?>
<script type="text/javascript">var id=<?php echo $id ?>;</script>
<script type='text/javascript' src="/common/application.js"></script>
<script type='text/javascript' src="/js/jquery.jeditable.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<div id="add-device-container">
	<div id="add-device-header">
		<h1><?php echo $roomName ?></h1>
		<button id="save-button">Save Room</button>
		<button id="add-cancel-button">Add</button>
	</div>
	<div style="float:left;width:35%;display:none;">
		<table>
			<tr><td>Daily power usage:</td><td><span id="daily-usage"></span> kW/h</td></tr>
			<tr><td>Daily electricity cost:</td><td>$<span id="daily-cost"></span></td></tr>
			<tr><td>Monthly electricity cost:</td><td>$<span id="monthly-cost"></span></td></tr>
		</table>
	</div>
	<div id="add-device-details-container">
		<div id="categories-container" class="list-container">
			<ul id="categories-list"><?php echo (new DevCats())->getCategories(null) ?></ul>
		</div>
		<div id="add-device-fields-container">
			<label>Name (optional)</label>
			<input type="text" id="add-device-name"/>
			<div id="add-device-power-container">
				<label>Power consumption (watts)</label>
				<input type="number" id="add-device-wattage"/>
			</div>
			<div id="add-device-hours-container">
				<label>Hours on per day</label>
				<input type="number" id="add-device-hours"/>
			</div>
			<div id="add-device-button-container">
				<button id="add-device-add-button">Add</button>
				<button id="add-device-cancel-button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<div id="device-list-container" class="list-container">
	<ul>
		<?php if (is_object($rm)) {
			echo $rm->getDevicesAsListHTML($id);
		} ?>
	</ul>
</div>

<?php include_once "common/footer.php"; ?>