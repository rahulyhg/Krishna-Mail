var mail = '', pass = '', sending = false, interval = 5;
var last_id = -1;
var tab_name = "tab-name-config";
var del_button_status = false;
var del_email_ids = [];
var global_window_name = '';
var message_id = 1;
var message_files = [];

$( document ).ready(function() {
	tinymce.init({
		selector: 'textarea',
		height: 230,
		menubar: true,
		plugins: [
			'advlist autolink lists link image charmap print preview anchor',
			'searchreplace visualblocks code fullscreen',
			'insertdatetime media table contextmenu paste code'
		],
		toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
		content_css: '//www.tinymce.com/css/codepen.min.css'
	});

	$('.im-ul-tab .im-li-tab-item').click( function() {
		$('.im-ul-tab .im-li-tab-item').removeClass('active');
		$(this).addClass('active');
		tab_name = $(this).attr('tab-name');
		$('.im-tab-container .tab-item').removeClass('active');
		$('.'+tab_name).addClass('active');
	});

	get_last_id(false);
	get_table_data();

	$(document).on('change', ".im-mail-checkbox", function () {
		del_button_status = false;
		del_email_ids = [];
		$('.im-mail-checkbox').each(function(index, el) {
			if ( $(el).prop( "checked") == true ) {
				del_email_ids.push($(el).attr('im-mail-id'));
				del_button_status = true;
			}
		});

		if ( del_button_status == true ) {
			$('.im-delete-button').addClass('red');
		} else {
			$('.im-delete-button').removeClass('red');
		}
	});

	$('.im-add-button').click(function () {
		im_window( 'im-add-mail-window', true, true, true );
	});

	$('.im-delete-button').click(function () {
		if ( del_email_ids.length > 0 ) {
			var email_ids = JSON.stringify(del_email_ids);

			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'emails_delete', email_ids: email_ids},
				success: function(response){
					response = JSON.parse(response);
					if ( response.status == true ) {
						message(null, "Emails deleted successfully", '');
						get_table_data();
						$('.im-delete-button').removeClass('red');
					}
				}
			});
		} else {
			message(null, 'Not be allocated us one element', 'error');
		}
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
			success: function(response){
				response = JSON.parse(response);
				if ( response.status == true ) {
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
	});

	$('.im-config-window .im-save-button').click(function () {
		if (tab_name == 'tab-name-config') {
			var interval =  $('.im-config-window .im-input-interval').val();
			var gmail_account =  $('.im-config-window .im-input-mail').val();
			var gmail_pass =  $('.im-config-window .im-input-pass').val();
			

			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'account_save', interval: interval, gmail_account: gmail_account, gmail_pass:gmail_pass },
				success: function(response){
					response = JSON.parse(response);
					if ( response.status == true ) {
						message('im-config-window', 'Account config saved successfully', '');
					}
				}
			});
		} else if (tab_name == 'tab-name-message') {
			var subject =  $('.im-config-window .im-subject').val();
			var content = tinyMCE.activeEditor.getContent({format : 'raw'});

			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'message_save', id: message_id, subject: subject, message: content, files: JSON.stringify(message_files) },
				success: function(response){
					response = JSON.parse(response);
					if ( response.status == true ) {
						message('im-config-window', 'Email message saved successfully', '');

						$('.im-subject-list-ul').html('');
						$.each(response.subject_list, function (index, value) {
							$('.im-subject-list-ul').append("<li message-id='"+value.id+"'>"+value.subject+"</li>");
						});
					}
				}
			});
		} else if (tab_name == 'tab-name-test-emails') {
			var test_emails = [];
			$('.tab-name-test-emails .im-test-email').each(function(index, el) {
				if ( $(el).val() != '' ) {
					test_emails.push($(el).val());
				} else {
					test_emails.push('');
				}
			});
			
			test_emails = JSON.stringify(test_emails);
			
			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'test_emails_save', test_emails: test_emails},
				success: function(response){
					response = JSON.parse(response);
					if ( response.status == true ) {
						message('im-config-window', "Test Emails saved successfully", '');
					}
				}
			});
		}
	});

	$('.im-add-new-contact-save-button').click(function () {
		var contact_name =  $('.im-add-mail-window .im-con-name').val();
		var contact_phone =  $('.im-add-mail-window .im-con-phone').val();
		var contact_email =  $('.im-add-mail-window .im-con-email').val();

		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'new_contact_save', contact_name: contact_name, contact_phone: contact_phone, contact_email: contact_email},
			success: function(response){
				response = JSON.parse(response);
				if ( response.status == true ) {
					message('im-add-mail-window', 'New contact successfully added', '');
					get_table_data();
				} else {
					message('im-add-mail-window', response.error, 'error');
				}
			}
		});
	});

	$('.im-send-test-button').click(function () {
		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'send_test'},
			success: function(data){
				data = JSON.parse(data);
				if ( data.status == true ) {
					message(null, 'Test emails sended successfully', '');
				} else if ( data.status == false ) {
					message(null, data.error, 'error');
				}
			}
		});
	});

	$('.im-upload-file').on('change', function() {
		var file_data = $(this).prop('files')[0];
		var form_data = new FormData();

		form_data.append('file', file_data);
		
		$.ajax({
			url: '../ajax/upload_file.php',
			dataType: 'text',
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,
			type: 'post',
			success: function(response){
				response = JSON.parse(response);
				if (response.status == true) {
					$.ajax({
						type: 'POST',
						url: 'ajax/ajax_actions.php',
						data: { action: 'add_file_to_message', message_id: message_id, file_name: response.file.name},
						success: function(data){
							data = JSON.parse(data);
							if ( data.status == true ) {
								get_message_files();
							}
						}
					});
				}
			}
		});
	});

	$('.im-message-control-table .im-arrow-down').click(function() {
		if ( $('.im-subject-list').hasClass('active') ) {
			$('.im-subject-list').removeClass('active');
		} else {
			$('.im-subject-list').addClass('active');
		}
	});

	$(document).on('click', ".im-subject-list-ul li", function() {
		message_id = $(this).attr('message-id');
		$('.im-subject-select .im-text').html($(this).html());
		
		$.ajax({
			type: 'POST',
			url: 'ajax/ajax_actions.php',
			data: { action: 'get_message_by_id', id: message_id},
			success: function(response){
				response = JSON.parse(response);
				if (response.status == true) {
					$('.im-config-window .im-subject').val(response.message_data.subject);
					tinyMCE.activeEditor.setContent(response.message_data.message, {format : 'raw'});
					
					build_messgae_file_list(response.message_data.files);


					$(".im-subject-list").removeClass('active');
				};
			}
		});

	});

	$(document).on('click', ".im-file-delete", function () {
		var ifd = $(this);
		var file_name = $(ifd).closest('.im-file-item').find('.op-file-name').html();
		if ( file_name != "") {
			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'delete_file_from_message', message_id: message_id, file_name: file_name},
				success: function(response){
					response = JSON.parse(response);
					if ( response.status == true ) {
						$(ifd).closest('.im-file-item').remove();
					}
				}
			});
		}
	});

	$('.im-window .im-close-button').click(function () {
		var current_window = $(this).closest('.im-window');
		
		if ( $(current_window).hasClass('im-add-mail-window') ) {
			im_window('im-add-mail-window', false, false, false);
		} else if ( $(current_window).hasClass('im-config-window') ) {
			im_window('im-config-window', false, false, false);
		} else if ( $(current_window).hasClass('im-message-window') ) {
			im_window('im-message-window', false, null, null);
			if (global_window_name != null)
				$('.'+global_window_name).removeClass('back');
		}
	});

	setInterval( function () {
		if ( sending == true && last_id != -1) {
			$('.im-mail-item-'+last_id).find('.im-status').addClass('animate-flicker sending');

			$.ajax({
				type: 'POST',
				url: 'ajax/ajax_actions.php',
				data: { action: 'send', gmail:mail, pass:pass },
				success: function(response){
					response = JSON.parse(response);
					
					$('.im-mail-item-'+last_id).find('.im-status').removeClass('animate-flicker sending');
					$('.im-mail-item-'+last_id).find('.im-status').addClass('active');
					$('.im-mail-item-'+response['id']).find('.im-status').addClass('animate-flicker sending');
					last_id = response['id'];

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

// ======================  Data ====================== //
function get_last_id ( flag ) {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_last_send_id'},
		success: function(response){
			response = JSON.parse(response);
			last_id = response.id;
			if (flag == true) {
				if ( last_id == -1 ) {
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
		success: function(response){
			response = JSON.parse(response);
			$('.im-email-table').html(response.html);
		}
	});
}


function get_config_data() {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_config_data'},
		success: function(response){
			response = JSON.parse(response);
			if ( response.status == true ) {
				$.each(response.options_data, function (index, value) {
					if (value.param_name == 'send_interval') {
						$('.im-config-window .im-input-interval').val( value.val );
					} else if (value.param_name == 'gmail_account') {
						$('.im-config-window .im-input-mail').val( value.val );
					} else if (value.param_name == 'gmail_pass') {
						$('.im-config-window .im-input-pass').val( value.val );
					} else if (value.param_name == 'test_emails') {
						$('.tab-name-test-emails .im-test-email').each(function(ind, el) {
							$(el).val(value.val[ind]);
						});
					} else if (value.param_name == 'active_message') {
						message_id = value.val;
					}
				});

				$.each(response.messages_subject, function (index, value) {
					$('.im-subject-list-ul').append("<li message-id='"+index+"'>"+value+"</li>");
				});

				$('.im-config-window .im-subject').val(response.message_data.subject);
				tinyMCE.activeEditor.setContent(response.message_data.message, {format : 'raw'});
				
				message_files = response.message_data.files;
				build_messgae_file_list(message_files);
				
				im_window( 'im-config-window', true, true, true );
			}
		}
	});
};

function get_message_files() {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'get_message_files', message_id: message_id},
		success: function(response){
			response = JSON.parse(response);
			if ( response.status == true ) {
				message_files = response.files;
				build_messgae_file_list(message_files);
			}
		}
	});
};

function update_message_subject_list() {
	$.ajax({
		type: 'POST',
		url: 'ajax/ajax_actions.php',
		data: { action: 'update_message_subject_list', message_id: message_id},
		success: function(response){
			response = JSON.parse(response);
			if ( response.status == true ) {

			}
		}
	});
};

function build_messgae_file_list (list) {
	if ( list ) {
		$(".im-file-container .im-file-ul").html("");
		$.each(list, function(index, el) {
			$(".im-file-container .im-file-ul").append("<li class='im-file-item'><div class='im-file-delete'><span>x</span></div>"+(1+index)+" - <span class='op-file-name'>"+el+"</span></li>")
		});
	}
}

// ======================  Windows ====================== //
function curtain( status ) {
	if ( status == true ) {
		$('.curtain').addClass('active');
		setTimeout(function(){
			$('.curtain').addClass('opacity');
		}, 100);
	} else if ( status == false ) {
		$('.curtain').removeClass('opacity');
		setTimeout(function(){
			$('.curtain').removeClass('active');
		}, 400);
	}
}

function im_window( window_name, window_status, curtain_status, close_all_window_status ) {
	var time = 0;
	window_name = '.'+window_name;
	center( window_name );
	
	if ( window_status == true ) {
		time = 500;
	} else if (window_status == false ) {
		time = 0;
	}

	if ( close_all_window_status == true ) {
		$('.im-window').removeClass('opacity');
		setTimeout(function(){
			$('.im-window').removeClass('active');
		}, 500);
	}

	if ( curtain_status == true )
		curtain( true );
	else if ( curtain_status == false )
		curtain( false );

	setTimeout(function(){
		if ( window_status == true ) {
			$(window_name).addClass('active');
			setTimeout(function(){
				$(window_name).addClass('opacity');
			}, 100);
		} else if ( window_status == false ) {
			$(window_name).removeClass('opacity');
			setTimeout(function(){
				$(window_name).removeClass('active');
			}, 400);
		}
	}, time);
}

function close_all_window() {
	$('.im-window').removeClass('active');
}

function message(window_name, message, additional_class, curtain_status) {
	if ( curtain_status == true ) {
		curtain( true );
	}

	$('.im-message-window .im-window-top-border').removeClass('error');
	$('.im-message-window .im-window-top-border').removeClass('warning');

	if (window_name != null)
		$('.'+window_name).addClass('back');

	global_window_name = window_name;

	if (additional_class != '') {
		$('.im-message-window .im-window-top-border').addClass(additional_class);
	}

	center('.im-message-window');
	$('.im-message-window').addClass('active');
	setTimeout(function(){
		$('.im-message-window').addClass('opacity');
	}, 100);

	$('.im-message-text').html(message);
}

function center (className) {
	$(className).css("top", ( $(window).height() - $(className).height() ) / 2  + "px");
	$(className).css("left", ( $(window).width() - $(className).width() ) / 2 + "px");
}


function clear_input( className ) {
	$('.' + className).find('.im-input').val('');
}
