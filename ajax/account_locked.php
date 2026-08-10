<?php 

	require '../init.conf';
	$CR->ajaxToken();

	// exist session (?)
	if( $UserID <= 0 ) {

		// check sessions expired
		$User->session(0);
		echo $CR->updateJS(' alerta("¡Ocurrió un error, intente nuevamente!", "success"); ').$CR->refresh(1, URL.'/login');

	} else {

		// add log
		$CR->logs('Bloqueo de Pantalla', 'El usuario ha bloqueado la pantalla correctamente.', $UserID, $serverID);
		// delete statusd
		$CR->updateData("login", "statusd", "2", "userid", $UserID);
		// refresh
		echo $CR->updateJS(' alerta("¡Pantalla Bloqueada correctamente!", "success"); ').$CR->refresh(0, URL.'/locked');

	}

?>