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
	$id 		= $_POST['id'];
	$subject 	= $_POST['subject'];
	$message 	= $_POST['message'];
	$files 		= serialize(json_decode(stripslashes( $_POST['files'] )));

	$sql = "SELECT * FROM mail.messages WHERE id=".$id;
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$message_data = $row;
		}
	}

	if ($message_data['subject'] != $subject ) {
		$sql = "INSERT INTO mail.messages (`subject`, `message`, `files`) VALUES ('".$subject."', '".$message."', '".$files."')";
		
		$result = mysqli_query($connection, $sql);
	} else {
		$sql = "UPDATE mail.messages SET subject='".$subject."', message='".$message."' WHERE id=1";
		$result = mysqli_query($connection, $sql);
	}

	if ( $result == true) {
		$sql = "SELECT id, subject FROM mail.messages";
		$subjects_list = mysqli_query($connection, $sql);

		if (mysqli_num_rows($subjects_list) > 0) {
			while($row = mysqli_fetch_assoc($subjects_list)) {
				$subjects_data[] = $row;
			}
		}

		echo json_encode(['status' => $result, 'subject_list' => $subjects_data]);
	}
		
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
	$html_response = "";
	if (mysqli_num_rows($result) > 0) {
		//$html_response = '<tr><td align=\'center\'><input type=\'checkbox\'></td><td align=\'center\'>ID</td><td>Name</td><td>Phone</td><td>Email</td><td align=\'center\'>St</td></tr>';
		while($row = mysqli_fetch_assoc($result)) {
			$status = ( $row['send'] == 1 ) ? 'active': '';
			$html_response .= "<tr class='im-mail-item-".$row['id']."'>";
			$html_response .= "<td width='20'><input type='checkbox' class='im-mail-checkbox' im-mail-id='".$row['id']."'></td>";
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
	$sql = "SELECT * FROM mail.options";
	$result = mysqli_query($connection, $sql);

	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			$options_data[] = $row;
		}
	}

	if ($options_data[2]['val'] != '') {
		$options_data[2]['val'] = base64_decode($options_data[2]['val']);
	}

	if ($options_data[3]['val'] != '') {
		$options_data[3]['val'] = unserialize($options_data[3]['val']);
	}

	$sql = "SELECT * FROM mail.messages WHERE id=1";
	$message = mysqli_query($connection, $sql);

	if (mysqli_num_rows($message) > 0) {
		while($row = mysqli_fetch_assoc($message)) {
			if ($row['files'] != "")
				$row['files'] = unserialize($row['files']);
			
			$message_data = $row;
		}
	}

	$sql = "SELECT * FROM mail.messages";
	$messages = mysqli_query($connection, $sql);

	if (mysqli_num_rows($messages) > 0) {
		while($row = mysqli_fetch_assoc($messages)) {
			if ($row['files'] != "")
				$row['files'] = unserialize($row['files']);
			
			$messages_data[] = $row;
		}
	}

	if ( !empty($messages_data) ) {
		foreach ($messages_data as $data) {
			$messages_subject[$data['id']] = $data['subject'];
		}
	}
	
	echo json_encode(['options_data' => $options_data, 'message_data' => $message_data, 'messages_subject' => $messages_subject, 'status' => true]);
}


if ( $action == 'account_save' ) {
	$interval = $_POST['interval'];
	$gmail_account = $_POST['gmail_account'];
	$gmail_pass = base64_encode($_POST['gmail_pass']);

	$sql = "UPDATE mail.options SET val='".$interval."' WHERE param_name='send_interval'";
	$result = mysqli_query($connection, $sql);

	$sql = "UPDATE mail.options SET val='".$gmail_account."' WHERE param_name='gmail_account'";
	$result = mysqli_query($connection, $sql);

	$sql = "UPDATE mail.options SET val='".$gmail_pass."' WHERE param_name='gmail_pass'";
	$result = mysqli_query($connection, $sql);

	echo json_encode(['status' => $result]);

};

if ( $action == 'test_emails_save' ) {
	$test_emails = json_decode(stripslashes( $_POST['test_emails'] ));
	
	$test_emails = serialize($test_emails);

	$sql = "UPDATE mail.options SET val='".$test_emails."' WHERE param_name='test_emails'";

	$result = mysqli_query($connection, $sql);

	echo json_encode(['status' => $result]);

}

if ( $action == 'emails_delete' ) {
	$email_ids = json_decode(stripslashes( $_POST['email_ids'] ));

	$email_ids = implode(', ', $email_ids);

	$sql = "DELETE FROM mail.mails WHERE id IN (".$email_ids.")";
	$result = mysqli_query($connection, $sql);

	echo json_encode(['status' => $result]);
}

if ( $action == 'send_test' ) {
	$sql = "SELECT * FROM mail.messages LIMIT 1";
	$messages = mysqli_query($connection, $sql);

	if (mysqli_num_rows($messages) > 0) {
		while($row = mysqli_fetch_assoc($messages)) {
			if ( $row['files'] != "" ) {
				$row['files'] = unserialize($row['files']);
			}
			
			$messages_data = $row;
		}
	}

	$sql = "SELECT * FROM mail.options";
	$options = mysqli_query($connection, $sql);

	if (mysqli_num_rows($options) > 0) {
		while($row = mysqli_fetch_assoc($options)) {
			$options_data[] = $row;
		}
	}
	
	$test_emails = unserialize( $options_data[3]['val'] );

	require '../lib/PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.gmail.com';                       // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $options_data[1]['val'];                   // SMTP username
	$mail->Password = base64_decode($options_data[2]['val']);               // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted
	$mail->Port = 587;                                    //Set the SMTP port number - 587 for authenticated TLS
	$mail->setFrom($options_data[1]['val'], 'Igor Melnik');     //Set who the message is to be sent from
	
	//$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
	$mail->isHTML(true);                                  // Set email format to HTML

	$mail->Subject = $messages_data['subject'];
	$mail->Body    = $messages_data['message'];
	$mail->altBody = $messages_data['message'];

	if (count($messages_data['files']) > 0 ) {
		foreach ($messages_data['files'] as $key => $file) {
			$mail->addAttachment('../uploads/'.$file);
		}
	}

	foreach ( $test_emails as $email ) {
		if ($email != "")
			$mail->addAddress($email);
	}

	if(!$mail->send()) {
		echo json_encode(['status' => false, 'error' => 'Message could not be sent -> '.$mail->ErrorInfo]);
		exit;
	}

	echo json_encode(['status' => true]);
}

if ( $action == 'add_file_to_message' ) {
	$message_id = $_POST['message_id'];
	$file_name = $_POST['file_name'];

	$sql = "SELECT * FROM mail.messages WHERE id=".$message_id." LIMIT 1";
	$messages = mysqli_query($connection, $sql);


	if (mysqli_num_rows($messages) > 0) {
		while($row = mysqli_fetch_assoc($messages)) {
			$messages_data = $row;
		}

		if ( $messages_data['files'] != "" ) {
			$messages_data['files'] = unserialize($messages_data['files']);

			if ( !in_array($file_name, $messages_data['files']) ) {
				array_push($messages_data['files'], $file_name);
			}
		} else {
			$messages_data['files'] = array();
			array_push($messages_data['files'], $file_name);
		}

		if ( count($messages_data['files']) > 0 ) {
			$messages_data['files'] = serialize($messages_data['files']);
			$sql = "UPDATE mail.messages SET files='".$messages_data['files']."' WHERE id = ".$message_id;
			$result = mysqli_query($connection, $sql);
			echo json_encode(['status' => $result]);
		} else {
			echo json_encode(['status' => false]);
		}
	}
}

if ( $action == 'get_message_files' ) {
	$message_id = $_POST['message_id'];

	$sql = "SELECT * FROM mail.messages WHERE id=".$message_id." LIMIT 1";
	$messages = mysqli_query($connection, $sql);

	if (mysqli_num_rows($messages) > 0) {
		while($row = mysqli_fetch_assoc($messages)) {
			$messages_data = $row;
		}

		if ( $messages_data['files'] != "" ) {
			$messages_data['files'] = unserialize($messages_data['files']);
		}
	}

	if ( !empty($messages_data['files']) ) {
		echo json_encode(['status' => true, 'files' => $messages_data['files']] );
	} else {
		echo json_encode(['status' => false]);
	}
}

if ( $action == 'get_message_by_id' ) {
	$message_id = $_POST['id'];

	$sql = "SELECT * FROM mail.messages WHERE id=".$message_id;
	$message = mysqli_query($connection, $sql);

	if (mysqli_num_rows($message) > 0) {
		while($row = mysqli_fetch_assoc($message)) {
			if ($row['files'] != "")
				$row['files'] = unserialize($row['files']);
			
			$message_data = $row;
		}
	}
	echo json_encode(['status' => true, 'message_data' => $message_data]);
}

if ( $action == 'delete_file_from_message' ) {
	$message_id = $_POST['message_id'];
	$file_name = $_POST['file_name'];

	$sql = "SELECT * FROM mail.messages WHERE id=".$message_id." LIMIT 1";
	$messages = mysqli_query($connection, $sql);

	if (mysqli_num_rows($messages) > 0) {
		while($row = mysqli_fetch_assoc($messages)) {
			$messages_data = $row;
		}

		if ( $messages_data['files'] != "" ) {
			$messages_data['files'] = unserialize($messages_data['files']);

			$files = array();
			foreach ($messages_data['files'] as $value) {
				if ( $value != $file_name ) {
					$files[] = $value;
				}
			}

			$messages_data['files'] = $files;
		}

		$messages_data['files'] = serialize($messages_data['files']);
		$sql = "UPDATE mail.messages SET files='".$messages_data['files']."' WHERE id = ".$message_id;
		$result = mysqli_query($connection, $sql);
		echo json_encode(['status' => $result]);
	}
}


mysqli_close($connection);

?>