<?php 
	
	require '../init.conf';
	
	// get vars
	$action = !empty($_POST['action']) ? $_POST['action'] : null;
	$id = !empty($_POST['id']) ? $_POST['id'] : null;

	// check data 
	if($action == 'action') {

		$table = 'table';
		$by = "*";
		$byCount = "*";
		$error = 'error message';
		$success = 'success message';
		$redirection = $CR->refresh(3, URL."/redirect");

	} else { echo $CR->updateJS(' alerta("error message action get", "danger"); button(true); '); exit; }

	// validation vars
	if( $action == null || $id == null || !is_numeric($id) ) { echo $CR->updateJS(' alerta("error message id null", "danger"); button(true); '); } else {

		// validate Data
		$eData = $CR->count_rows($table, "WHERE {$byCount} = :var", $by, $id);
		if( $eData >= 1 ) {

			// proccess to delete
			$db->query("DELETE FROM {$table} WHERE {$by} = :v1");
			$db->bind(':v1', $id);
			$db->execute();
			$db->CloseConnection();

			// add Log system
			$CR->logs("log message descrip", $success, $UserID, $table, $id);
			// get response success
			echo $CR->updateJS(' alerta("'.$success.'", "success"); button(true); ').$redirection;

		} else { echo $CR->updateJS(' alerta("'.$error.'", "danger"); button(true); '); }

	}

?>

