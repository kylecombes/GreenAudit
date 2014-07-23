<?php
	include_once "common/base.php";
	$pageTitle = "Home";
	include_once "common/header.php";
	include_once "inc/constants.php";
	include_once "inc/class.room.list.php"; ?>
<script type="text/javascript">var id=<?php echo 1; ?>;</script>
<script type='text/javascript' src="/common/application.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<div id="add-device-container">
	<h1>Room</h1>
	<button id="save-button" style="float:right;">Save Room</button>
	<div style="float:left;width:65%;">
		<input placeholder="Name" type="text" id="device-add-name" length="40"/>
		<input placeholder="Wattage" type="number" id="device-add-wattage" length="40"/>
		<input placeholder="Hours on per day" type="number" id="device-add-hours" length="40"/>
		<button id="device-add-button">Add</button>
	</div>
	<div style="float:left;width:35%;">
		<table>
			<tr><td>Daily power usage:</td><td><span id="daily-usage"></span> kW/h</td></tr>
			<tr><td>Daily electricity cost:</td><td>$<span id="daily-cost"></span></td></tr>
			<tr><td>Monthly electricity cost:</td><td>$<span id="monthly-cost"></span></td></tr>
		</table>
	</div>
</div>
<div id="device-list-container">
	<ul id="device-list">
		<?php echo (new DeviceListManager())->getRenderedHTML(1); ?>
	</ul>
</div>

<?php include_once "common/footer.php"; ?>