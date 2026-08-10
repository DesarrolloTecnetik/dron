<?php
/**
 * External API gateway for dashboard telemetry
 *
 * Provides read-only integration adapters for weather, wind, gusts,
 * ambient temperature and geofence/risk data around a telemetry point.
 */
class ExternalApiService {

    public function weatherAt($latitude, $longitude, $currentOnly = false) {
        $base = 'https://api.open-meteo.com/v1/forecast';
        $params = array(
            'latitude' => (float) $latitude,
            'longitude' => (float) $longitude,
            'current' => 'temperature_2m,wind_speed_10m,wind_gusts_10m,wind_direction_10m,relative_humidity_2m,apparent_temperature,precipitation,weather_code',
            'hourly' => 'temperature_2m,wind_speed_10m,wind_gusts_10m',
            'daily' => 'temperature_2m_max,temperature_2m_min',
            'temperature_unit' => 'celsius',
            'wind_speed_unit' => 'kmh',
            'timezone' => 'auto'
        );

        $url = $base . '?' . http_build_query($params);
        $data = $this->httpGetJson($url);

        if (!$data || empty($data['current'])) {
            return $this->weatherFallback($latitude, $longitude);
        }

        $current = $data['current'];
        $daily = isset($data['daily']) ? $data['daily'] : array();

        $temperature = isset($current['temperature_2m']) ? (float) $current['temperature_2m'] : null;
        $windSpeed = isset($current['wind_speed_10m']) ? (float) $current['wind_speed_10m'] : null;
        $windGusts = isset($current['wind_gusts_10m']) ? (float) $current['wind_gusts_10m'] : null;
        $windDirection = isset($current['wind_direction_10m']) ? (int) $current['wind_direction_10m'] : null;
        $humidity = isset($current['relative_humidity_2m']) ? (int) $current['relative_humidity_2m'] : null;
        $visibility = isset($current['visibility']) ? (float) $current['visibility'] : null;
        $precipitation = isset($current['precipitation']) ? (float) $current['precipitation'] : null;
        $weatherCode = isset($current['weather_code']) ? (int) $current['weather_code'] : null;

        return array(
            'source' => 'Open-Meteo',
            'updated_at' => isset($current['time']) ? $current['time'] : date('c'),
            'temperature_c' => $temperature,
            'wind_kmh' => $windSpeed,
            'gusts_kmh' => $windGusts,
            'wind_direction_deg' => $windDirection,
            'humidity_pct' => $humidity,
            'visibility_km' => $visibility,
            'precipitation_mm' => $precipitation,
            'weather_code' => $weatherCode,
            'condition' => $this->weatherCodeLabel($weatherCode),
            'daily' => array(
                'max_c' => isset($daily['temperature_2m_max'][0]) ? (float) $daily['temperature_2m_max'][0] : null,
                'min_c' => isset($daily['temperature_2m_min'][0]) ? (float) $daily['temperature_2m_min'][0] : null,
            ),
            'lat' => (float) $latitude,
            'lon' => (float) $longitude,
        );
    }

    public function reverseGeocodeAt($latitude, $longitude) {
        $url = 'https://nominatim.openstreetmap.org/reverse?format=json&lat=' . urlencode((float)$latitude) . '&lon=' . urlencode((float)$longitude) . '&zoom=18&addressdetails=1&extratags=1';
        $data = $this->httpGetJson($url, true);

        if (!$data || empty($data['address'])) {
            return array(
                'zone' => null,
                'municipality' => null,
                'state' => null,
                'country' => null,
                'formatted' => null,
                'lat' => (float) $latitude,
                'lon' => (float) $longitude,
            );
        }

        $address = $data['address'];
        $zone = $address['suburb'] ?? $address['neighbourhood'] ?? $address['hamlet'] ?? $address['city_district'] ?? $address['quarter'] ?? null;
        $municipality = $address['municipality'] ?? $address['city'] ?? $address['town'] ?? $address['village'] ?? $address['county'] ?? null;
        $state = $address['state'] ?? $address['province'] ?? $address['state_district'] ?? null;
        $country = $address['country'] ?? null;

        return array(
            'zone' => $zone,
            'municipality' => $municipality,
            'state' => $state,
            'country' => $country,
            'formatted' => !empty($data['display_name']) ? $data['display_name'] : null,
            'lat' => (float) $latitude,
            'lon' => (float) $longitude,
        );
    }

    public function geofencesAt($latitude, $longitude) {
        $dbZones = $this->geofencesFromDatabase($latitude, $longitude);
        if (!empty($dbZones['near_geofences'])) {
            return $dbZones;
        }

        $bbox = array(
            'south' => round($latitude - 0.03, 6),
            'west' => round($longitude - 0.03, 6),
            'north' => round($latitude + 0.03, 6),
            'east' => round($longitude + 0.03, 6)
        );

        $query = "[out:json][timeout:25];\n";
        $query .= "(\n";
        $query .= "  node[\"aeroway\"=\"aerodrome\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  way[\"aeroway\"=\"aerodrome\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  relation[\"aeroway\"=\"aerodrome\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  node[\"military\"=\"danger_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  way[\"military\"=\"danger_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  relation[\"military\"=\"danger_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  node[\"boundary\"=\"protected_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  way[\"boundary\"=\"protected_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= "  relation[\"boundary\"=\"protected_area\"]({$bbox['south']},{$bbox['west']},{$bbox['north']},{$bbox['east']});\n";
        $query .= ");\n";
        $query .= "out geom;";

        $overpassUrl = 'https://overpass-api.de/api/interpreter?data=' . urlencode($query);
        $data = $this->httpGetJson($overpassUrl);

        if (empty($data['elements'])) {
            return array(
                'source' => 'overpass-api.de',
                'status' => 'clean',
                'near_geofences' => array(),
                'risk_level' => 'none',
                'lat' => (float) $latitude,
                'lon' => (float) $longitude,
            );
        }

        $zones = array();
        foreach ($data['elements'] as $element) {
            $tags = isset($element['tags']) ? $element['tags'] : array();
            $zoneType = null;
            if (!empty($tags['aeroway'])) {
                $zoneType = 'aerodrome';
            } elseif (!empty($tags['military'])) {
                $zoneType = 'military';
            } elseif (!empty($tags['boundary'])) {
                $zoneType = 'protected_area';
            }

            if ($zoneType) {
                $zones[] = array(
                    'name' => !empty($tags['name']) ? $tags['name'] : strtoupper($zoneType),
                    'type' => $zoneType,
                    'risk_level' => ($zoneType === 'military' || $zoneType === 'protected_area') ? 'restricted' : 'advisory',
                    'source' => 'overpass-api.de',
                    'lat' => isset($element['lat']) ? (float) $element['lat'] : null,
                    'lon' => isset($element['lon']) ? (float) $element['lon'] : null,
                );
            }
        }

        $risk = 'none';
        if (!empty($zones)) {
            foreach ($zones as $zone) {
                if ($zone['risk_level'] === 'restricted') {
                    $risk = 'restricted';
                    break;
                } elseif ($risk !== 'restricted') {
                    $risk = 'advisory';
                }
            }
        }

        return array(
            'source' => 'overpass-api.de',
            'status' => !empty($zones) ? 'alerts' : 'clean',
            'near_geofences' => $zones,
            'risk_level' => $risk,
            'lat' => (float) $latitude,
            'lon' => (float) $longitude,
        );
    }

    private function geofencesFromDatabase($latitude, $longitude) {
        if (!isset($GLOBALS['db']) || !method_exists($GLOBALS['db'], 'query')) {
            return array(
                'source' => 'database',
                'status' => 'clean',
                'near_geofences' => array(),
                'risk_level' => 'none',
                'lat' => (float) $latitude,
                'lon' => (float) $longitude,
            );
        }

        $db = $GLOBALS['db'];
        try {
            $db->query("SELECT id, nombre, tipo, lat, lon, radio_m, fuente, activo FROM geocercas WHERE activo = 1");
            $db->execute();
            $rows = $db->resultSet();
            $db->CloseConnection();
        } catch(Exception $e) {
            return array(
                'source' => 'database',
                'status' => 'clean',
                'near_geofences' => array(),
                'risk_level' => 'none',
                'lat' => (float) $latitude,
                'lon' => (float) $longitude,
            );
        }

        $near = array();
        foreach ($rows as $row) {
            $zoneLat = (float) $row['lat'];
            $zoneLon = (float) $row['lon'];
            $radius = !empty($row['radio_m']) ? (int) $row['radio_m'] : 1000;
            $distance = $this->distanceMeters($latitude, $longitude, $zoneLat, $zoneLon);

            if ($distance <= $radius) {
                $near[] = array(
                    'id' => (int) $row['id'],
                    'name' => $row['nombre'],
                    'type' => $row['tipo'],
                    'risk_level' => $this->riskFromType($row['tipo']),
                    'source' => 'database',
                    'lat' => $zoneLat,
                    'lon' => $zoneLon,
                    'radio_m' => $radius,
                    'distance_m' => (int) $distance,
                );
            }
        }

        $risk = 'none';
        if (!empty($near)) {
            foreach ($near as $zone) {
                if ($zone['risk_level'] === 'restricted') {
                    $risk = 'restricted';
                    break;
                } elseif ($risk !== 'restricted') {
                    $risk = 'advisory';
                }
            }
        }

        return array(
            'source' => 'database-geocercas',
            'status' => !empty($near) ? 'alerts' : 'clean',
            'near_geofences' => $near,
            'risk_level' => $risk,
            'lat' => (float) $latitude,
            'lon' => (float) $longitude,
        );
    }

    private function riskFromType($type) {
        $restricted = array('zona_restringida', 'zona_militar', 'area_protegida');
        if (in_array($type, $restricted, true)) {
            return 'restricted';
        }
        return 'advisory';
    }

    private function distanceMeters($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return (float) ($earthRadius * $c);
    }

    private function httpGetJson($url, $reverseGeocode = false) {
        if (!function_exists('curl_init')) {
            $payload = @file_get_contents($url);
            if ($payload === false) {
                return null;
            }
            return json_decode($payload, true);
        }

        $ch = curl_init();
        $headers = array('Accept: application/json');
        if ($reverseGeocode) {
            $headers[] = 'User-Agent: DronApp/1.0';
        }

        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HTTPHEADER => $headers
        ));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode < 200 || $httpCode >= 300 || $response === false) {
            return null;
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $decoded;
    }

    private function weatherFallback($latitude, $longitude) {
        return array(
            'source' => 'fallback',
            'updated_at' => date('c'),
            'temperature_c' => null,
            'wind_kmh' => null,
            'gusts_kmh' => null,
            'wind_direction_deg' => null,
            'humidity_pct' => null,
            'visibility_km' => null,
            'precipitation_mm' => null,
            'weather_code' => null,
            'condition' => 'Sin datos',
            'daily' => array(
                'max_c' => null,
                'min_c' => null,
            ),
            'lat' => (float) $latitude,
            'lon' => (float) $longitude,
        );
    }

    private function weatherCodeLabel($code) {
        $labels = array(
            0 => 'Despejado',
            1 => 'Mayormente despejado',
            2 => 'Parcialmente nublado',
            3 => 'Nublado',
            45 => 'Neblina',
            48 => 'Escarcha',
            51 => 'Llovizna ligera',
            53 => 'Llovizna moderada',
            55 => 'Llovizna intensa',
            56 => 'Llovizna en frío',
            57 => 'Llovizna intensa',
            61 => 'Lluvia ligera',
            63 => 'Lluvia moderada',
            65 => 'Lluvia fuerte',
            66 => 'Lluvia helada',
            67 => 'Lluvia helada intensa',
            71 => 'Nieve ligera',
            73 => 'Nieve moderada',
            75 => 'Nieve fuerte',
            77 => 'Granizo',
            80 => 'Chubascos ligeros',
            81 => 'Chubascos moderados',
            82 => 'Chubascos fuertes',
            85 => 'Chubascos de nieve',
            86 => 'Chubascos de nieve fuertes',
            95 => 'Tormenta',
            96 => 'Tormenta con granizo',
            99 => 'Tormenta violenta'
        );

        return isset($labels[$code]) ? $labels[$code] : 'Sin datos';
    }
}
