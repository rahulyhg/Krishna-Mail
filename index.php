<?php
// Create connection
$connection = new mysqli("localhost", "root", "MBPLl5tHR0");

// Check connection
if ($connection->connect_error) {
	die("Connection failed: " . $connection->connect_error);
}

$sql = "SELECT * FROM mail.mails";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)) {
		$emails[] = $row;
	}
}


$sql = "SELECT * FROM mail.params";
$result = mysqli_query($connection, $sql);

if (mysqli_num_rows($result) > 0) {
	// output data of each row
	while($row = mysqli_fetch_assoc($result)) {
		$params[] = $row;
	}
}

//mysqli_close($connection);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Mail sender</title>
	<link rel="stylesheet/less" type="text/css" href="../css/styles.less" />

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
				<button class="im-button im-add-button add im-left">Add Mail</button>
				<button class="im-button im-config-button add im-right">Config</button>
				<button class="im-button im-reset-button im-right">Reset</button>
				<button class="im-button im-send-button active im-right">Send</button>
			</div>
		</div>
		<div class="im-email-tc">
			<table class="im-email-table"></table>
		</div>

		<div class='im-footer'>Mail Sender Copyright (c) 2017</div>
	</div>
	
	<div class='im-window im-config-window'>
		<div class="im-window-title">Configuration</div>
		<div class="tab_item" style="">
			<input class="im-input im-input-interval" type="text" value="<?=$params[0]['val']; ?>">
			<input class="im-input im-input-mail" type="text" value="<?=$params[1]['val']; ?>">
			<input class="im-input im-input-pass" type="password" value="<?=$params[2]['val']; ?>">
		</div>

		<div class="im-hr"></div>

		<div class="tab_item">
			<input type="text" class="im-input im-subject" placeholder="Subject">
			<textarea class="im-message-textarea"></textarea>
		</div>

		<div class="im-control">
			<button class="im-button im-close-button stop im-right">Close</button>
			<button class="im-button im-message-save-button active im-left">Save</button>
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
			<button class="im-button im-close-button stop im-right">Close</button>
			<button class="im-button im-add-new-contact-save-button active im-left">Save</button>
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
