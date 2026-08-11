<?php

	// $externalWeather, $externalGeofences, $externalLocation vienen ya calculados desde index.php

	$climaOK = !empty($externalWeather['ok']);
	$geoOK = !empty($externalGeofences['ok']);

	// ------------------------------------------------------------
	// semáforo de condiciones -> reglas simples, ajustables
	// ------------------------------------------------------------
	$statusZona = 'neutral'; $statusLabel = 'Sin datos suficientes';

	if( $climaOK && $geoOK ) {

		if( $externalGeofences['zona'] == 'restringida' ) {

			$statusZona = 'red'; $statusLabel = 'Zona restringida';

		} elseif( $externalWeather['viento_kmh'] > 30 || $externalWeather['rafagas_kmh'] > 40 ) {

			$statusZona = 'red'; $statusLabel = 'Viento fuera de rango';

		} elseif( $externalWeather['viento_kmh'] > 20 || $externalWeather['rafagas_kmh'] > 30 ) {

			$statusZona = 'amber'; $statusLabel = 'Precaución por viento';

		} else {

			$statusZona = 'green'; $statusLabel = 'Puedes volar';

		}

	}

?>

<div class="desk">

	<div style="grid-column:span 12;">
		<div class="eyebrow">Briefing de hoy</div>
		<h1 style="font-family:var(--grot);font-size:26px;font-weight:600;">
			<?php echo ($externalLocation['ok']) ? ($externalLocation['ciudad'] ?: $externalLocation['nombre']) : 'Tu zona de vuelo'; ?>
		</h1>
	</div>

	<!-- ESTADO DE VUELO -->
	<div class="win s12">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Estado de vuelo</div></div>
			<div class="win-status <?php echo $statusZona ?>"><?php echo $statusLabel ?></div>
		</div>
		<div class="win-body">

			<?php if( $climaOK ) { ?>
				<div class="data-grid">
					<div class="data-item"><div class="k">Viento</div><div class="v"><?php echo $externalWeather['viento_kmh'] ?> <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Ráfagas</div><div class="v"><?php echo $externalWeather['rafagas_kmh'] ?> <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Temperatura</div><div class="v"><?php echo round($externalWeather['temperatura_c']) ?>° <small>C</small></div></div>
					<div class="data-item"><div class="k">Zona</div><div class="v" style="font-size:15px;"><?php echo $geoOK ? ucfirst($externalGeofences['zona']) : '—' ?></div></div>
				</div>
			<?php } else { ?>
				<p class="desc">No se pudo consultar el clima en este momento (<?php echo isset($externalWeather['error']) ? $externalWeather['error'] : 'sin conexión' ?>). Intenta recargar en unos segundos.</p>
			<?php } ?>

			<?php if( $geoOK ) { ?>
				<p class="desc" style="margin-top:14px;">
					Aeropuerto más cercano: <strong><?php echo $externalGeofences['aeropuerto_cercano'] ?></strong>
					a <?php echo $externalGeofences['distancia_km'] ?> km. <?php echo $externalGeofences['nota'] ?>
				</p>
			<?php } ?>

		</div>
	</div>

	<!-- ACCESOS RÁPIDOS -->
	<div class="win s4">
		<div class="win-bar"><div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Galería en mapa</div></div></div>
		<div class="win-body">
			<p class="desc">Explora fotos y videos compartidos por sitio de interés, y revisa el Top de la comunidad.</p>
			<a href="<?php echo URL ?>/inicio/galeria" class="btn accent" style="margin-top:14px;">Ver galería</a>
		</div>
	</div>

	<div class="win s4">
		<div class="win-bar"><div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Bitácora</div></div></div>
		<div class="win-body">
			<p class="desc">Registra tus vuelos con ubicación, duración y condiciones al momento de volar.</p>
			<a href="<?php echo URL ?>/inicio/bitacora" class="btn" style="margin-top:14px;">Próximamente</a>
		</div>
	</div>

	<div class="win s4">
		<div class="win-bar"><div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Noticias</div></div></div>
		<div class="win-body">
			<p class="desc">Actualizaciones de AFAC, fabricantes y la industria, filtradas para droneros en México.</p>
			<a href="<?php echo URL ?>/inicio/noticias" class="btn" style="margin-top:14px;">Próximamente</a>
		</div>
	</div>

</div>
