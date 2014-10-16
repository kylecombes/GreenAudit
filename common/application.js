var list;

$(document).ready( function() {
	$("#add-device-add-button").on('click', addDevice);
	
	$(document).on('click', '.remove-button', function() {
		$(this).closest(".device-list-item").remove();
		refreshTotals();
	});
	
	list = $('.list-container').children('ul');
	
	list.sortable({
		handle   : ".reorder-handle",
		update   : function(event, ui){
		   
			// Developer, this function fires after a list sort, commence list saving!

		},
		forcePlaceholderSize: true
	});
	
	$(".device-name").editable(renameDevice, {
		tooltip: 'Click to edit',
		style: 'inherit',
		height: 'none',
		cssclass: 'jedit-box'
	} );
	
	$("#save-button").on('click', saveRoom);
	$(".edit-button").on('click', function() {
		roomId = +$(this).closest('.room-list-item').data('id');
		window.location = '/room-edit?id=' + roomId;
	});
	
	$("#room-add-button").on('click', addRoom);
	$(".room-list-item").on('click', '.remove-button', deleteRoom);
	
	refreshTotals();
	
	$("#categories-list").on('click', 'li', catClick);
});

function renameDevice(value) {
	var parent = $(this).closest('span');
	$(this).remove();
	parent.val(value);
}

function addDevice() {
	/** TODO: Input validation **/
	var nameBox = $("#add-device-name");
	var name = nameBox.val().trim();
	var wattBox = $("#add-device-wattage");
	var watts = +wattBox.val();
	var hrBox = $("#add-device-hours");
	var hours = +hrBox.val();
	if (name.length > 0 && watts > 0 && hours > 0) {
		var item = $('<li class="device-list-item" data-watts="'+watts+'" data-hours="'+hours+'">'
			+ '<table><tr><td><div class="reorder-handle"></div></td>'
			+ '<td><span class="device-name">'+name+'</span></td>'
			+ '<td><span class="device-wattage">'+watts+'</span>W</td>'
			+ '<td><span class="device-hours">'+hours+'</span> hours</td>'
			+ '<td><button class="remove-button">Remove</button></td></tr></table>'
			+ '</li>');
		$(list).prepend(item);
	}
	refreshTotals();
	nameBox.val("");
	wattBox.val("");
	hrBox.val("");
	nameBox.focus();
}

function refreshTotals() {
	var whTotal = 0;
	list.children().each(function() {
		var watts = $(this).data('watts');
		var hrs = $(this).data('hours');
		whTotal += (watts * hrs);
	});
	var kwhTotal = whTotal/1000;
	$("#daily-usage").text((kwhTotal).toFixed(2));
	var dailyCost = kwhTotal * 0.33;
	$("#daily-cost").text(dailyCost.toFixed(2));
	$("#monthly-cost").text((dailyCost * 30.4).toFixed(2));
}

function catClick() {
	if ($(this).hasClass('cat-selected') == false) {
		$('.cat-selected').removeClass('cat-selected');
		$(this).addClass('cat-selected');
		
	}
}

function addRoom() {
	var name = $("#room-add-name").val().trim();
	
	if(name.length > 0) {
		$.ajax({
			type: "POST",
			url: "/db-interaction/room-manage.php",
			data: "action=add&name="  + name,
			success: function(result_id) {
				window.location = '/room-edit?id=' + result_id;
			},
			error: saveUnsuccessful
		});
	}
}

function saveRoom() {
	var values = {};
	var pos = 0;
	list.children().each(function() {
		var obj = {
			n: $(this).find('.device-name').text(),
			w: +$(this).data('watts'),
			h: +$(this).data('hours')};
		values[pos++] = obj;
	});
	
	var json = JSON.stringify(values);
	
	/*
	// HTML tag whitelist. All other tags are stripped.
	var $whitelist = '<b><i><strong><em><a>',
		forList = $("#current-list").val(),
		newListItemText = strip_tags(cleanHREF($("#new-list-item-text").val()), $whitelist),
		URLtext = escape(newListItemText),
		newListItemRel = $('#list li').size() 1;
	*/
	
	if(json.length > 0) {
		$.ajax({
			type: "POST",
			url: "/db-interaction/room-manage.php",
			data: "action=update&id=" + id + "&devices=" +  json,
			success: saveSuccessful,
			error: saveUnsuccessful
		});
	}
}

function deleteRoom() {
	li = $(this).closest('.room-list-item');
	id = li.data('id');
	if (id) {
		$.ajax({
				type: "POST",
				url: "/db-interaction/room-manage.php",
				data: "action=delete&id=" + id,
				success: saveSuccessful,
				error: saveUnsuccessful
			});
	}
	li.remove();
}
	

function saveSuccessful(theResponse) {
	
}

function saveUnsuccessful(response) {
	alert("Save unsuccessful: " + response);
}
