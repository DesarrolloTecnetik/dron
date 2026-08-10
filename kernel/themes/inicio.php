<?php
	// ============================================================
	// THEME: inicio  (dashboard principal de RADAR)
	// Cargado por index.php entre body.tpl y footer.tpl
	// Los datos principales del dashboard se recogen de servicios
	// externos: Open-Meteo (temperatura/viento/ráfagas) y Overpass
	// (geocercas / áreas protegidas / aeródromos cercanos)
	// ============================================================

	$weather = isset($externalWeather) ? $externalWeather : null;
	$geofences = isset($externalGeofences) ? $externalGeofences : null;
	$windKmh = !empty($weather['wind_kmh']) ? round((float)$weather['wind_kmh']) : null;
	$gustsKmh = !empty($weather['gusts_kmh']) ? round((float)$weather['gusts_kmh']) : null;
	$tempC = !empty($weather['temperature_c']) ? round((float)$weather['temperature_c'], 1) : null;
	$weatherCondition = !empty($weather['condition']) ? $weather['condition'] : 'Sin datos';
	$visibilityKm = ($weather['visibility_km'] ?? 10);
	$geofenceRisk = !empty($geofences['risk_level']) ? $geofences['risk_level'] : 'none';
	$geoStatus = ($geofenceRisk === 'none') ? 'Sin restricciones' : strtoupper($geofenceRisk);

	// Semáforo de riesgo: mismo criterio para "Estado de vuelo" y "GeoCercas"
	$riskClass = 'green';
	$riskLabel = 'Zona verde';
	$lightRingClass = '';
	$flightLabel = 'Puedes volar';
	if ($geofenceRisk === 'restricted') {
		$riskClass = 'red';
		$riskLabel = 'No volar';
		$lightRingClass = 'red';
		$flightLabel = 'No volar aquí';
	} elseif ($geofenceRisk === 'advisory') {
		$riskClass = 'amber';
		$riskLabel = 'Precaución';
		$lightRingClass = 'amber';
		$flightLabel = 'Volar con precaución';
	}

	// Radio de referencia (metros) para dibujar cada geocerca en el mapa.
	// Las zonas de la base de datos ya traen su propio radio (radio_m);
	// para las detectadas por Overpass (solo un punto) usamos un radio
	// típico según el tipo de zona.
	function radarZoneRadius($zone) {
		if (!empty($zone['radio_m'])) {
			return (int) $zone['radio_m'];
		}
		$defaults = array('aerodrome' => 1500, 'military' => 2000, 'protected_area' => 1000);
		return isset($defaults[$zone['type']]) ? $defaults[$zone['type']] : 800;
	}

	// Distancia en metros al punto activo (haversine), para zonas que no
	// la traen ya calculada (las de Overpass).
	function radarZoneDistance($zone, $centerLat, $centerLon) {
		if (isset($zone['distance_m'])) {
			return (int) $zone['distance_m'];
		}
		if (empty($zone['lat']) || empty($zone['lon'])) {
			return null;
		}
		$earthRadius = 6371000;
		$dLat = deg2rad($zone['lat'] - $centerLat);
		$dLon = deg2rad($zone['lon'] - $centerLon);
		$a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($centerLat)) * cos(deg2rad($zone['lat'])) * sin($dLon / 2) * sin($dLon / 2);
		$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
		return (int) round($earthRadius * $c);
	}

	$zoneTypeLabels = array(
		'aerodrome' => 'Aeródromo',
		'military' => 'Zona militar',
		'protected_area' => 'Área protegida',
	);

	$geoCenterLat = !empty($geofences['lat']) ? (float) $geofences['lat'] : 20.6736;
	$geoCenterLon = !empty($geofences['lon']) ? (float) $geofences['lon'] : -103.4059;
	$geoZonesRaw = !empty($geofences['near_geofences']) ? $geofences['near_geofences'] : array();

	$geoZonesForMap = array();
	foreach ($geoZonesRaw as $zone) {
		$geoZonesForMap[] = array(
			'name' => !empty($zone['name']) ? $zone['name'] : 'Zona sin nombre',
			'type' => !empty($zone['type']) ? $zone['type'] : 'zona',
			'type_label' => isset($zoneTypeLabels[$zone['type']]) ? $zoneTypeLabels[$zone['type']] : 'Zona restringida',
			'risk_level' => !empty($zone['risk_level']) ? $zone['risk_level'] : 'advisory',
			'lat' => isset($zone['lat']) ? (float) $zone['lat'] : null,
			'lon' => isset($zone['lon']) ? (float) $zone['lon'] : null,
			'radius_m' => radarZoneRadius($zone),
			'distance_m' => radarZoneDistance($zone, $geoCenterLat, $geoCenterLon),
		);
	}

	// Ubicación desde donde se están tomando los datos (clima/geocercas)
	$location = isset($externalLocation) ? $externalLocation : null;
	$locationParts = array();
	if (!empty($location['zone'])) { $locationParts[] = $location['zone']; }
	if (!empty($location['municipality'])) { $locationParts[] = $location['municipality']; }
	if (!empty($location['state'])) { $locationParts[] = $location['state']; }
	$locationLabelText = !empty($locationParts) ? implode(', ', $locationParts) : 'Detectando ubicación…';

	// Datos horarios (lluvia / viento / ráfagas) y ciclo solar del punto activo
	$sun = isset($weather['sun']) ? $weather['sun'] : array();
	$hourlyForecast = isset($weather['hourly_forecast']) ? $weather['hourly_forecast'] : array();
	$rainMaxToday = !empty($weather['daily']['rain_chance_pct']) ? $weather['daily']['rain_chance_pct'] : null;

	$chartLabels = array();
	$chartRain = array();
	$chartWind = array();
	$chartGusts = array();
	foreach ($hourlyForecast as $h) {
		$chartLabels[] = $h['hour_label'];
		$chartRain[] = $h['rain_chance_pct'];
		$chartWind[] = $h['wind_kmh'];
		$chartGusts[] = $h['gusts_kmh'];
	}

	$sunProgress = isset($sun['day_progress_pct']) ? (int) $sun['day_progress_pct'] : null;
?>

	<!-- ESTADO -->
	<div class="win s12" id="win-estado">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Estado de vuelo</div></div>
			<div class="win-status <?php echo $riskClass; ?>" id="estado-status-badge"><?php echo $riskLabel; ?></div>
		</div>
		<div class="win-body estado-body">
			<div class="light-col">
				<div class="light-ring<?php echo $lightRingClass ? ' '.$lightRingClass : ''; ?>" id="estado-light-ring"><div class="core"></div></div>
				<div class="light-label" id="estado-light-label"><?php echo $flightLabel; ?></div>
				<div class="light-sub" id="estado-light-sub"><?php echo $geoStatus; ?></div>
			</div>
			<div>
				<div class="estado-location" id="estado-location">
					<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 21s-7-6.1-7-11a7 7 0 0 1 14 0c0 4.9-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
					<span id="estado-location-text"><?php echo htmlspecialchars($locationLabelText); ?></span>
				</div>
				<div class="data-grid">
					<div class="data-item"><div class="k">Viento</div><div class="v"><?php echo ($windKmh !== null) ? $windKmh : '--'; ?> <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Ráfagas</div><div class="v"><?php echo ($gustsKmh !== null) ? $gustsKmh : '--'; ?> <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Temperatura</div><div class="v"><?php echo ($tempC !== null) ? $tempC : '--'; ?> <small>°C</small></div></div>
					<div class="data-item"><div class="k">Condición</div><div class="v"><?php echo htmlspecialchars($weatherCondition); ?></div></div>
				</div>
				<div class="row-between">
					<div class="checklist-mini">
						<span class="ok">Batería 92%</span>
						<span class="ok">GPS 14 sats</span>
						<span class="ok">Firmware al día</span>
					</div>
					<button class="btn accent">Iniciar vuelo</button>
				</div>
			</div>
		</div>
	</div>

	<!-- PRONÓSTICO: LLUVIA / VIENTO -->
	<div class="win s12" id="win-clima">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Pronóstico de vuelo · próximas 24h</div></div>
			<div class="win-status neutral">Open-Meteo</div>
		</div>
		<div class="win-body chart-grid">
			<div class="chart-col">
				<div class="chart-head">
					<span class="chart-label">Probabilidad de lluvia</span>
					<span class="chart-value" id="rain-max-today"><?php echo ($rainMaxToday !== null) ? $rainMaxToday.'%' : '--'; ?> <small>hoy</small></span>
				</div>
				<div class="chart-frame">
					<canvas id="chart-rain" height="170"></canvas>
					<p class="chart-empty" id="chart-rain-empty" style="display:none;">No se pudieron cargar los datos de lluvia por hora.</p>
				</div>
			</div>
			<div class="chart-col">
				<div class="chart-head">
					<span class="chart-label">Viento y ráfagas</span>
					<span class="chart-value" id="wind-max-today"><?php echo ($gustsKmh !== null) ? $gustsKmh : '--'; ?> <small>km/h ráfaga actual</small></span>
				</div>
				<div class="chart-frame">
					<canvas id="chart-wind" height="170"></canvas>
					<p class="chart-empty" id="chart-wind-empty" style="display:none;">No se pudieron cargar los datos de viento por hora.</p>
				</div>
			</div>
		</div>
	</div>

	<!-- CICLO SOLAR -->
	<div class="win s12" id="win-sol">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Ciclo solar</div></div>
			<div class="win-status neutral" id="sun-daylight-badge"><?php echo !empty($sun['daylight_label']) ? $sun['daylight_label'].' de luz' : 'Sin datos'; ?></div>
		</div>
		<div class="win-body">
			<div class="sun-track">
				<div class="sun-bar">
					<div class="sun-bar-fill" id="sun-bar-fill" style="width:<?php echo ($sunProgress !== null) ? $sunProgress : 0; ?>%;"></div>
					<div class="sun-marker" id="sun-marker" style="left:<?php echo ($sunProgress !== null) ? $sunProgress : 0; ?>%;">
						<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
					</div>
				</div>
			</div>
			<div class="sun-grid">
				<div class="data-item"><div class="k">Amanece</div><div class="v" id="sun-dawn"><?php echo !empty($sun['dawn']) ? $sun['dawn'] : '--:--'; ?></div></div>
				<div class="data-item"><div class="k">Salida del sol</div><div class="v" id="sun-sunrise"><?php echo !empty($sun['sunrise']) ? $sun['sunrise'] : '--:--'; ?></div></div>
				<div class="data-item"><div class="k">Puesta del sol</div><div class="v" id="sun-sunset"><?php echo !empty($sun['sunset']) ? $sun['sunset'] : '--:--'; ?></div></div>
				<div class="data-item"><div class="k">Oscurece</div><div class="v" id="sun-dusk"><?php echo !empty($sun['dusk']) ? $sun['dusk'] : '--:--'; ?></div></div>
			</div>
			<p class="desc">La zona sombreada indica horas de luz natural entre la salida y la puesta del sol. "Amanece" y "Oscurece" son crepúsculo civil aproximado, la mejor ventana de referencia para vuelos al límite de la luz del día.</p>
		</div>
	</div>

	<!-- GEOCERCAS -->
	<div class="win s12" id="win-mapa">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">GeoCercas · Semáforo de vuelo</div></div>
			<div class="win-status <?php echo $riskClass; ?>" id="mapa-status-badge"><?php echo $riskLabel; ?></div>
		</div>
		<div class="win-body geo-body">
			<div class="geo-map-col">
				<div id="geo-map" class="geo-map"></div>
				<p class="loc-label" id="live-location-label">Ubicación: <?php echo htmlspecialchars($locationLabelText); ?></p>
			</div>
			<div class="geo-side-col">
				<div class="geo-legend">
					<div class="geo-legend-item"><span class="geo-dot green"></span>Despejado — puedes volar</div>
					<div class="geo-legend-item"><span class="geo-dot amber"></span>Precaución — revisa la zona</div>
					<div class="geo-legend-item"><span class="geo-dot red"></span>No volar — zona restringida</div>
				</div>
				<p class="desc" id="geo-summary">
					<?php
						if (!empty($geoZonesForMap)) {
							echo 'Zonas cercanas detectadas: ' . count($geoZonesForMap) . ' · Nivel: ' . htmlspecialchars($geofenceRisk);
						} else {
							echo 'Sin zonas restringidas ni áreas protegidas detectadas en un radio cercano.';
						}
					?>
				</p>
				<div class="geo-zone-list" id="geo-zone-list">
					<?php if (empty($geoZonesForMap)): ?>
						<p class="geo-zone-empty" id="geo-zone-empty">No hay geocercas activas cerca de tu ubicación.</p>
					<?php else: ?>
						<?php foreach ($geoZonesForMap as $zone): ?>
							<div class="geo-zone-row">
								<span class="geo-dot <?php echo ($zone['risk_level'] === 'restricted') ? 'red' : 'amber'; ?>"></span>
								<div class="geo-zone-info">
									<div class="geo-zone-name"><?php echo htmlspecialchars($zone['name']); ?></div>
									<div class="geo-zone-meta">
										<?php
											echo htmlspecialchars($zone['type_label']);
											if ($zone['distance_m'] !== null) {
												echo ' · a ' . number_format($zone['distance_m'] / 1000, 1) . ' km';
											}
										?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<script>
		(function initGeoMap() {
			if (!window.L) {
				console.error('RADAR: Leaflet no se cargó (revisa assets/js/leaflet.js). El mapa de geocercas no se mostrará.');
				const mapEl = document.getElementById('geo-map');
				if (mapEl) { mapEl.innerHTML = '<p class="chart-empty" style="position:static;transform:none;padding:60px 16px;">No se pudo cargar la librería del mapa (Leaflet).</p>'; }
				return;
			}

			const centerLat = <?php echo json_encode($geoCenterLat); ?>;
			const centerLon = <?php echo json_encode($geoCenterLon); ?>;
			const zones = <?php echo json_encode($geoZonesForMap); ?>;

			const mapEl = document.getElementById('geo-map');
			if (!mapEl) { return; }

			const map = L.map('geo-map', { zoomControl: true, attributionControl: true }).setView([centerLat, centerLon], 12);

			L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
				maxZoom: 18,
				attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
			}).addTo(map);

			const userMarker = L.circleMarker([centerLat, centerLon], {
				radius: 8,
				color: '#14171A',
				weight: 2,
				fillColor: '#FF5A29',
				fillOpacity: 1
			}).addTo(map).bindPopup('Tu ubicación');

			const zoneColor = (riskLevel, type) => {
				if (riskLevel === 'restricted') {
					return '#D6483C';
				}
				if (type === 'aerodrome' || type === 'airport' || type === 'aeropuerto' || type === 'aeropuerto') {
					return '#2473FF';
				}
				return '#C98A12';
			};

			let zoneLayers = [];
			zones.forEach(zone => {
				if (zone.lat === null || zone.lon === null) { return; }
				const color = zoneColor(zone.risk_level, zone.type);
				const circle = L.circle([zone.lat, zone.lon], {
					radius: zone.radius_m || 800,
					color: color,
					weight: 1.5,
					fillColor: color,
					fillOpacity: 0.18
				}).addTo(map).bindPopup('<strong>' + zone.name + '</strong><br>' + zone.type_label);
				zoneLayers.push(circle);

				if (zone.type === 'aerodrome' || zone.type === 'airport' || zone.type === 'aeropuerto') {
					const marker = L.circleMarker([zone.lat, zone.lon], {
						radius: 6,
						color: '#1D75DD',
						weight: 2,
						fillColor: '#88DBFF',
						fillOpacity: 0.9
					}).addTo(map).bindPopup('<strong>' + zone.name + '</strong><br>Aeródromo / Aeropuerto');
					zoneLayers.push(marker);
				}
			});

			if (!zones.length) {
				const clearCircle = L.circle([centerLat, centerLon], {
					radius: 1200,
					color: '#1FA463',
					weight: 1.5,
					fillColor: '#1FA463',
					fillOpacity: 0.10
				}).addTo(map);
				zoneLayers.push(clearCircle);
			}

			window.__radarMap = { map, userMarker, zoneLayers, zoneColor };
		})();
	</script>

	<script>
		(function initClimateCharts() {
			const labels = <?php echo json_encode($chartLabels); ?>;
			const rainData = <?php echo json_encode($chartRain); ?>;
			const windData = <?php echo json_encode($chartWind); ?>;
			const gustsData = <?php echo json_encode($chartGusts); ?>;

			const ink = '#14171A';
			const inkDim = '#767C87';
			const border = '#E5E7EB';
			const accent = '#FF5A29';

			const rainCanvas = document.getElementById('chart-rain');
			const windCanvas = document.getElementById('chart-wind');
			const rainEmpty = document.getElementById('chart-rain-empty');
			const windEmpty = document.getElementById('chart-wind-empty');

			if (!window.Chart) {
				console.error('RADAR: Chart.js no se cargó (revisa assets/js/chart.umd.min.js). Las gráficas no se mostrarán.');
				if (rainCanvas) rainCanvas.style.display = 'none';
				if (windCanvas) windCanvas.style.display = 'none';
				if (rainEmpty) { rainEmpty.textContent = 'No se pudo cargar la librería de gráficas (Chart.js).'; rainEmpty.style.display = 'block'; }
				if (windEmpty) { windEmpty.textContent = 'No se pudo cargar la librería de gráficas (Chart.js).'; windEmpty.style.display = 'block'; }
				return;
			}
			if (!rainCanvas || !windCanvas) { return; }

			window.__radarCharts = window.__radarCharts || {};

			window.__radarCharts.rain = new Chart(rainCanvas.getContext('2d'), {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Prob. de lluvia (%)',
						data: rainData,
						backgroundColor: '#3B82F6',
						borderRadius: 3,
						barThickness: 10,
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: { legend: { display: false } },
					scales: {
						x: { ticks: { color: inkDim, font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 }, grid: { display: false } },
						y: { min: 0, max: 100, ticks: { color: inkDim, font: { size: 10 }, callback: v => v + '%' }, grid: { color: border } }
					}
				}
			});

			window.__radarCharts.wind = new Chart(windCanvas.getContext('2d'), {
				type: 'line',
				data: {
					labels: labels,
					datasets: [
						{
							label: 'Ráfagas (km/h)',
							data: gustsData,
							borderColor: accent,
							backgroundColor: 'transparent',
							borderWidth: 2,
							pointRadius: 0,
							tension: 0.35,
						},
						{
							label: 'Viento sostenido (km/h)',
							data: windData,
							borderColor: ink,
							backgroundColor: 'transparent',
							borderWidth: 2,
							borderDash: [4, 3],
							pointRadius: 0,
							tension: 0.35,
						}
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: { display: true, position: 'top', align: 'end', labels: { boxWidth: 8, boxHeight: 8, color: inkDim, font: { size: 10 } } }
					},
					scales: {
						x: { ticks: { color: inkDim, font: { size: 10 }, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 }, grid: { display: false } },
						y: { min: 0, ticks: { color: inkDim, font: { size: 10 }, callback: v => v + ' km/h' }, grid: { color: border } }
					}
				}
			});

			toggleEmptyState(labels, rainCanvas, rainEmpty);
			toggleEmptyState(labels, windCanvas, windEmpty);
		})();

		function toggleEmptyState(labels, canvas, emptyEl) {
			const hasData = Array.isArray(labels) && labels.length > 0;
			if (canvas) { canvas.style.display = hasData ? 'block' : 'none'; }
			if (emptyEl) {
				emptyEl.textContent = 'Sin datos por hora disponibles todavía. Se actualizará al detectar tu ubicación.';
				emptyEl.style.display = hasData ? 'none' : 'block';
			}
		}
	</script>