<?php

$action = $_POST['action'];

$connection = new mysqli("localhost", "root", "MBPLl5tHR0");


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

//mysqli_close($connection);

?>