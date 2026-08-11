<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	$accion = !empty($_POST['accion']) ? $_POST['accion'] : 'listar';

	// -----------------------------------------------------------
	// listar: catálogo completo + cuál tiene seleccionado el piloto (si hay sesión)
	// -----------------------------------------------------------
	if( $accion == 'listar' ) {

		$db->query("SELECT id, nombre, fabricante, categoria, imagen, descripcion
					FROM drones
					WHERE status = 1
					ORDER BY orden ASC, nombre ASC");
		$db->execute();
		$drones = $db->resultSet();
		$db->CloseConnection();

		$seleccionado = null;
		if( $UserID != null ) { $seleccionado = $User->user($UserID, 'drone_id'); }

		echo json_encode(array('ok' => true, 'drones' => $drones, 'seleccionado' => $seleccionado ? (int)$seleccionado : null));
		exit;

	}

	// -----------------------------------------------------------
	// seleccionar: guarda el dron que utiliza el piloto (requiere sesión)
	// -----------------------------------------------------------
	if( $accion == 'seleccionar' ) {

		if( $UserID == null ) { echo json_encode(array('ok' => false, 'error' => 'login_required')); exit; }

		$droneID = !empty($_POST['drone_id']) ? (int)$_POST['drone_id'] : null;
		if( $droneID == null ) { echo json_encode(array('ok' => false, 'error' => 'datos_incompletos')); exit; }

		$existe = $CR->count_rows('drones', 'WHERE id = :var AND status = 1', 'id', $droneID);
		if( $existe < 1 ) { echo json_encode(array('ok' => false, 'error' => 'dron_invalido')); exit; }

		$CR->updateData('login', 'drone_id', $droneID, 'userid', $UserID);
		$CR->logs('Actualización de equipo', 'El piloto seleccionó el dron que utiliza en su perfil.', $UserID, null, $UserID);

		echo json_encode(array('ok' => true, 'drone_id' => $droneID));
		exit;

	}

	echo json_encode(array('ok' => false, 'error' => 'accion_invalida'));

?>