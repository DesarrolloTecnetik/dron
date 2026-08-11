<?php

	/**
	 *
	 *  ExternalApiService
	 *  Concentra las llamadas a servicios externos (todos gratuitos, sin API key)
	 *  que alimentan el panel de "Estado de vuelo": clima/viento, geocercas y ubicación.
	 *
	 *  Uso:
	 *     $api = new ExternalApiService();
	 *     $api->weatherAt($lat, $lon);
	 *     $api->geofencesAt($lat, $lon);
	 *     $api->reverseGeocodeAt($lat, $lon);
	 *
	 **/

	class ExternalApiService {

		// timeout corto -> si el servicio externo tarda, no debe tumbar la página
		private $timeout = 6;

		// -----------------------------------------------------------
		// helper interno para peticiones GET con manejo de errores
		// -----------------------------------------------------------
		private function curlGet($url, $headers = array()) {

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
			if( !empty($headers) ) { curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); }

			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = curl_errno($ch);
			curl_close($ch);

			if( $error || $response === false || $httpCode >= 400 ) { return null; }

			$data = json_decode($response, true);
			return $data;

		}

		// -----------------------------------------------------------
		// Clima y viento -> Open-Meteo (gratis, sin API key, incluye viento en superficie)
		// https://open-meteo.com/en/docs
		// -----------------------------------------------------------
		public function weatherAt($lat, $lon) {

			$url = "https://api.open-meteo.com/v1/forecast"
				 . "?latitude=".$lat."&longitude=".$lon
				 . "&current=temperature_2m,wind_speed_10m,wind_direction_10m,wind_gusts_10m,visibility,weather_code"
				 . "&hourly=wind_speed_10m,wind_gusts_10m,precipitation_probability"
				 . "&daily=sunrise,sunset,precipitation_probability_max"
				 . "&timezone=auto&forecast_days=2";

			$data = $this->curlGet($url);

			if( empty($data['current']) ) {

				return array(
					'ok' => false,
					'error' => 'sin_datos',
					'nota' => 'No se pudo consultar Open-Meteo en este momento.'
				);

			}

			$c = $data['current'];

			return array(
				'ok' => true,
				'viento_kmh' => isset($c['wind_speed_10m']) ? round($c['wind_speed_10m']) : null,
				'rafagas_kmh' => isset($c['wind_gusts_10m']) ? round($c['wind_gusts_10m']) : null,
				'direccion_grados' => isset($c['wind_direction_10m']) ? $c['wind_direction_10m'] : null,
				'temperatura_c' => isset($c['temperature_2m']) ? $c['temperature_2m'] : null,
				'visibilidad_m' => isset($c['visibility']) ? $c['visibility'] : null,
				'actualizado' => isset($c['time']) ? $c['time'] : null,
				'hourly' => $this->extractHourly(!empty($data['hourly']) ? $data['hourly'] : array(), isset($c['time']) ? $c['time'] : date('c')),
				'sun' => $this->extractSun(!empty($data['daily']) ? $data['daily'] : array(), isset($c['time']) ? $c['time'] : date('c')),
			);

		}

		// -----------------------------------------------------------
		// Recorta el bloque "hourly" de Open-Meteo a las próximas 12 horas
		// contando desde la hora actual local, para alimentar las gráficas.
		// -----------------------------------------------------------
		private function extractHourly($hourly, $nowTime) {

			if( empty($hourly['time']) || !is_array($hourly['time']) ) { return array('labels' => array(), 'lluvia_pct' => array(), 'viento_kmh' => array(), 'rafagas_kmh' => array()); }

			$times = $hourly['time'];
			$startIndex = 0;

			foreach( $times as $index => $time ) {
				if( $time >= substr($nowTime, 0, 13) ) { $startIndex = $index; break; }
			}

			$labels = array(); $lluvia = array(); $viento = array(); $rafagas = array();
			$count = count($times);

			for( $i = $startIndex; $i < min($startIndex + 12, $count); $i++ ) {

				$ts = strtotime($times[$i]);
				$labels[] = $ts ? date('G:i', $ts) : $times[$i];
				$lluvia[] = isset($hourly['precipitation_probability'][$i]) ? (int) $hourly['precipitation_probability'][$i] : 0;
				$viento[] = isset($hourly['wind_speed_10m'][$i]) ? round($hourly['wind_speed_10m'][$i]) : 0;
				$rafagas[] = isset($hourly['wind_gusts_10m'][$i]) ? round($hourly['wind_gusts_10m'][$i]) : 0;

			}

			return array('labels' => $labels, 'lluvia_pct' => $lluvia, 'viento_kmh' => $viento, 'rafagas_kmh' => $rafagas);

		}

		// -----------------------------------------------------------
		// Calcula amanecer/atardecer, crepúsculo aproximado (±25 min)
		// y el % del día ya transcurrido, en hora local.
		// -----------------------------------------------------------
		private function extractSun($daily, $nowTime) {

			$sunriseIso = isset($daily['sunrise'][0]) ? $daily['sunrise'][0] : null;
			$sunsetIso = isset($daily['sunset'][0]) ? $daily['sunset'][0] : null;

			if( !$sunriseIso || !$sunsetIso ) {
				return array('amanecer' => null, 'atardecer' => null, 'crepusculo_manana' => null, 'crepusculo_tarde' => null, 'duracion_label' => null, 'progreso_pct' => null, 'es_de_dia' => null);
			}

			$sunriseTs = strtotime($sunriseIso);
			$sunsetTs = strtotime($sunsetIso);
			$nowTs = strtotime($nowTime);
			$offset = 25 * 60;

			$daylightSeconds = max(0, $sunsetTs - $sunriseTs);
			$hours = floor($daylightSeconds / 3600);
			$minutes = floor(($daylightSeconds % 3600) / 60);

			$progreso = 0; $esDeDia = false;

			if( $nowTs !== false ) {
				if( $nowTs <= $sunriseTs ) { $progreso = 0; $esDeDia = false; }
				elseif( $nowTs >= $sunsetTs ) { $progreso = 100; $esDeDia = false; }
				else { $progreso = round((($nowTs - $sunriseTs) / max(1, $daylightSeconds)) * 100); $esDeDia = true; }
			}

			return array(
				'amanecer' => date('G:i', $sunriseTs),
				'atardecer' => date('G:i', $sunsetTs),
				'crepusculo_manana' => date('G:i', $sunriseTs - $offset),
				'crepusculo_tarde' => date('G:i', $sunsetTs + $offset),
				'duracion_label' => $hours.'h '.$minutes.'m',
				'progreso_pct' => $progreso,
				'es_de_dia' => $esDeDia,
			);

		}

		// -----------------------------------------------------------
		// GeoCercas -> versión inicial simplificada por radio de aeropuerto.
		// PENDIENTE: sustituir/complementar con el KML oficial de zonas AFAC
		// (gob.mx/afac) para cobertura completa de zonas rojas/amarillas.
		// -----------------------------------------------------------
		public function geofencesAt($lat, $lon) {

			// aeropuertos/zonas conocidas cerca de Jalisco -> ir agregando conforme se necesite
			$conocidas = array(
				array('nombre' => 'Aeropuerto Internacional de Guadalajara (GDL)', 'tipo' => 'aeropuerto', 'lat' => 20.5218, 'lon' => -103.3111, 'radio_km' => 9),
			);

			// si existe la tabla `geocercas` en BD, se agregan también esas zonas
			$conocidas = array_merge($conocidas, $this->geocercasDesdeBD());

			$masCercano = null;
			$distanciaMin = null;
			$cercanas = array();

			foreach( $conocidas as $z ) {

				$d = $this->haversine($lat, $lon, $z['lat'], $z['lon']);
				$radio = !empty($z['radio_km']) ? $z['radio_km'] : 5;

				if( $distanciaMin === null || $d < $distanciaMin ) {
					$distanciaMin = $d;
					$masCercano = $z;
				}

				// solo se listan las zonas relevantes para el mapa (radio ampliado x4 para dar contexto visual)
				if( $d <= ($radio * 4) ) {

					$cercanas[] = array(
						'nombre' => $z['nombre'],
						'tipo' => $z['tipo'],
						'lat' => $z['lat'],
						'lon' => $z['lon'],
						'radio_km' => $radio,
						'distancia_km' => round($d, 1),
						'riesgo' => ($d <= $radio) ? 'restringida' : (($d <= $radio * 2) ? 'precaucion' : 'informativa'),
					);

				}

			}

			usort($cercanas, function($a, $b) { return $a['distancia_km'] <=> $b['distancia_km']; });

			$restringido = ($distanciaMin !== null && $masCercano && $distanciaMin <= $masCercano['radio_km']);

			return array(
				'ok' => true,
				'zona' => $restringido ? 'restringida' : 'verde',
				'aeropuerto_cercano' => $masCercano ? $masCercano['nombre'] : null,
				'distancia_km' => $distanciaMin !== null ? round($distanciaMin, 1) : null,
				'nota' => 'Cálculo aproximado por radio de zonas conocidas. Falta integrar el KML oficial de AFAC para cobertura completa.',
				'near' => $cercanas,
			);

		}

		// -----------------------------------------------------------
		// Lee zonas adicionales desde la tabla `geocercas` si existe.
		// Falla en silencio (tabla ausente, sin BD, etc.) -> lista vacía.
		// -----------------------------------------------------------
		private function geocercasDesdeBD() {

			if( !isset($GLOBALS['db']) || !method_exists($GLOBALS['db'], 'query') ) { return array(); }

			try {

				$db = $GLOBALS['db'];
				$db->query("SELECT nombre, tipo, lat, lon, radio_m FROM geocercas WHERE activo = 1");
				$db->execute();
				$rows = $db->resultSet();

			} catch( Exception $e ) { return array(); }

			$out = array();
			foreach( (array) $rows as $r ) {

				$out[] = array(
					'nombre' => $r['nombre'],
					'tipo' => !empty($r['tipo']) ? $r['tipo'] : 'zona',
					'lat' => (float) $r['lat'],
					'lon' => (float) $r['lon'],
					'radio_km' => !empty($r['radio_m']) ? round($r['radio_m'] / 1000, 2) : 1,
				);

			}

			return $out;

		}

		// -----------------------------------------------------------
		// Reverse geocoding -> Nominatim (OpenStreetMap, gratis, requiere User-Agent identificable)
		// https://nominatim.org/release-docs/latest/api/Reverse/
		// -----------------------------------------------------------
		public function reverseGeocodeAt($lat, $lon) {

			$url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=".$lat."&lon=".$lon."&zoom=14";
			$data = $this->curlGet($url, array('User-Agent: RADAR-Droneros/1.0 (contacto: desarrollo@tecnetik.com)'));

			if( empty($data['display_name']) ) {

				return array('ok' => false, 'nombre' => null);

			}

			$addr = !empty($data['address']) ? $data['address'] : array();
			$ciudad = !empty($addr['city']) ? $addr['city'] : (!empty($addr['town']) ? $addr['town'] : (!empty($addr['municipality']) ? $addr['municipality'] : null));

			return array(
				'ok' => true,
				'nombre' => $data['display_name'],
				'ciudad' => $ciudad,
			);

		}

		// -----------------------------------------------------------
		// distancia entre 2 coordenadas en km (fórmula haversine)
		// -----------------------------------------------------------
		private function haversine($lat1, $lon1, $lat2, $lon2) {

			$R = 6371; // radio de la Tierra en km
			$dLat = deg2rad($lat2 - $lat1);
			$dLon = deg2rad($lon2 - $lon1);
			$a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			return $R * $c;

		}

	}

?>