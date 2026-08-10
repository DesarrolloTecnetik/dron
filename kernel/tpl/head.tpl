<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo isset($pageTitle) ? $pageTitle : TITLE; ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

	<?php
		$cssBase = '';
		if (!empty($_SERVER['SCRIPT_NAME'])) {
			$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
			$scriptDir = str_replace('\\', '/', $scriptDir);
			$cssBase = rtrim($scriptDir, '/');
		}
		if ($cssBase === '/' || $cssBase === '\\') {
			$cssBase = '';
		}
		$cssHref = $cssBase . '/assets/css/radar.css';
	?>
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
	<link rel="stylesheet" href="<?php echo htmlspecialchars($cssHref); ?>">

	<script src="<?php echo htmlspecialchars($cssBase); ?>/assets/js/chart.umd.min.js"></script>
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

	<?php if(!empty($metaSEO)) { echo $metaSEO; } ?>
</head>
<body>

	<div class="response"></div>
	<div class="sbalerts"></div>