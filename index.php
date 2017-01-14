<!DOCTYPE html>
<html>
<head>
	<title>Mail sender</title>
	
	<!-- LESS -->
	<link rel="stylesheet/less" type="text/css" href="../css/styles.less" />

	<!-- JS -->
	<script src="../js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script src="../js/jquery-ui.min.js"></script>
	<script src="../js/less.min.js" type="text/javascript"></script>
	<script src="../js/kernel.js" type="text/javascript"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
</head>

<body>
	<div class="curtain"></div>
	<div class="im-table-container">
		<div class="im-header">
			<div class="im-name">Smart emails sender</div>
			<div class="clear"></div>
		</div>

		<div class="im-control-container">
			<div class="im-control">
				<button class="im-button im-add-button green im-left">Add Mail</button>
				<button class="im-button im-config-button blue im-left">Config</button>
				<button class="im-button im-reset-button red im-right">Reset</button>
				<button class="im-button im-send-button green im-right">Send</button>
			</div>
		</div>
		<div class="im-email-tc">
			<table class="im-email-table"></table>
		</div>

		<div class='im-footer'>Mail Sender Copyright (c) 2017</div>
	</div>
	
	<div class='im-window im-config-window'>
		<div class="im-window-title">Configuration</div>

		<ul class="im-ul-tab">
			<li tab-name='' class="im-li-tab-item active">Account</li>
			<li class="im-li-tab-item">Message</li>
			<li class="im-li-tab-item">Test Emails</li>
		</ul>
		<div class="clear"></div>
		<div class="im-tab-container">
			<div class="tab-item">
				<input class="im-input im-input-interval" type="text" value="<?=$params[0]['val']; ?>">
				<input class="im-input im-input-mail" type="text" value="<?=$params[1]['val']; ?>">
				<input class="im-input im-input-pass" type="password" value="<?=$params[2]['val']; ?>">
			</div>

			<div class="tab-item active">
				<input type="text" class="im-input im-subject" placeholder="Subject">
				<textarea class="im-message-textarea"></textarea>
			</div>
		</div>


<!--		<div class="im-hr"></div>-->


		<div class="im-control">
			<button class="im-button im-close-button red im-right">Close</button>
			<button class="im-button im-message-save-button green im-left">Save</button>
		</div>
	</div>

	<div class='im-window im-add-mail-window'>
		<div class="im-window-title">Add new contact</div>
		<table>
			<tr>
				<td>
					<input class="im-input im-con-name" type="text" placeholder="Name">
				</td>
			</tr>
			<tr>
				<td>
					<input class="im-input im-con-phone" type="text" placeholder="Phone">
				</td>
			</tr>
			<tr>
				<td>
					<input class="im-input im-con-email" type="text" placeholder="Email">
				</td>
			</tr>
		</table>
		<div class="im-control">
			<button class="im-button im-close-button yellow im-right">Close</button>
			<button class="im-button im-add-new-contact-save-button yellow im-left">Save</button>
		</div>
	</div>

	<div class='im-window im-message-window'>
		<div class="im-window-title">Message</div>
		<div class="im-message-text">New contact successfully added</div>
		<div class="im-control">
			<button class="im-button im-button active im-right">Close</button>
		</div>

	</div>
</body>

</html>
