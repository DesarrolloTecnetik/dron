<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	global $UserID;

	$sitioID = !empty($_POST['sitio_id']) ? (int)$_POST['sitio_id'] : null;
	$orden   = !empty($_POST['orden']) ? $_POST['orden'] : 'recientes'; // recientes | top
	$limite  = !empty($_POST['limite']) ? (int)$_POST['limite'] : 60;

	$where = "WHERE p.status = 1";
	if( $sitioID != null ) { $where .= " AND p.sitio_id = :sitio_id"; }

	$orderBy = ($orden == 'top') ? "p.votos DESC, p.idatetime DESC" : "p.idatetime DESC";

	$sql = "SELECT p.id, p.tipo, p.archivo, p.titulo, p.descripcion, p.lat, p.lng,
					p.votos, p.vistas, p.idatetime, p.userid,
					l.user AS autor, l.avatar AS autor_avatar,
					s.id AS sitio_id, s.nombre AS sitio_nombre
			FROM galeria_publicaciones p
			LEFT JOIN login l ON l.userid = p.userid
			LEFT JOIN galeria_sitios s ON s.id = p.sitio_id
			$where
			ORDER BY $orderBy
			LIMIT $limite";

	$db->query($sql);
	if( $sitioID != null ) { $db->bind(':sitio_id', $sitioID); }
	$db->execute();
	$publicaciones = $db->resultSet();

	// marcar si el usuario actual ya votó cada publicación
	if( $UserID != null && !empty($publicaciones) ) {

		foreach( $publicaciones as &$pub ) {

			$db->query("SELECT id FROM galeria_votos WHERE publicacion_id = :pid AND userid = :uid");
			$db->bind(':pid', $pub['id']);
			$db->bind(':uid', $UserID);
			$db->execute();
			$pub['yaVote'] = $db->rowCount() >= 1 ? true : false;

		}
		unset($pub);

	} else if( !empty($publicaciones) ) {

		foreach( $publicaciones as &$pub ) { $pub['yaVote'] = false; }
		unset($pub);

	}

	$db->CloseConnection();

	echo json_encode(array('ok' => true, 'publicaciones' => $publicaciones));

?>
