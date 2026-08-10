<?php 

	require '../init.conf';
	$CR->ajaxToken();

	// vars
	$image = !empty($_FILES['image']) ? $_FILES['image'] : null;
	$errorImage = false;
	$nameImage = $avatarU;

	// detect not its null var
	if( $image['name'] != null ) {

		// Info Image
		$nameFile = 'avatar_'.strtolower($userName);
		$typeFiles  = array('image/jpg', 'image/jpeg', 'image/png');
		// Proccess Image
		$handle = new upload($image);
		$handle->allowed = $typeFiles; 
		$handle->file_max_size = 2000000; 
		$handle->file_overwrite = true;
		$handle->file_new_name_body = $nameFile;
		$handle->image_resize = false;
		$newPath = PATH.'/assets/images/avatars/';
		$handle->process($newPath);
		// verified Proccess
		if( $handle->file_is_image ) {

			if( $handle->processed ) {

				// get data and new name imageFile
				$img_ext = $handle->file_src_name_ext;
				$nameImage = $nameFile.'.'.$img_ext;
				$errorImage = false;

			} else { $errorImage = true; }

		} else { $errorImage = true; }

		// finish Process
		if( $errorImage == false ) {

			// update Data
			$CR->updateData("login", "avatar", $nameImage, "userid", $UserID);
			$CR->logs("Actualización de Avatar", "El Usuario ha cambiado su imagen de perfil.", $UserID, null, $UserID);
			echo $CR->updateJS(' alerta("Tu imagen de perfil (avatar) ha sido actualizada.", "success"); button(true); ').$CR->refresh(2);

		} else { echo $CR->updateJS(' alerta("Ocurrió un error al cargar la imagen, intenta nuevamente.", "danger"); button(true); '); }

	} else { echo $CR->updateJS(' alerta("Utiliza una imagen correcta.", "danger"); button(true); '); }

?>