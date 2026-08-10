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
	<link rel="stylesheet" href="<?php echo htmlspecialchars($cssHref); ?>">

	<?php if(!empty($metaSEO)) { echo $metaSEO; } ?>
</head>
<body>

	<div class="response"></div>
	<div class="sbalerts"></div>