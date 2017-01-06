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
	<script src="../js/less.min.js" type="text/javascript"></script>
	<script src="../js/kernel.js" type="text/javascript"></script>
	

</head>

<body>
	<div class="im-table-container">
		<div class="im-header">
			<div class="im-name">Smart emails sender</div>
			<div class="clear"></div>
		</div>

		<div class="im-control-container">
			<div class="im-control">
				<input class="im-input im-input-interval" type="text" value="<?=$params[0]['val']; ?>">
				<input class="im-input im-input-mail" type="text" value="<?=$params[1]['val']; ?>">
				<input class="im-input im-input-pass" type="password" value="<?=$params[2]['val']; ?>">
				<button class="im-button im-send-button active">Send</button>
				<button class="im-button im-reset-button">Reset</button>
			</div>

		</div>
		<div class="im-email-tc">
			<table class="im-email-table">
				<?php foreach ($emails as $email ) { ?>
					<tr class='im-mail-item-<?=$email['id'];?>'>
						<td><?=$email['id'];?></td>
						<td><?=$email['name'];?></td>
						<td><?=$email['phone'];?></td>
						<td class="im-email"><?=$email['mail'];?></td>
						<td align="center">
							<div class="im-status <?= ( $email['send'] == 1 ) ? 'active': ''; ?>"></div>
						</td>
					</tr>
				<? } ?>
			</table>
		</div>

		<div class='im-footer'>Mail Sender Copyright (c) 2017</div>
	</div>
	
	<div class='im-text-window'>
		<textarea name="" id="" cols="30" rows="10"></textarea>
		<div class="im-control">
			<button class="im-button im-send-button active">Save</button>
		</div>
	</div>

</body>

</html>
