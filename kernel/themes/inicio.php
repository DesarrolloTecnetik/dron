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

	<!-- GEOCERCAS -->
	<div class="win s12" id="win-geocercas">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">GeoCercas · Semáforo de vuelo</div></div>
			<div class="win-status <?php echo $statusZona ?>" id="geo-status-badge"><?php echo $statusLabel ?></div>
		</div>
		<div class="win-body">
			<div class="geo-body">
				<div class="geo-map-col">
					<div id="geo-map" class="geo-map"></div>
				</div>
				<div class="geo-side-col">
					<div class="geo-legend">
						<div class="geo-legend-item"><span class="geo-dot green"></span> Verde · sin restricción cercana</div>
						<div class="geo-legend-item"><span class="geo-dot amber"></span> Precaución · zona cerca</div>
						<div class="geo-legend-item"><span class="geo-dot red"></span> Restringida · dentro del radio</div>
					</div>
					<div class="geo-zone-list">
						<?php if( $geoOK && !empty($externalGeofences['near']) ) { ?>
							<?php foreach( $externalGeofences['near'] as $zona ) { ?>
								<?php
									$dotClass = ($zona['riesgo'] == 'restringida') ? 'red' : (($zona['riesgo'] == 'precaucion') ? 'amber' : 'green');
								?>
								<div class="geo-zone-row">
									<span class="geo-dot <?php echo $dotClass ?>"></span>
									<div>
										<div class="geo-zone-name"><?php echo htmlspecialchars($zona['nombre']) ?></div>
										<div class="geo-zone-meta"><?php echo ucfirst($zona['tipo']) ?> · <?php echo $zona['distancia_km'] ?> km</div>
									</div>
								</div>
							<?php } ?>
						<?php } else { ?>
							<p class="geo-zone-empty" id="geo-zone-empty">No hay geocercas activas cerca de tu ubicación.</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- CLIMA DETALLADO -->
	<div class="win s12" id="win-clima">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Clima · viento y puesta de sol</div></div>
		</div>
		<div class="win-body">

			<?php if( $climaOK ) { ?>
				<div class="chart-grid">
					<div class="chart-col">
						<div class="chart-head">
							<span class="chart-label">Probabilidad de lluvia</span>
							<span class="chart-value" id="rain-now"><?php echo !empty($externalWeather['hourly']['lluvia_pct'][0]) ? $externalWeather['hourly']['lluvia_pct'][0].'%' : '--'; ?> <small>ahora</small></span>
						</div>
						<div class="chart-frame">
							<canvas id="chart-rain" height="170"></canvas>
							<p class="chart-empty" id="chart-rain-empty" style="display:none;">No se pudieron cargar los datos de lluvia por hora.</p>
						</div>
					</div>
					<div class="chart-col">
						<div class="chart-head">
							<span class="chart-label">Viento y ráfagas</span>
							<span class="chart-value" id="wind-now"><?php echo $externalWeather['rafagas_kmh'] ?> <small>km/h ráfaga actual</small></span>
						</div>
						<div class="chart-frame">
							<canvas id="chart-wind" height="170"></canvas>
							<p class="chart-empty" id="chart-wind-empty" style="display:none;">No se pudieron cargar los datos de viento por hora.</p>
						</div>
					</div>
				</div>

				<?php if( !empty($externalWeather['sun']) && $externalWeather['sun']['amanecer'] ) { $sun = $externalWeather['sun']; ?>
					<div class="sun-track">
						<div class="chart-head">
							<span class="chart-label">Ciclo solar</span>
							<span class="chart-value"><?php echo $sun['duracion_label'] ?> <small>de luz hoy</small></span>
						</div>
						<div class="sun-bar">
							<div class="sun-bar-fill" style="width:<?php echo $sun['progreso_pct'] ?>%;"></div>
							<div class="sun-marker" style="left:<?php echo $sun['progreso_pct'] ?>%;">
								<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4 12H2M22 12h-2M5 5l1.5 1.5M17.5 17.5L19 19M5 19l1.5-1.5M17.5 6.5L19 5"/></svg>
							</div>
						</div>
						<div class="sun-grid">
							<div class="data-item"><div class="k">Amanecer</div><div class="v" style="font-size:15px;"><?php echo $sun['amanecer'] ?></div></div>
							<div class="data-item"><div class="k">Atardecer</div><div class="v" style="font-size:15px;"><?php echo $sun['atardecer'] ?></div></div>
							<div class="data-item"><div class="k">Crepúsculo AM</div><div class="v" style="font-size:15px;"><?php echo $sun['crepusculo_manana'] ?></div></div>
							<div class="data-item"><div class="k">Crepúsculo PM</div><div class="v" style="font-size:15px;"><?php echo $sun['crepusculo_tarde'] ?></div></div>
						</div>
					</div>
				<?php } ?>
			<?php } else { ?>
				<p class="desc">No se pudo consultar el clima en este momento. Intenta recargar en unos segundos.</p>
			<?php } ?>

		</div>
	</div>

	<!-- GALERÍA EN MAPA -->
	<div class="win s12" id="win-galeria">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Galería en mapa</div></div>
		</div>
		<div class="win-body">
			<p class="desc" style="margin-bottom:14px;">Publicaciones recientes de la comunidad, ubicadas por sitio de interés.</p>
			<div id="gal-map" class="gal-map"></div>
			<a href="<?php echo URL ?>/inicio/galeria" class="btn accent" style="margin-top:14px;">Ver galería completa</a>
		</div>
	</div>

	<!-- ACCESOS RÁPIDOS -->
	<div class="win s6">
		<div class="win-bar"><div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Bitácora</div></div></div>
		<div class="win-body">
			<p class="desc">Registra tus vuelos con ubicación, duración y condiciones al momento de volar.</p>
			<a href="<?php echo URL ?>/inicio/bitacora" class="btn" style="margin-top:14px;">Próximamente</a>
		</div>
	</div>

	<div class="win s6">
		<div class="win-bar"><div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Noticias</div></div></div>
		<div class="win-body">
			<p class="desc">Actualizaciones de AFAC, fabricantes y la industria, filtradas para droneros en México.</p>
			<a href="<?php echo URL ?>/inicio/noticias" class="btn" style="margin-top:14px;">Próximamente</a>
		</div>
	</div>

</div>

<script>
	window.__radarGeo = <?php echo json_encode(array(
		'lat' => DRONE_BASE_LAT,
		'lon' => DRONE_BASE_LON,
		'near' => $geoOK && !empty($externalGeofences['near']) ? $externalGeofences['near'] : array(),
	)); ?>;
	window.__radarClima = <?php echo json_encode(array(
		'hourly' => $climaOK && !empty($externalWeather['hourly']) ? $externalWeather['hourly'] : array('labels' => array(), 'lluvia_pct' => array(), 'viento_kmh' => array(), 'rafagas_kmh' => array()),
	)); ?>;
</script>