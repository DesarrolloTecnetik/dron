</div><!-- /.desk -->

	<script>
		const buttons = document.querySelectorAll('.dock button');
		buttons.forEach(btn => {
			btn.addEventListener('click', () => {
				buttons.forEach(b => b.classList.remove('is-active'));
				btn.classList.add('is-active');
				const target = document.getElementById(btn.dataset.target);
				if(!target) return;
				target.scrollIntoView({behavior:'smooth', block:'start'});
				document.querySelectorAll('.win').forEach(w => w.classList.remove('is-focus'));
				target.classList.add('is-focus');
				setTimeout(() => target.classList.remove('is-focus'), 1400);
			});
		});

		function loadLiveTelemetry(lat, lon) {
			const endpoint = '<?php echo URL; ?>/ajax/live_data.php?lat=' + encodeURIComponent(lat) + '&lon=' + encodeURIComponent(lon);
			fetch(endpoint)
				.then(response => response.json())
				.then(data => {
					const weather = data.weather || {};
					const geofences = data.geofences || {};
					const locationInfo = data.location || {};
					const wind = document.querySelector('#win-estado .data-item:nth-child(1) .v');
					const gusts = document.querySelector('#win-estado .data-item:nth-child(2) .v');
					const temp = document.querySelector('#win-estado .data-item:nth-child(3) .v');
					const condition = document.querySelector('#win-estado .data-item:nth-child(4) .v');
					if (wind) {
						wind.innerHTML = (weather.wind_kmh !== null && weather.wind_kmh !== undefined ? Math.round(weather.wind_kmh) : '--') + ' <small>km/h</small>';
					}
					if (gusts) {
						gusts.innerHTML = (weather.gusts_kmh !== null && weather.gusts_kmh !== undefined ? Math.round(weather.gusts_kmh) : '--') + ' <small>km/h</small>';
					}
					if (temp) {
						temp.innerHTML = (weather.temperature_c !== null && weather.temperature_c !== undefined ? Number(weather.temperature_c).toFixed(1) : '--') + ' <small>°C</small>';
					}
					if (condition) {
						condition.textContent = weather.condition || 'Sin datos';
					}
					const locationLabel = document.querySelector('#live-location-label');
					if (locationLabel) {
						const zone = locationInfo.zone || 'zona';
						const municipality = locationInfo.municipality || 'municipio';
						const state = locationInfo.state || 'estado';
						const country = locationInfo.country || 'país';
						locationLabel.textContent = 'Ubicación: ' + zone + ', ' + municipality + ', ' + state + ', ' + country + ' · ' + Number(lat).toFixed(5) + ', ' + Number(lon).toFixed(5);
					}
					const desc = document.querySelector('#win-mapa .desc');
					if (desc) {
						if (geofences && geofences.near_geofences && geofences.near_geofences.length) {
							desc.textContent = 'Zonas cercanas detectadas: ' + geofences.near_geofences.length + ' · ' + (geofences.risk_level || 'advisory');
						} else {
							desc.textContent = 'Sin zonas restringidas ni áreas protegidas detectadas en un radio cercano.';
						}
					}
				})
				.catch(error => console.log('Live telemetry error:', error));
		}

		function startLivePositioning() {
			if (!navigator.geolocation) {
				console.log('Geolocalización no soportada por navegador.');
				loadLiveTelemetry(20.6736, -103.4059);
				return;
			}

			navigator.geolocation.getCurrentPosition(
				position => {
					const lat = position.coords.latitude;
					const lon = position.coords.longitude;
					loadLiveTelemetry(lat, lon);
				},
				() => {
					console.log('Geolocalización denegada. Usando ubicación por defecto.');
					loadLiveTelemetry(20.6736, -103.4059);
				},
				{ enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
			);
		}

		startLivePositioning();
	</script>

</body>
</html>