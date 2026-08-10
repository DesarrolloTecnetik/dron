<?php
	// ============================================================
	// THEME: inicio  (dashboard principal de RADAR)
	// Cargado por index.php entre body.tpl y footer.tpl
	// Todos los valores son de ejemplo (placeholders) hasta que
	// se conecte la base de datos — ver /database/schema.sql
	// ============================================================
?>

	<!-- ESTADO -->
	<div class="win s12" id="win-estado">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Estado de vuelo</div></div>
			<div class="win-status green">Zona verde</div>
		</div>
		<div class="win-body estado-body">
			<div class="light-col">
				<div class="light-ring"><div class="core"></div></div>
				<div class="light-label">Puedes volar</div>
				<div class="light-sub">Sin restricciones activas</div>
			</div>
			<div>
				<div class="data-grid">
					<div class="data-item"><div class="k">Viento</div><div class="v">14 <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Ráfagas</div><div class="v">22 <small>km/h</small></div></div>
					<div class="data-item"><div class="k">Visibilidad</div><div class="v">10 <small>km</small></div></div>
					<div class="data-item"><div class="k">Kp index</div><div class="v">2 <small>normal</small></div></div>
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

	<!-- GEOCERCAS -->
	<div class="win s7" id="win-mapa">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">GeoCercas</div></div>
			<div class="win-status neutral">AFAC · KML</div>
		</div>
		<div class="win-body">
			<div class="map-frame"><div class="map-zone"></div><div class="map-pin"></div></div>
			<p class="desc">Aeropuerto más cercano a 14 km. Sin zonas restringidas ni áreas protegidas en un radio de 5 km.</p>
		</div>
	</div>

	<!-- CHECKLIST -->
	<div class="win s5" id="win-checklist">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Checklist</div></div>
			<div class="win-status green">6/6</div>
		</div>
		<div class="win-body" style="padding-top:8px;">
			<div class="check-item"><div class="box"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>Batería principal cargada</div>
			<div class="check-item"><div class="box"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>Tarjeta SD con espacio</div>
			<div class="check-item"><div class="box"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>Hélices sin daño</div>
			<div class="check-item"><div class="box"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><path d="M20 6L9 17l-5-5"/></svg></div>GPS con señal estable</div>
		</div>
	</div>

	<!-- BITACORA -->
	<div class="win s7" id="win-bitacora">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Bitácora de vuelos</div></div>
			<div class="win-status neutral">128 hrs</div>
		</div>
		<div class="win-body">
			<div class="flight-row"><span class="loc">Parque Metropolitano, Zapopan</span><span class="meta">HOY · 18 MIN · MAVIC 3</span></div>
			<div class="flight-row"><span class="loc">Bosque La Primavera</span><span class="meta">AYER · 34 MIN · FPV FREESTYLE</span></div>
			<div class="flight-row"><span class="loc">Presa La Vega</span><span class="meta">6 AGO · 22 MIN · MAVIC 3</span></div>
			<div class="stat-strip">
				<div><div class="k">Vuelos</div><div class="v">47</div></div>
				<div><div class="k">Este mes</div><div class="v">6h 40m</div></div>
				<div><div class="k">Incidentes</div><div class="v">0</div></div>
			</div>
		</div>
	</div>

	<!-- EQUIPO -->
	<div class="win s5" id="win-equipo">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Equipo y baterías</div></div>
			<div class="win-status amber">1 en desgaste</div>
		</div>
		<div class="win-body" style="padding-top:14px;">
			<div class="batt-row"><span style="width:60px;color:var(--ink-dim);font-size:12px;">Batt. 1</span><div class="batt-bar"><i style="width:92%;"></i></div><span class="batt-pct">92%</span></div>
			<div class="batt-row"><span style="width:60px;color:var(--ink-dim);font-size:12px;">Batt. 2</span><div class="batt-bar"><i style="width:78%;"></i></div><span class="batt-pct">78%</span></div>
			<div class="batt-row"><span style="width:60px;color:var(--ink-dim);font-size:12px;">Batt. 3</span><div class="batt-bar"><i class="amber" style="width:41%;"></i></div><span class="batt-pct">41%</span></div>
		</div>
	</div>

	<!-- NOTICIAS -->
	<div class="win s12" id="win-noticias">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Noticias del sector</div></div>
			<div class="win-status neutral">Actualizado hoy</div>
		</div>
		<div class="win-body" style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;">
			<div class="news-row"><div class="news-src">AFAC</div><div class="news-head">Actualización de la circular para operaciones RPAS en zonas urbanas</div></div>
			<div class="news-row"><div class="news-src">DJI</div><div class="news-head">Nuevo firmware mejora la estabilidad con viento cruzado en la serie Mavic</div></div>
			<div class="news-row"><div class="news-src">Industria</div><div class="news-head">Crece la demanda de pilotos certificados en Jalisco</div></div>
		</div>
	</div>