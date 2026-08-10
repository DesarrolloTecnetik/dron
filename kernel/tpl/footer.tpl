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
					const estadoLocationText = document.getElementById('estado-location-text');
					if (estadoLocationText) {
						const parts = [locationInfo.zone, locationInfo.municipality, locationInfo.state].filter(Boolean);
						estadoLocationText.textContent = parts.length ? parts.join(', ') : ('Lat ' + Number(lat).toFixed(4) + ', Lon ' + Number(lon).toFixed(4));
					}

					updateClimateCharts(weather);
					updateSunPanel(weather);
					updateRiskBadges(geofences);
					updateGeoMap(geofences, lat, lon);
				})
				.catch(error => console.log('Live telemetry error:', error));
		}

		function riskPresentation(riskLevel) {
			if (riskLevel === 'restricted') {
				return { badgeClass: 'red', badgeLabel: 'No volar', ringClass: 'red', flightLabel: 'No volar aquí', color: '#D6483C' };
			}
			if (riskLevel === 'advisory') {
				return { badgeClass: 'amber', badgeLabel: 'Precaución', ringClass: 'amber', flightLabel: 'Volar con precaución', color: '#C98A12' };
			}
			return { badgeClass: 'green', badgeLabel: 'Zona verde', ringClass: '', flightLabel: 'Puedes volar', color: '#1FA463' };
		}

		function updateRiskBadges(geofences) {
			const riskLevel = (geofences && geofences.risk_level) ? geofences.risk_level : 'none';
			const geoStatusText = (riskLevel === 'none') ? 'Sin restricciones' : riskLevel.toUpperCase();
			const p = riskPresentation(riskLevel);

			const applyBadge = (id) => {
				const el = document.getElementById(id);
				if (!el) { return; }
				el.classList.remove('green', 'amber', 'red', 'neutral');
				el.classList.add(p.badgeClass);
				el.textContent = p.badgeLabel;
			};
			applyBadge('estado-status-badge');
			applyBadge('mapa-status-badge');

			const ring = document.getElementById('estado-light-ring');
			if (ring) {
				ring.classList.remove('amber', 'red');
				if (p.ringClass) { ring.classList.add(p.ringClass); }
			}
			const label = document.getElementById('estado-light-label');
			if (label) { label.textContent = p.flightLabel; }
			const sub = document.getElementById('estado-light-sub');
			if (sub) { sub.textContent = geoStatusText; }

			const summary = document.getElementById('geo-summary');
			if (summary) {
				const zones = (geofences && geofences.near_geofences) ? geofences.near_geofences : [];
				summary.textContent = zones.length
					? ('Zonas cercanas detectadas: ' + zones.length + ' · Nivel: ' + riskLevel)
					: 'Sin zonas restringidas ni áreas protegidas detectadas en un radio cercano.';
			}
		}

		const GEO_TYPE_LABELS = {
			aerodrome: 'Aeródromo',
			aeropuerto: 'Aeropuerto',
			airport: 'Aeropuerto',
			military: 'Zona militar',
			military_area: 'Zona militar',
			danger_area: 'Zona de riesgo / no volar',
			protected_area: 'Área protegida',
			no_fly: 'No volar',
			no_volar: 'No volar',
			zona_no_volar: 'No volar',
			restricted_zone: 'Zona restringida'
		};
		const GEO_DEFAULT_RADIUS = { aerodrome: 1500, aeropuerto: 1500, airport: 1500, military: 2000, military_area: 2000, danger_area: 2000, protected_area: 1000, no_fly: 1200, no_volar: 1200, zona_no_volar: 1200, restricted_zone: 1200 };

		function geoZoneRadius(zone) {
			if (zone.radio_m) { return zone.radio_m; }
			return GEO_DEFAULT_RADIUS[zone.type] || 800;
		}

		function geoZoneDistance(zone, centerLat, centerLon) {
			if (typeof zone.distance_m === 'number') { return zone.distance_m; }
			if (zone.lat === undefined || zone.lon === undefined || zone.lat === null || zone.lon === null) { return null; }
			const earthRadius = 6371000;
			const dLat = (zone.lat - centerLat) * Math.PI / 180;
			const dLon = (zone.lon - centerLon) * Math.PI / 180;
			const a = Math.sin(dLat / 2) ** 2 + Math.cos(centerLat * Math.PI / 180) * Math.cos(zone.lat * Math.PI / 180) * Math.sin(dLon / 2) ** 2;
			const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
			return Math.round(earthRadius * c);
		}

		function updateGeoZoneList(zones) {
			const list = document.getElementById('geo-zone-list');
			if (!list) { return; }

			if (!zones.length) {
				list.innerHTML = '<p class="geo-zone-empty" id="geo-zone-empty">No hay geocercas activas cerca de tu ubicación.</p>';
				return;
			}

			list.innerHTML = zones.map(zone => {
				const dotClass = (zone.risk_level === 'restricted') ? 'red' : 'amber';
				const typeLabel = GEO_TYPE_LABELS[zone.type] || 'Zona restringida';
				const distance = geoZoneDistance(zone, zone._centerLat, zone._centerLon);
				const distanceText = (distance !== null) ? (' · a ' + (distance / 1000).toFixed(1) + ' km') : '';
				const safeName = (zone.name || 'Zona sin nombre').replace(/</g, '&lt;');
				return '<div class="geo-zone-row"><span class="geo-dot ' + dotClass + '"></span>' +
					'<div class="geo-zone-info"><div class="geo-zone-name">' + safeName + '</div>' +
					'<div class="geo-zone-meta">' + typeLabel + distanceText + '</div></div></div>';
			}).join('');
		}

		function updateGeoMap(geofences, lat, lon) {
			updateRiskBadges(geofences); // idempotent: keeps badges in sync even if called standalone

			const zonesRaw = (geofences && geofences.near_geofences) ? geofences.near_geofences : [];
			const zones = zonesRaw.map(z => Object.assign({}, z, { _centerLat: lat, _centerLon: lon }));
			updateGeoZoneList(zones);

			if (!window.__radarMap) { return; }
			const { map, zoneColor } = window.__radarMap;

			map.setView([lat, lon], map.getZoom());

			if (window.__radarMap.userMarker) {
				window.__radarMap.userMarker.setLatLng([lat, lon]);
			}

			(window.__radarMap.zoneLayers || []).forEach(layer => map.removeLayer(layer));
			window.__radarMap.zoneLayers = [];

			zones.forEach(zone => {
				if (zone.lat === undefined || zone.lon === undefined || zone.lat === null || zone.lon === null) { return; }
				const type = (zone.type || '').toLowerCase();
				const color = zoneColor(zone.risk_level, type);
				const circle = L.circle([zone.lat, zone.lon], {
					radius: geoZoneRadius(zone),
					color: color,
					weight: 1.5,
					fillColor: color,
					fillOpacity: 0.18
				}).addTo(map).bindPopup('<strong>' + (zone.name || 'Zona') + '</strong><br>' + (GEO_TYPE_LABELS[type] || GEO_TYPE_LABELS[zone.type] || 'Zona restringida'));
				window.__radarMap.zoneLayers.push(circle);

				if (type === 'aerodrome' || type === 'airport' || type === 'aeropuerto') {
					const airportMarker = L.circleMarker([zone.lat, zone.lon], {
						radius: 6,
						color: '#1D75DD',
						weight: 2,
						fillColor: '#88DBFF',
						fillOpacity: 0.9
					}).addTo(map).bindPopup('<strong>' + (zone.name || 'Aeródromo') + '</strong><br>Aeródromo / Aeropuerto');
					window.__radarMap.zoneLayers.push(airportMarker);
				}
			});

			if (!zones.length) {
				const clearCircle = L.circle([lat, lon], {
					radius: 1200,
					color: '#1FA463',
					weight: 1.5,
					fillColor: '#1FA463',
					fillOpacity: 0.10
				}).addTo(map);
				window.__radarMap.zoneLayers.push(clearCircle);
			}
		}

		function updateClimateCharts(weather) {
			const forecast = (weather && weather.hourly_forecast) ? weather.hourly_forecast : [];
			const rainMax = document.getElementById('rain-max-today');
			const windMax = document.getElementById('wind-max-today');

			if (rainMax) {
				const pct = (weather && weather.daily && weather.daily.rain_chance_pct !== null && weather.daily.rain_chance_pct !== undefined)
					? weather.daily.rain_chance_pct + '%' : '--';
				rainMax.innerHTML = pct + ' <small>hoy</small>';
			}
			if (windMax) {
				const gusts = (weather && weather.gusts_kmh !== null && weather.gusts_kmh !== undefined) ? Math.round(weather.gusts_kmh) : '--';
				windMax.innerHTML = gusts + ' <small>km/h ráfaga actual</small>';
			}

			if (!forecast.length || !window.__radarCharts) {
				toggleEmptyState([], document.getElementById('chart-rain'), document.getElementById('chart-rain-empty'));
				toggleEmptyState([], document.getElementById('chart-wind'), document.getElementById('chart-wind-empty'));
				return;
			}

			const labels = forecast.map(h => h.hour_label);
			const rain = forecast.map(h => h.rain_chance_pct);
			const wind = forecast.map(h => h.wind_kmh);
			const gusts = forecast.map(h => h.gusts_kmh);

			if (window.__radarCharts.rain) {
				window.__radarCharts.rain.data.labels = labels;
				window.__radarCharts.rain.data.datasets[0].data = rain;
				window.__radarCharts.rain.update();
			}
			if (window.__radarCharts.wind) {
				window.__radarCharts.wind.data.labels = labels;
				window.__radarCharts.wind.data.datasets[0].data = gusts;
				window.__radarCharts.wind.data.datasets[1].data = wind;
				window.__radarCharts.wind.update();
			}

			toggleEmptyState(labels, document.getElementById('chart-rain'), document.getElementById('chart-rain-empty'));
			toggleEmptyState(labels, document.getElementById('chart-wind'), document.getElementById('chart-wind-empty'));
		}

		function updateSunPanel(weather) {
			const sun = (weather && weather.sun) ? weather.sun : null;
			if (!sun) { return; }

			const setText = (id, value) => {
				const el = document.getElementById(id);
				if (el) { el.textContent = value || '--:--'; }
			};
			setText('sun-dawn', sun.dawn);
			setText('sun-sunrise', sun.sunrise);
			setText('sun-sunset', sun.sunset);
			setText('sun-dusk', sun.dusk);

			const badge = document.getElementById('sun-daylight-badge');
			if (badge) {
				badge.textContent = sun.daylight_label ? (sun.daylight_label + ' de luz') : 'Sin datos';
			}

			const progress = (sun.day_progress_pct !== null && sun.day_progress_pct !== undefined) ? sun.day_progress_pct : 0;
			const fill = document.getElementById('sun-bar-fill');
			const marker = document.getElementById('sun-marker');
			if (fill) { fill.style.width = progress + '%'; }
			if (marker) { marker.style.left = progress + '%'; }
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

		// ===== alerta() / button() =====
		// Funciones globales que usan las respuestas PHP (updateJS) de
		// ajax/account_start.php, ajax/account_signup.php, ajax/account_logout.php, etc.
		// para mostrar mensajes al usuario y reactivar el botón del formulario.
		window.__activeAuthButton = null;

		function alerta(message, type, time) {
			type = type || 'info';
			let stack = document.querySelector('.sbalerts');
			if (!stack) {
				stack = document.createElement('div');
				stack.className = 'sbalerts';
				document.body.appendChild(stack);
			}
			const toast = document.createElement('div');
			toast.className = 'radar-toast ' + type;
			toast.textContent = message;
			stack.appendChild(toast);
			requestAnimationFrame(() => toast.classList.add('is-visible'));
			const life = (typeof time === 'number' && time > 0) ? time : 4500;
			setTimeout(() => {
				toast.classList.remove('is-visible');
				setTimeout(() => toast.remove(), 300);
			}, life);
		}

		function button(enable) {
			if (window.__activeAuthButton) {
				window.__activeAuthButton.disabled = !enable;
			}
		}

		// ===== Modales de acceso (login / registro) =====
		(function setupAuthModals() {
			const modals = {
				login: document.getElementById('login-modal'),
				register: document.getElementById('register-modal')
			};

			const openModal = (name) => {
				Object.values(modals).forEach(m => { if (m) { m.classList.remove('is-open'); } });
				if (modals[name]) { modals[name].classList.add('is-open'); }
			};
			const closeModal = (name) => {
				if (modals[name]) { modals[name].classList.remove('is-open'); }
			};

			document.querySelectorAll('[data-open-login]').forEach(btn => btn.addEventListener('click', event => {
				event.preventDefault();
				openModal('login');
			}));
			document.querySelectorAll('[data-open-register]').forEach(btn => btn.addEventListener('click', event => {
				event.preventDefault();
				openModal('register');
			}));

			Object.entries(modals).forEach(([name, modal]) => {
				if (!modal) { return; }
				const closeButton = modal.querySelector('.modal-close');
				if (closeButton) { closeButton.addEventListener('click', () => closeModal(name)); }
				modal.addEventListener('click', event => {
					if (event.target === modal) { closeModal(name); }
				});
			});

			// envío genérico por fetch de un formulario dentro de un modal de auth
			function wireAuthForm(formId, buttonId, endpoint, busyLabel, idleLabel, messageId) {
				const form = document.getElementById(formId);
				if (!form) { return; }
				form.addEventListener('submit', event => {
					event.preventDefault();
					const formData = new FormData(form);
					const button = document.getElementById(buttonId);
					window.__activeAuthButton = button;
					if (button) {
						button.disabled = true;
						button.textContent = busyLabel;
					}
					fetch('<?php echo URL; ?>' + endpoint, {
						method: 'POST',
						headers: { 'X-Requested-With': 'XMLHttpRequest' },
						body: formData
					})
					.then(response => {
						if (!response.ok) { throw new Error(endpoint + ' returned ' + response.status); }
						return response.text();
					})
					.then(text => {
						const message = document.getElementById(messageId);
						if (message) { message.innerHTML = ''; }
						const scripts = text.match(/<script[^>]*>([\s\S]*?)<\/script>/gi) || [];
						for (const scriptTag of scripts) {
							const inner = scriptTag.match(/<script[^>]*>([\s\S]*?)<\/script>/i);
							if (inner && inner[1]) { eval(inner[1]); }
						}
					})
					.catch(error => {
						console.log('Auth form error:', error);
						alerta('No se pudo contactar con el servidor, intenta nuevamente.', 'danger');
					})
					.finally(() => {
						// si el botón sigue deshabilitado es porque hubo un redirect exitoso (button(true) no se llamó);
						// lo dejamos así hasta que la página cambie. Si se reactivó, restauramos su texto.
						if (button && !button.disabled) { button.textContent = idleLabel; }
					});
				});
			}

			wireAuthForm('modal-login-form', 'modal-login-button', '/ajax/account_start.php', 'Conectando...', 'Entrar', 'modal-login-message');
			wireAuthForm('modal-register-form', 'modal-register-button', '/ajax/account_signup.php', 'Creando cuenta...', 'Crear cuenta', 'modal-register-message');

			<?php if (empty($UserID)) : ?>
				openModal('login');
			<?php endif; ?>
		})();

		<?php if (!empty($_GET['logout'])) : ?>
			alerta('Sesión cerrada correctamente.', 'success');
		<?php endif; ?>

		startLivePositioning();
	</script>

</body>
</html>