<?php

$action = $_POST['action'];

$connection = new mysqli("localhost", "root", "MBPLl5tHR0");

if (!$connection->set_charset("utf8mb4")) {
	exit();
}

// Check connection
if ($connection->connect_error) {
	die("Connection failed: " . $connection->connect_error);
}


if ( $action == 'send') {
	$gmail = $_POST['gmail'];
	$pass = $_POST['pass'];
	
	$sql = "SELECT * FROM mail.mails WHERE send=0 LIMIT 1";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$data = $row;
		}
		
		require '../lib/PHPMailer/PHPMailerAutoload.php';

		$mail = new PHPMailer;

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'inflatable.syphax@gmail.com';                   // SMTP username
		$mail->Password = 'MBPLl5tHR0';               // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
		$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
		$mail->setFrom('inflatable.syphax@gmail.com', 'Igor Melnik');     //Set who the message is to be sent from
		$mail->addAddress($data['mail'], $data['name']);  // Add a recipient
		$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
		$mail->isHTML(true);                                  // Set email format to HTML

		$mail->Subject = 'Here is the subject';
		$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		if(!$mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $mail->ErrorInfo;
			exit;
		}

		$sql = "UPDATE mail.mails SET send=1 WHERE id=".$data['id'];
		$result = mysqli_query($connection, $sql);

		echo json_encode(['id' => $data['id'], 'status' => $result]);

	} else {
		echo json_encode(['id' => -1, 'status' => true]);
	}
}

if ( $action == 'reset') {
	$sql = "UPDATE mail.mails SET send=0 WHERE send=1";
	$result = mysqli_query($connection, $sql);

	echo json_encode(['status' => $result]);
}

if ( $action == 'get_last_send_id') {
	$sql = "SELECT * FROM mail.mails WHERE send=0 LIMIT 1";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$data = $row;
		}

		echo json_encode(['id' => $data['id'], 'status' => true]);
	} else {
		echo json_encode(['id' => -1, 'status' => true]);
	}
}

if ( $action == 'message_save') {
	$subject = $_POST['subject'];
	$message = $_POST['message'];

	$sql = "UPDATE mail.messages SET subject='".$subject."', message='".$message."' WHERE id=1";
	$result = mysqli_query($connection, $sql);

	echo json_encode(['status' => $result]);
}

if ( $action == 'new_contact_save') {
	$contact_name = $_POST['contact_name'];
	$contact_phone = $_POST['contact_phone'];
	$contact_email = $_POST['contact_email'];

	$sql = "SELECT * FROM mail.mails WHERE mail='".$contact_email."'";
	$result = mysqli_query($connection, $sql);
	if ( $result->num_rows > 0 ) {
		echo json_encode(['status' => 0, 'error' => 'Email already exist']);
	} else {
		$sql = "INSERT INTO `mail`.`mails` (`name`, `phone`, `mail`) VALUES ('$contact_name', '$contact_phone', '$contact_email')";
		$result = mysqli_query($connection, $sql);
		echo json_encode(['status' => $result]);
	}
}

if ( $action == 'get_table_data') {
	$sql = "SELECT * FROM mail.mails";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		$html_response = '<tr><td align=\'center\'><input type=\'checkbox\'></td><td align=\'center\'>ID</td><td>Name</td><td>Phone</td><td>Email</td><td align=\'center\'>St</td></tr>';
		while($row = mysqli_fetch_assoc($result)) {
			$status = ( $row['send'] == 1 ) ? 'active': '';
			$html_response .= "<tr class='im-mail-item-".$row['id']."'>";
			$html_response .= "<td width='20'><input type='checkbox'></td>";
			$html_response .= "<td width='20' align='center'>".$row['id']."</td>";
			$html_response .= "<td>".$row['name']."</td>";
			$html_response .= "<td>".$row['phone']."</td>";
			$html_response .= "<td class='im-email'>".$row['mail']."</td>";
			$html_response .= "<td align='center' width='20'><div class='im-status ".$status."'></div></td>";
			$html_response .= "</tr>";
		}
	}

	echo json_encode(['status' => true, 'html' => $html_response]);
}


if ( $action == 'get_config_data' ) {
	$sql = "SELECT * FROM mail.params";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$params_data[] = $row;
		}
	}

	$sql = "SELECT * FROM mail.messages";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$messages_data[] = $row;
		}
	}

	echo json_encode(['params_data' => $params_data, 'messages_data' => $messages_data, 'status' => true]);
}

//mysqli_close($connection);

?>