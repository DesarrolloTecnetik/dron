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

	if (!defined('PATH')) {
		define('PATH', realpath(__DIR__));
	}

	if (!defined('DRONE_BASE_LAT')) {
		define('DRONE_BASE_LAT', 20.6736);
	}
	if (!defined('DRONE_BASE_LON')) {
		define('DRONE_BASE_LON', -103.4059);
	}

	require_once 'init.conf';

	require_once PATH.'/kernel/core/external_api.php';
	$externalApi = new ExternalApiService();
	$externalWeather = $externalApi->weatherAt(DRONE_BASE_LAT, DRONE_BASE_LON);
	$externalGeofences = $externalApi->geofencesAt(DRONE_BASE_LAT, DRONE_BASE_LON);
	$externalLocation = $externalApi->reverseGeocodeAt(DRONE_BASE_LAT, DRONE_BASE_LON);

	if (!empty($_GET['user']) || !empty($_GET['pass'])) {
		// Nunca aceptamos credenciales en query-string. Si llegan, se descartan
		// y se fuerza la navegación a la página de inicio sin exponer user/pass.
		header('Location: ' . URL . '/inicio');
		exit;
	}

	// acción solicitada -> nombre del archivo dentro de kernel/themes/
	$action = !empty($_GET['action']) ? $_GET['action'] : 'inicio';

	// solo letras, números, guiones -> evita path traversal
	$action = preg_replace('/[^a-zA-Z0-9\-_]/', '', $action);

	// /login se resuelve por la dashboard principal para desplegar
	// el modal de acceso dentro del layout. No llevamos a una página aislada.
	if ($action === 'login') {
		$action = 'inicio';
	}

	$themeFile = PATH.'/kernel/themes/'.$action.'.php';

	if( !file_exists($themeFile) ) {

		// tema no encontrado -> 404 amigable, mismo layout
		$action = 'nofound';
		http_response_code(404);

	}

	require PATH.'/kernel/tpl/head.tpl';

	if( $action != 'login' ) {
		require PATH.'/kernel/tpl/body.tpl';
	}

		if( $action == 'nofound' ) {

			echo '<div class="win s12"><div class="win-body"><p class="desc">La página que buscas no existe.</p></div></div>';

		} elseif ($action == 'login') {

			require PATH.'/kernel/themes/login.php';

		} else {

			require PATH.'/kernel/themes/'.$action.'.php';

		}

	if( $action != 'login' ) {
		require PATH.'/kernel/tpl/footer.tpl';
	}

?>