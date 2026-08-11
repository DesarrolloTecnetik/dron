<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

	// ---------------------------------------------------------------
	// Helpers globales usados por $CR->updateJS() en las respuestas AJAX
	// (ej. modal-basic.php hace: alerta("...", "danger"); button(true); modalX();)
	// ---------------------------------------------------------------

	function alerta(mensaje, tipo) {

		tipo = tipo || 'success';
		var colores = { success: '#1FA463', danger: '#D6483C', warning: '#C98A12' };
		var caja = document.createElement('div');
		caja.textContent = mensaje;
		caja.style.cssText = 'position:fixed;top:18px;right:18px;z-index:200;background:#fff;'
			+ 'border-left:3px solid ' + (colores[tipo] || colores.success) + ';border-radius:8px;'
			+ 'box-shadow:0 8px 24px -8px rgba(20,23,26,.25);padding:12px 16px;font-family:Inter,sans-serif;'
			+ 'font-size:13px;max-width:280px;';
		document.querySelector('.sbalerts').appendChild(caja);
		setTimeout(function() { caja.remove(); }, 4000);

	}

	function modalX() {

		document.querySelectorAll('.modalSB').forEach(function(m) { m.style.display = 'none'; });
		document.querySelectorAll('.loadermodals').forEach(function(m) { m.innerHTML = ''; });

	}

	function button(activar) {

		document.querySelectorAll('button[disabled]').forEach(function(b) { if (activar) b.disabled = false; });

	}

	// =================================================================
	// GEOCERCAS -> mapa Leaflet + semáforo
	// =================================================================
	(function initGeoMap() {

		var mapEl = document.getElementById('geo-map');
		if (!mapEl) { return; }

		if (!window.L) {
			console.error('RADAR: Leaflet no se cargó. El mapa de geocercas no se mostrará.');
			mapEl.innerHTML = '<p class="chart-empty" style="position:static;transform:none;padding:60px 16px;">No se pudo cargar la librería del mapa (Leaflet).</p>';
			return;
		}

		var geo = window.__radarGeo || { lat: 20.6736, lon: -103.4059, near: [] };

		var map = L.map(mapEl).setView([geo.lat, geo.lon], 11);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 18,
			attribution: '&copy; OpenStreetMap'
		}).addTo(map);

		L.marker([geo.lat, geo.lon]).addTo(map).bindPopup('Tu ubicación');

		var colores = { restringida: '#D6483C', precaucion: '#C98A12', informativa: '#1FA463' };

		(geo.near || []).forEach(function(zona) {

			var color = colores[zona.riesgo] || '#767C87';

			L.circle([zona.lat, zona.lon], {
				radius: (zona.radio_km || 1) * 1000,
				color: color,
				fillColor: color,
				fillOpacity: 0.12,
				weight: 1.5
			}).addTo(map).bindPopup(zona.nombre + ' — ' + zona.distancia_km + ' km');

		});

	})();

	// =================================================================
	// CLIMA -> gráficas Chart.js (lluvia, viento/ráfagas)
	// =================================================================
	(function initClimateCharts() {

		var rainCanvas = document.getElementById('chart-rain');
		var windCanvas = document.getElementById('chart-wind');
		if (!rainCanvas || !windCanvas) { return; }

		var rainEmpty = document.getElementById('chart-rain-empty');
		var windEmpty = document.getElementById('chart-wind-empty');

		if (!window.Chart) {
			console.error('RADAR: Chart.js no se cargó. Las gráficas no se mostrarán.');
			rainCanvas.style.display = 'none';
			windCanvas.style.display = 'none';
			if (rainEmpty) { rainEmpty.textContent = 'No se pudo cargar la librería de gráficas (Chart.js).'; rainEmpty.style.display = 'block'; }
			if (windEmpty) { windEmpty.textContent = 'No se pudo cargar la librería de gráficas (Chart.js).'; windEmpty.style.display = 'block'; }
			return;
		}

		var clima = window.__radarClima || { hourly: { labels: [], lluvia_pct: [], viento_kmh: [], rafagas_kmh: [] } };
		var hourly = clima.hourly || {};
		var labels = hourly.labels || [];

		if (!labels.length) {
			rainCanvas.style.display = 'none';
			windCanvas.style.display = 'none';
			if (rainEmpty) rainEmpty.style.display = 'block';
			if (windEmpty) windEmpty.style.display = 'block';
			return;
		}

		new Chart(rainCanvas.getContext('2d'), {
			type: 'bar',
			data: {
				labels: labels,
				datasets: [{
					label: 'Lluvia %',
					data: hourly.lluvia_pct || [],
					backgroundColor: '#8BB4FF',
					borderRadius: 4,
					maxBarThickness: 22
				}]
			},
			options: {
				responsive: true, maintainAspectRatio: false,
				plugins: { legend: { display: false } },
				scales: {
					y: { beginAtZero: true, max: 100, ticks: { callback: function(v) { return v + '%'; } }, grid: { color: '#F1F2F4' } },
					x: { grid: { display: false } }
				}
			}
		});

		new Chart(windCanvas.getContext('2d'), {
			type: 'line',
			data: {
				labels: labels,
				datasets: [
					{ label: 'Ráfagas km/h', data: hourly.rafagas_kmh || [], borderColor: '#FF5A29', backgroundColor: 'rgba(255,90,41,0.08)', tension: 0.35, fill: true, pointRadius: 0 },
					{ label: 'Viento km/h', data: hourly.viento_kmh || [], borderColor: '#14171A', backgroundColor: 'transparent', tension: 0.35, borderDash: [4, 3], pointRadius: 0 }
				]
			},
			options: {
				responsive: true, maintainAspectRatio: false,
				plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
				scales: {
					y: { beginAtZero: true, grid: { color: '#F1F2F4' } },
					x: { grid: { display: false } }
				}
			}
		});

	})();

	// =================================================================
	// GALERÍA -> mini-mapa con publicaciones recientes
	// =================================================================
	(function initGaleriaMiniMap() {

		var mapEl = document.getElementById('gal-map');
		if (!mapEl) { return; }

		var geo = window.__radarGeo || { lat: 20.6736, lon: -103.4059 };

		function ready() { return !!(window.google && window.google.maps); }

		function boot() {

			if (!ready()) { setTimeout(boot, 300); return; }

			var map = new google.maps.Map(mapEl, { center: { lat: geo.lat, lng: geo.lon }, zoom: 10 });

			var fd = new FormData();
			fd.append('orden', 'recientes');
			fd.append('limite', 12);

			fetch('<?php echo URL ?>/ajax/galeria_listar.php', { method: 'POST', body: fd, credentials: 'same-origin' })
				.then(function(r) { return r.json(); })
				.then(function(data) {

					if (!data || !data.ok || !data.publicaciones) { return; }

					data.publicaciones.forEach(function(p) {

						if (!p.lat || !p.lng) { return; }

						var marker = new google.maps.Marker({
							position: { lat: parseFloat(p.lat), lng: parseFloat(p.lng) },
							map: map,
							title: p.titulo || ''
						});

						var info = new google.maps.InfoWindow({
							content: '<strong>' + (p.titulo || 'Publicación') + '</strong>' + (p.sitio_nombre ? '<br>' + p.sitio_nombre : '')
						});

						marker.addListener('click', function() { info.open(map, marker); });

					});

				})
				.catch(function() { /* silencioso: el mapa queda vacío sin bloquear el resto del dashboard */ });

		}

		boot();

	})();

</script>
</body>
</html>
