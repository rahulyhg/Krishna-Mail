var mail = '', pass = '', sending = false, interval = 5;
var current_email = '';
var last_id = -1;
var process_window_name = '';
$( document ).ready(function() {
	tinymce.init({
		selector: 'textarea',
		height: 250,
		menubar: true,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table contextmenu paste code'
		],
		toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		content_css: '//www.tinymce.com/css/codepen.min.css'
	});

	get_last_id(false);
	get_table_data();

	$('.im-add-button').click(function () {
		close_all_window();
		center('.im-add-mail-window');
		$('.im-add-mail-window').addClass('active');
	});

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

	$('.im-config-button').click(function () {
		get_config_data();
		close_all_window();
		center('.im-config-window');
		$('.im-config-window').addClass('active');
	});

	$('.im-message-save-button').click(function () {
		var subject =  $('.im-config-window .im-subject').val();
		var content = tinyMCE.activeEditor.getContent({format : 'raw'});

		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'message_save', subject: subject, message: content },
			success: function(data){
				data = JSON.parse(data);
				if ( data.status == true ) {
					$('.im-config-window').removeClass('active');
				}
			}
		});
	});

	$('.im-add-new-contact-save-button').click(function () {
		var contact_name =  $('.im-add-mail-window .im-con-name').val();
		var contact_phone =  $('.im-add-mail-window .im-con-phone').val();
		var contact_email =  $('.im-add-mail-window .im-con-email').val();

		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'new_contact_save', contact_name: contact_name, contact_phone: contact_phone, contact_email: contact_email},
			success: function(data){
				data = JSON.parse(data);
				if ( data.status == true ) {
					message('New contact successfully added');
					get_table_data();
				} else {
					message(data.error);
				}
			}
		});
	});

	$('.im-message-window .im-button').click(function () {
		$('.im-message-window').removeClass('active');
	});

	$('.im-window .im-close-button').click(function () {
		$(this).closest('.im-window').removeClass('active');
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


function get_last_id ( flag ) {
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

function get_table_data () {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_table_data'},
		success: function(data){
			data = JSON.parse(data);
			$('.im-email-table').html(data.html);
		}
	});
}

function center (className) {
	$(className).css("top", ( $(window).height() - $(className).height() ) / 2  + "px");
	$(className).css("left", ( $(window).width() - $(className).width() ) / 2 + "px");
}

function close_all_window() {
	$('.im-window').removeClass('active');
}

function message(msg) {
	$('.im-add-mail-window').addClass('back');

	center('.im-message-window');
	$('.im-message-window').addClass('active');
	$('.im-message-text').html(msg);
}


function get_config_data() {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_config_data'},
		success: function(data){
			data = JSON.parse(data);
			console.log(data);

			$.each(data.params_data, function (index, value) {
				if (value.param_name == 'interval') {
					$('.im-config-window .im-input-interval').val( value.val );
				} else if (value.param_name == 'gmail') {
					$('.im-config-window .im-input-mail').val( value.val );
				} else if (value.param_name == 'pass') {
					$('.im-config-window .im-input-pass').val( value.val );
				}

			});


		}
	});
}