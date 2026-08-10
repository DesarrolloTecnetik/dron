<?php
	/*
	 * index.php — punto de entrada del sitio
	 *
	 * Sigue el patrón de rutas ya definido en .htaccess:
	 *   /inicio            -> index.php               (action = 'inicio')
	 *   /inicio/algo       -> index.php?action=algo
	 *
	 * Arma cada página combinando:
	 *   kernel/tpl/head.tpl      (<head>, fuentes, CSS)
	 *   kernel/tpl/body.tpl      (topbar + intro, compartidos)
	 *   kernel/themes/{action}.php  (contenido específico de la página)
	 *   kernel/tpl/footer.tpl    (cierre + JS del dock)
	 *
	 * NOTA: init.conf se conecta a MySQL de inmediato (tabla `config`).
	 * Hasta que exista la base de datos con /database/schema.sql importado
	 * y las credenciales en kernel/core/config.php, esta página mostrará
	 * el mensaje de conexión fallida definido en kernel/database.php —
	 * eso es esperado mientras se trabaja solo en el código/plantillas.
	 */

	require 'init.conf';

	// acción solicitada -> nombre del archivo dentro de kernel/themes/
	$action = !empty($_GET['action']) ? $_GET['action'] : 'inicio';

	// solo letras, números, guiones -> evita path traversal
	$action = preg_replace('/[^a-zA-Z0-9\-_]/', '', $action);

	$themeFile = PATH.'/kernel/themes/'.$action.'.php';

	if( !file_exists($themeFile) ) {

		// tema no encontrado -> 404 amigable, mismo layout
		$action = 'nofound';
		http_response_code(404);

	}

	require PATH.'/kernel/tpl/head.tpl';
	require PATH.'/kernel/tpl/body.tpl';

		if( $action == 'nofound' ) {

			echo '<div class="win s12"><div class="win-body"><p class="desc">La página que buscas no existe.</p></div></div>';

		} else {

			require PATH.'/kernel/themes/'.$action.'.php';

		}

	require PATH.'/kernel/tpl/footer.tpl';

?>