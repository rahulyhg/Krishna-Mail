<?php
	if ( 0 < $_FILES['file']['error'] ) {
		echo 'Error: ' . $_FILES['file']['error'] . '<br>';
	} else {
		if ( move_uploaded_file($_FILES['file']['tmp_name'], '../uploads/' . $_FILES['file']['name']) ) {
			echo json_encode(['status' => true, 'file' => $_FILES['file']]);
		}
	}
?>