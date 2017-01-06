var mail = '', pass = '', sending = false, interval = 5;
var current_email = '';
var last_id = -1;

$( document ).ready(function() {
	get_last_id(false);
	$('.im-send-button').click(function () {
		if (last_id != -1) {
			if ( sending == true ) {
				sending = false;
				$(this).html("Send");
				$(this).addClass('active');
				$(this).removeClass('stop');
				$('.im-status').removeClass('animate-flicker');
			} else {
				interval = $('.im-input-interval').val();
				mail = $('.im-input-mail').val();
				pass = $('.im-input-pass').val();

				get_last_id(true);
				$(this).addClass('stop');
				$(this).removeClass('active');
				$(this).html("Stop");

			}
		} else {
			$(this).addClass('disable');
			$(this).removeClass('active');
			$(this).removeClass('stop');
			$(this).html("Send");
		}
	});

	$('.im-reset-button').click(function () {
		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'reset' },
			success: function(data){
				data = JSON.parse(data);
				if ( data.status == true ) {
					$('.im-send-button').addClass('active');
					$('.im-send-button').removeClass('disable');
					$('.im-send-button').removeClass('stop');
					$('.im-send-button').html("Send");

					$('.im-status').removeClass('active');
					$('.im-status').removeClass('sending');
					$('.im-status').removeClass('animate-flicker');
					get_last_id(false);
				}

				sending = false;
			}
		});
	});

	setInterval( function () {
		if ( sending == true && last_id != -1) {
			$('.im-mail-item-'+last_id).find('.im-status').addClass('animate-flicker sending');

			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'send', gmail:mail, pass:pass },
				success: function(data){
					data = JSON.parse(data);
					
					$('.im-mail-item-'+last_id).find('.im-status').removeClass('animate-flicker sending');
					$('.im-mail-item-'+last_id).find('.im-status').addClass('active');
					$('.im-mail-item-'+data['id']).find('.im-status').addClass('animate-flicker sending');
					last_id = data['id'];


					if (last_id == -1) {
						sending = false;
						$('.im-send-button').addClass('disable');
						$('.im-send-button').removeClass('active');
						$('.im-send-button').removeClass('stop');
						$('.im-send-button').html("Send");
					}
				}
			});
		}
	}, interval * 1000);
});


function get_last_id ( flag = false ) {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_last_send_id'},
		success: function(data){
			data = JSON.parse(data);
			last_id = data.id;
			if (flag == true) {
				if (last_id == -1) {
					sending = false;
				} else {
					sending = true;
				}
			}
		}
	});
}