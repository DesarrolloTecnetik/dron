<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	$accion = !empty($_POST['accion']) ? $_POST['accion'] : 'ver';
	$pubID  = !empty($_POST['publicacion_id']) ? (int)$_POST['publicacion_id'] : null;

	if( $pubID == null ) { echo json_encode(array('ok' => false, 'error' => 'datos_incompletos')); exit; }

	// -----------------------------------------------------------
	// ver: detalle de la publicación + autor + comentarios
	// -----------------------------------------------------------
	if( $accion == 'ver' ) {

		$db->query("SELECT p.id, p.tipo, p.archivo, p.titulo, p.descripcion, p.lat, p.lng,
						p.votos, p.vistas, p.idatetime, p.userid,
						l.user AS autor, l.name AS autor_nombre, l.avatar AS autor_avatar,
						s.id AS sitio_id, s.nombre AS sitio_nombre
					FROM galeria_publicaciones p
					LEFT JOIN login l ON l.userid = p.userid
					LEFT JOIN galeria_sitios s ON s.id = p.sitio_id
					WHERE p.id = :pid AND p.status = 1");
		$db->bind(':pid', $pubID);
		$db->execute();
		$pub = $db->single();

		if( !$pub ) { echo json_encode(array('ok' => false, 'error' => 'no_encontrado')); exit; }

		// suma una vista (best-effort)
		$db->query("UPDATE galeria_publicaciones SET vistas = vistas + 1 WHERE id = :pid");
		$db->bind(':pid', $pubID);
		$db->execute();

		$pub['yaVote'] = false;
		if( $UserID != null ) {

			$db->query("SELECT id FROM galeria_votos WHERE publicacion_id = :pid AND userid = :uid");
			$db->bind(':pid', $pubID);
			$db->bind(':uid', $UserID);
			$db->execute();
			$pub['yaVote'] = $db->rowCount() >= 1 ? true : false;

		}

		$db->query("SELECT c.id, c.comentario, c.idatetime, c.userid, l.user AS autor, l.avatar AS autor_avatar
					FROM galeria_comentarios c
					LEFT JOIN login l ON l.userid = c.userid
					WHERE c.publicacion_id = :pid
					ORDER BY c.idatetime ASC");
		$db->bind(':pid', $pubID);
		$db->execute();
		$comentarios = $db->resultSet();

		$db->CloseConnection();

		echo json_encode(array('ok' => true, 'publicacion' => $pub, 'comentarios' => $comentarios));
		exit;

	}

	// -----------------------------------------------------------
	// comentar: agrega un comentario (requiere sesión)
	// -----------------------------------------------------------
	if( $accion == 'comentar' ) {

		if( $UserID == null ) { echo json_encode(array('ok' => false, 'error' => 'login_required')); exit; }

		$comentario = !empty($_POST['comentario']) ? trim($_POST['comentario']) : null;
		if( $comentario == null ) { echo json_encode(array('ok' => false, 'error' => 'comentario_vacio')); exit; }
		if( mb_strlen($comentario) > 500 ) { $comentario = mb_substr($comentario, 0, 500); }

		$db->query("INSERT INTO galeria_comentarios (publicacion_id, userid, comentario) VALUES (:r1, :r2, :r3)");
		$db->bind(':r1', $pubID);
		$db->bind(':r2', $UserID);
		$db->bind(':r3', $comentario);
		$db->execute();
		$newID = $db->lastID();

		$db->query("SELECT c.id, c.comentario, c.idatetime, c.userid, l.user AS autor, l.avatar AS autor_avatar
					FROM galeria_comentarios c
					LEFT JOIN login l ON l.userid = c.userid
					WHERE c.id = :cid");
		$db->bind(':cid', $newID);
		$db->execute();
		$nuevo = $db->single();

		$db->CloseConnection();

		echo json_encode(array('ok' => true, 'comentario' => $nuevo));
		exit;

	}

	echo json_encode(array('ok' => false, 'error' => 'accion_invalida'));

?>