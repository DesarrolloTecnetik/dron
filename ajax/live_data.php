<?php
    require_once '../init.conf';

    header('Content-Type: application/json; charset=utf-8');

    $lat = isset($_GET['lat']) ? (float) $_GET['lat'] : (isset($_POST['lat']) ? (float) $_POST['lat'] : DRONE_BASE_LAT);
    $lon = isset($_GET['lon']) ? (float) $_GET['lon'] : (isset($_POST['lon']) ? (float) $_POST['lon'] : DRONE_BASE_LON);

    $api = new ExternalApiService();
    $weather = $api->weatherAt($lat, $lon);
    $geofences = $api->geofencesAt($lat, $lon);
    $location = $api->reverseGeocodeAt($lat, $lon);

    echo json_encode(array(
        'lat' => (float) $lat,
        'lon' => (float) $lon,
        'weather' => $weather,
        'geofences' => $geofences,
        'location' => $location,
        'updated_at' => date('c')
    ));
