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
				 . "&timezone=auto";

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
			);

		}

		// -----------------------------------------------------------
		// GeoCercas -> versión inicial simplificada por radio de aeropuerto.
		// PENDIENTE: sustituir/complementar con el KML oficial de zonas AFAC
		// (gob.mx/afac) para cobertura completa de zonas rojas/amarillas.
		// -----------------------------------------------------------
		public function geofencesAt($lat, $lon) {

			// aeropuertos conocidos cerca de Jalisco -> ir agregando conforme se necesite
			$aeropuertos = array(
				array('nombre' => 'Aeropuerto Internacional de Guadalajara (GDL)', 'lat' => 20.5218, 'lon' => -103.3111, 'radio_km' => 9),
			);

			$masCercano = null;
			$distanciaMin = null;

			foreach( $aeropuertos as $a ) {

				$d = $this->haversine($lat, $lon, $a['lat'], $a['lon']);
				if( $distanciaMin === null || $d < $distanciaMin ) {

					$distanciaMin = $d;
					$masCercano = $a;

				}

			}

			$restringido = ($distanciaMin !== null && $distanciaMin <= $masCercano['radio_km']);

			return array(
				'ok' => true,
				'zona' => $restringido ? 'restringida' : 'verde',
				'aeropuerto_cercano' => $masCercano ? $masCercano['nombre'] : null,
				'distancia_km' => $distanciaMin !== null ? round($distanciaMin, 1) : null,
				'nota' => 'Cálculo aproximado por radio de aeropuerto. Falta integrar el KML oficial de AFAC para zonas urbanas, arqueológicas y áreas protegidas.'
			);

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
