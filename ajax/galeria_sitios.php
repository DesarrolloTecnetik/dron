<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	$accion = !empty($_POST['accion']) ? $_POST['accion'] : 'listar';

	// -----------------------------------------------------------
	// listar: devuelve todos los sitios con el conteo de publicaciones
	// -----------------------------------------------------------
	if( $accion == 'listar' ) {

		$db->query("SELECT s.id, s.nombre, s.descripcion, s.lat, s.lng,
							(SELECT COUNT(*) FROM galeria_publicaciones p WHERE p.sitio_id = s.id AND p.status = 1) AS total
					FROM galeria_sitios s
					WHERE s.status = 1
					ORDER BY s.nombre ASC");
		$db->execute();
		$sitios = $db->resultSet();
		$db->CloseConnection();

		echo json_encode(array('ok' => true, 'sitios' => $sitios));
		exit;

	}

	// -----------------------------------------------------------
	// crear: da de alta un sitio nuevo (requiere sesión)
	// -----------------------------------------------------------
	if( $accion == 'crear' ) {

		if( $UserID == null ) { echo json_encode(array('ok' => false, 'error' => 'login_required')); exit; }

		$nombre = !empty($_POST['nombre']) ? trim($_POST['nombre']) : null;
		$lat = !empty($_POST['lat']) ? $_POST['lat'] : null;
		$lng = !empty($_POST['lng']) ? $_POST['lng'] : null;
		$descripcion = !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

		if( $nombre == null || $lat == null || $lng == null ) {
			echo json_encode(array('ok' => false, 'error' => 'datos_incompletos')); exit;
		}

		$db->query("INSERT INTO galeria_sitios (nombre, descripcion, lat, lng, userid) VALUES (:r1, :r2, :r3, :r4, :r5)");
		$db->bind(':r1', $nombre);
		$db->bind(':r2', $descripcion);
		$db->bind(':r3', $lat);
		$db->bind(':r4', $lng);
		$db->bind(':r5', $UserID);
		$db->execute();
		$newID = $db->lastID();
		$db->CloseConnection();

		echo json_encode(array('ok' => true, 'id' => $newID));
		exit;

	}

	echo json_encode(array('ok' => false, 'error' => 'accion_invalida'));

?>
