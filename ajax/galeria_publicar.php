<?php

	require '../init.conf';
	header('Content-Type: application/json; charset=utf-8');

	// requiere sesión iniciada
	if( $UserID == null ) { echo json_encode(array('ok' => false, 'error' => 'login_required')); exit; }

	$archivo    = !empty($_FILES['archivo']) ? $_FILES['archivo'] : null;
	$sitioID    = !empty($_POST['sitio_id']) ? (int)$_POST['sitio_id'] : null;
	$titulo     = !empty($_POST['titulo']) ? trim($_POST['titulo']) : null;
	$descripcion= !empty($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
	$lat        = !empty($_POST['lat']) ? $_POST['lat'] : null;
	$lng        = !empty($_POST['lng']) ? $_POST['lng'] : null;

	if( $archivo == null || $lat == null || $lng == null ) {
		echo json_encode(array('ok' => false, 'error' => 'datos_incompletos')); exit;
	}

	// detectar si es foto o video por MIME
	$mime = $archivo['type'];
	$esVideo = (strpos($mime, 'video') !== false);
	$tipo = $esVideo ? 'video' : 'foto';

	$tiposPermitidos = $esVideo
		? array('video/mp4', 'video/quicktime', 'video/webm', 'video/x-m4v')
		: array('image/jpg', 'image/jpeg', 'image/png', 'image/webp', 'image/HEIC');

	$nameFile = 'galeria_'.$UserID.'_'.rand(0, 999).'_'.date('ymdhis');

	$handle = new upload($archivo);
	$handle->allowed = $tiposPermitidos;
	$handle->file_max_size = $esVideo ? 80000000 : 12000000; // 80MB video / 12MB foto
	$handle->file_overwrite = true;
	$handle->file_new_name_body = $nameFile;
	$handle->image_resize = false;

	$newPath = PATH.'/assets/media/galeria/';
	$handle->process($newPath);

	if( $handle->processed ) {

		$ext = $handle->file_src_name_ext;
		$nombreArchivo = $nameFile.'.'.$ext;

		$db->query("INSERT INTO galeria_publicaciones
						(userid, sitio_id, tipo, archivo, titulo, descripcion, lat, lng)
					VALUES
						(:r1, :r2, :r3, :r4, :r5, :r6, :r7, :r8)");
		$db->bind(':r1', $UserID);
		$db->bind(':r2', $sitioID);
		$db->bind(':r3', $tipo);
		$db->bind(':r4', $nombreArchivo);
		$db->bind(':r5', $titulo);
		$db->bind(':r6', $descripcion);
		$db->bind(':r7', $lat);
		$db->bind(':r8', $lng);
		$db->execute();
		$newID = $db->lastID();
		$db->CloseConnection();

		echo json_encode(array('ok' => true, 'id' => $newID, 'archivo' => $nombreArchivo, 'tipo' => $tipo));

	} else {

		echo json_encode(array('ok' => false, 'error' => 'upload_fallo'));

	}

?>
