$(document).ready( function() {
	$("#device-add-button").on('click', addDevice);
	
	$(document).on('click', '.remove-button', function() {
		$(this).closest(".device-list-item").remove();
		refreshTotals();
	});
	
	$("#device-list").sortable({
		handle   : ".reorder-handle",
		update   : function(event, ui){
		   
			// Developer, this function fires after a list sort, commence list saving!

		},
		forcePlaceholderSize: true
	});
	
	$("h1").text("Room "+id);
	
	$("#save-button").on('click', saveRoom);
	
	refreshTotals();
});

function addDevice() {
	/** TODO: Input validation **/
	var nameBox = $("#device-add-name");
	var name = nameBox.val().trim();
	var wattBox = $("#device-add-wattage");
	var watts = +wattBox.val();
	var hrBox = $("#device-add-hours");
	var hours = +hrBox.val();
	if (name.length > 0 && watts > 0 && hours > 0) {
		var item = $('<li class="device-list-item" data-watts="'+watts+'" data-hours="'+hours+'">'
			+ '<table><tr><td><div class="reorder-handle"></div></td>'
			+ '<td><span class="device-name">'+name+'</span></td>'
			+ '<td><span class="device-wattage">'+watts+'</span>W</td>'
			+ '<td><span class="device-hours">'+hours+'</span> hours</td>'
			+ '<td><button class="remove-button">Remove</button></td></tr></table>'
			+ '</li>');
		$("#device-list").prepend(item);
	}
	refreshTotals();
	nameBox.val("");
	wattBox.val("");
	hrBox.val("");
	nameBox.focus();
}

function refreshTotals() {
	var whTotal = 0;
	$("#device-list").children().each(function() {
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

function saveRoom() {
	var name = "A Room";
	var values = {};
	var pos = 0;
	$("#device-list").children().each(function() {
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
			data: "action=update&id=" + id + "&name="  + name  + "&devices=" +  json,
			success: saveSuccessful,
			error: saveUnsuccessful
		});
	}
}

function saveSuccessful(theResponse) {
	
}

function saveUnsuccessful() {
	
}