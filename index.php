<?php
	include_once "common/base.php";
	$pageTitle = "Home";
	include_once "common/header.php";
	include_once "inc/constants.php";
	include_once "inc/class.room.manage.php"; ?>
<?php if (isLoggedIn()) : ?>
<script type="text/javascript">var id=<?php echo 1; ?>;</script>
<script type='text/javascript' src="/common/application.js"></script>
<script type='text/javascript' src="/js/jquery.jeditable.min.js"></script>
<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

<div id="add-device-container"><div id="add-device-header">
	<h1>Rooms</h1>
	<input placeholder="Name" type="text" id="room-add-name" length="60"/>
	<button id="room-add-button">Add</button>
</div></div>
<div id="room-list-container" class="list-container">
	<ul>
		<?php echo (new RoomManager())->getRoomsAsListHTML(); ?>
	</ul>
</div>
<?php else : ?>
<div id="padded-container">
	<h1>Welcome to GreenAudit!</h1>
	<p>To get started, please <a href="/sign-up">sign up</a>.</p>
</div>
<?php endif; ?>
<?php include_once "common/footer.php"; ?>