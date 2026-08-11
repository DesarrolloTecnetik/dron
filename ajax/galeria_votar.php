<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	if( $UserID == null ) { echo json_encode(array('ok' => false, 'error' => 'login_required')); exit; }

	$pubID = !empty($_POST['publicacion_id']) ? (int)$_POST['publicacion_id'] : null;
	if( $pubID == null ) { echo json_encode(array('ok' => false, 'error' => 'datos_incompletos')); exit; }

	// revisa si ya existe el voto de este usuario
	$db->query("SELECT id FROM galeria_votos WHERE publicacion_id = :pid AND userid = :uid");
	$db->bind(':pid', $pubID);
	$db->bind(':uid', $UserID);
	$db->execute();
	$existe = $db->rowCount() >= 1;

	if( $existe ) {

		// quitar voto
		$db->query("DELETE FROM galeria_votos WHERE publicacion_id = :pid AND userid = :uid");
		$db->bind(':pid', $pubID);
		$db->bind(':uid', $UserID);
		$db->execute();

		$db->query("UPDATE galeria_publicaciones SET votos = votos - 1 WHERE id = :pid AND votos > 0");
		$db->bind(':pid', $pubID);
		$db->execute();

		$estado = false;

	} else {

		// agregar voto
		$db->query("INSERT INTO galeria_votos (publicacion_id, userid) VALUES (:pid, :uid)");
		$db->bind(':pid', $pubID);
		$db->bind(':uid', $UserID);
		$db->execute();

		$db->query("UPDATE galeria_publicaciones SET votos = votos + 1 WHERE id = :pid");
		$db->bind(':pid', $pubID);
		$db->execute();

		$estado = true;

	}

	$db->query("SELECT votos FROM galeria_publicaciones WHERE id = :pid");
	$db->bind(':pid', $pubID);
	$db->execute();
	$row = $db->single();
	$db->CloseConnection();

	echo json_encode(array('ok' => true, 'voto_activo' => $estado, 'votos' => (int)$row['votos']));

?>
