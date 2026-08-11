<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo isset($pageTitle) ? $pageTitle : TITLE ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="">
	<style>
		:root{
			--desk:#F3F4F6; --win:#FFFFFF; --border:#E5E7EB; --border-strong:#D6D9DE;
			--ink:#14171A; --ink-dim:#767C87; --ink-faint:#A7ACB4;
			--accent:#FF5A29; --accent-soft:#FFF0EA;
			--green:#1FA463; --green-soft:#EAF7F0;
			--amber:#C98A12; --amber-soft:#FBF2E1;
			--red:#D6483C; --red-soft:#FBEEEC;
			--grot:'Space Grotesk', sans-serif; --sans:'Inter', sans-serif; --mono:'JetBrains Mono', monospace;
		}
		*{ box-sizing:border-box; margin:0; padding:0; }
		body{
			background:
				radial-gradient(var(--border-strong) 1px, transparent 1px) 0 0/22px 22px,
				var(--desk);
			color:var(--ink); font-family:var(--sans); -webkit-font-smoothing:antialiased; min-height:100vh;
		}
		a{ color:inherit; text-decoration:none; }

		/* ===== TOPBAR ===== */
		.topbar{
			display:flex; align-items:center; justify-content:space-between; padding:16px 32px;
			position:sticky; top:0; z-index:30; background:rgba(243,244,246,0.86); backdrop-filter:blur(10px);
			border-bottom:1px solid var(--border);
		}
		.brand{ display:flex; align-items:center; gap:9px; }
		.brand-mark{ width:22px; height:22px; border-radius:5px; background:var(--ink); display:flex; align-items:center; justify-content:center; }
		.brand-mark svg{ width:12px; height:12px; }
		.brand-name{ font-family:var(--grot); font-weight:600; font-size:15px; }

		.dock{ display:flex; gap:2px; background:var(--win); border:1px solid var(--border); border-radius:9px; padding:4px; overflow-x:auto; }
		.dock a{ font-family:var(--sans); font-size:13px; color:var(--ink-dim); padding:6px 13px; border-radius:6px; white-space:nowrap; }
		.dock a:hover{ color:var(--ink); }
		.dock a.is-active{ background:var(--desk); color:var(--ink); font-weight:500; }

		.pilot-id{ font-family:var(--mono); font-size:11px; color:var(--ink-dim); display:flex; align-items:center; gap:7px; }
		.pilot-id .dot{ width:6px; height:6px; border-radius:50%; background:var(--green); }
		.pilot-id.is-guest .dot{ background:var(--ink-faint); }
		.pilot-id a.login-link{ color:var(--accent); font-weight:600; }

		.topbar-profile{ display:flex; align-items:center; gap:10px; }
		.topbar-profile .login-button,
		.topbar-profile .logout-button{
			display:inline-flex; align-items:center; justify-content:center;
			font:inherit; font-size:11px; font-weight:600; padding:7px 12px; border-radius:99px;
			border:1px solid var(--border-strong); background:var(--win); color:var(--ink); cursor:pointer;
		}
		.topbar-profile .logout-button{ color:var(--red); background:var(--red-soft); border-color:transparent; }

		/* ===== MODAL LOGIN / REGISTRO ===== */
		.login-modal-overlay{
			position:fixed; inset:0; z-index:90; background:rgba(20,23,26,0.54); backdrop-filter:blur(4px);
			display:none; align-items:center; justify-content:center; padding:20px;
		}
		.login-modal-overlay.is-open{ display:flex; }
		.login-modal-shell{
			position:relative; width:min(100%, 440px); max-height:90vh; overflow-y:auto;
			background:var(--win); border:1px solid var(--border-strong); border-radius:16px;
			box-shadow:0 30px 100px rgba(20,23,26,0.35);
		}
		.login-modal-shell .modal-close{
			position:absolute; top:10px; right:14px; border:none; background:transparent;
			font-size:26px; line-height:1; color:var(--ink-dim); cursor:pointer;
		}
		.login-modal-shell .modal-close:hover{ color:var(--ink); }
		.login-modal-body{ padding:30px 28px 28px; }
		.login-modal-brand{ display:flex; align-items:center; gap:9px; margin-bottom:18px; }

		.auth-tabs{ display:flex; border:1px solid var(--border); border-radius:9px; overflow:hidden; }
		.auth-tab{
			flex:1; font-family:var(--sans); font-size:13px; font-weight:600; color:var(--ink-dim);
			background:none; border:none; padding:11px 12px; cursor:pointer;
		}
		.auth-tab.is-active{ color:var(--ink); background:var(--desk); }

		.field-label{ display:block; font-size:11.5px; color:var(--ink-dim); margin-bottom:5px; }
		.field-input{
			width:100%; border:1px solid var(--border); border-radius:7px; padding:9px 11px;
			font-size:13px; font-family:var(--sans); background:var(--win); color:var(--ink);
		}
		.field-input:focus{ outline:none; border-color:var(--accent); }
		.field-row{ display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px; }
		@media (max-width:480px){ .field-row{ grid-template-columns:1fr; gap:0; } }

		/* ===== INTRO reutilizable por los themes ===== */
		.eyebrow{ font-family:var(--mono); font-size:11px; color:var(--accent); letter-spacing:1.5px; text-transform:uppercase; margin-bottom:8px; }

		/* ===== DESK / WINDOWS (grid de 12 columnas) ===== */
		.desk{ max-width:1160px; margin:0 auto; padding:28px 32px 80px; display:grid; grid-template-columns:repeat(12,1fr); gap:18px; }

		.win{
			background:var(--win); border:1px solid var(--border); border-radius:11px;
			box-shadow:0 1px 2px rgba(20,23,26,0.04), 0 8px 24px -14px rgba(20,23,26,0.10);
			overflow:hidden; scroll-margin-top:90px;
		}
		.win-bar{ display:flex; align-items:center; justify-content:space-between; padding:11px 16px; border-bottom:1px solid var(--border); }
		.win-bar-left{ display:flex; align-items:center; gap:10px; }
		.win-dots{ display:flex; gap:5px; } .win-dots span{ width:7px; height:7px; border-radius:50%; background:var(--border-strong); }
		.win-title{ font-family:var(--sans); font-size:12.5px; font-weight:500; color:var(--ink-dim); }
		.win-status{ font-family:var(--mono); font-size:10px; padding:2px 7px; border-radius:100px; letter-spacing:0.3px; }
		.win-status.green{ background:var(--green-soft); color:var(--green); }
		.win-status.amber{ background:var(--amber-soft); color:var(--amber); }
		.win-status.red{ background:var(--red-soft); color:var(--red); }
		.win-status.neutral{ background:var(--desk); color:var(--ink-faint); }
		.win-body{ padding:22px; }
		.desc{ color:var(--ink-dim); font-size:13px; line-height:1.55; }

		.s12{ grid-column:span 12; } .s7{ grid-column:span 7; } .s5{ grid-column:span 5; }
		.s6{ grid-column:span 6; } .s4{ grid-column:span 4; } .s8{ grid-column:span 8; }

		.data-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:0; margin-bottom:18px; }
		.data-item{ padding:0 18px; border-left:1px solid var(--border); }
		.data-item:first-child{ padding-left:0; border-left:none; }
		.k{ font-size:10.5px; color:var(--ink-faint); letter-spacing:0.4px; text-transform:uppercase; margin-bottom:5px; }
		.v{ font-family:var(--grot); font-size:19px; font-weight:600; }
		.v small{ font-size:11px; color:var(--ink-dim); font-weight:400; }

		.btn{ font-family:var(--sans); font-weight:600; font-size:13px; color:#fff; background:var(--ink); border:none; border-radius:7px; padding:10px 18px; cursor:pointer; display:inline-flex; align-items:center; gap:7px; }
		.btn.accent{ background:var(--accent); }

		/* ===== CLIMA · GRÁFICAS ===== */
		.chart-grid{ display:grid; grid-template-columns:1fr 1fr; gap:28px; }
		.chart-col{ min-width:0; }
		.chart-head{ display:flex; align-items:baseline; justify-content:space-between; margin-bottom:14px; }
		.chart-label{ font-size:12.5px; font-weight:500; color:var(--ink); }
		.chart-value{ font-family:var(--grot); font-size:15px; font-weight:600; color:var(--ink); }
		.chart-value small{ font-family:var(--sans); font-size:11px; color:var(--ink-faint); font-weight:400; }
		.chart-frame{ height:170px; position:relative; }
		.chart-empty{ position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:80%; text-align:center; font-size:12px; color:var(--ink-faint); line-height:1.5; }

		/* ===== CICLO SOLAR ===== */
		.sun-track{ margin-top:26px; padding-top:22px; border-top:1px solid var(--border); }
		.sun-bar{ position:relative; height:8px; border-radius:100px; background:linear-gradient(90deg, #E7E9EC 0%, #E7E9EC 100%); overflow:visible; margin:26px 0 18px; }
		.sun-bar-fill{ position:absolute; top:0; left:0; height:100%; border-radius:100px; background:linear-gradient(90deg, #FFD27A 0%, var(--accent) 100%); transition:width 0.4s ease; }
		.sun-marker{ position:absolute; top:50%; width:20px; height:20px; border-radius:50%; background:var(--accent); box-shadow:0 0 0 4px var(--accent-soft), 0 2px 6px rgba(20,23,26,0.18); transform:translate(-50%,-50%); transition:left 0.4s ease; }
		.sun-grid{ display:grid; grid-template-columns:repeat(4,1fr); gap:0; }
		.sun-grid .data-item{ padding:0 18px; border-left:1px solid var(--border); }
		.sun-grid .data-item:first-child{ padding-left:0; border-left:none; }

		/* ===== GEOCERCAS / MAPA ===== */
		.geo-body{ display:grid; grid-template-columns:1.5fr 1fr; gap:24px; align-items:start; }
		.geo-map{ height:340px; border-radius:8px; border:1px solid var(--border); background:#EDEFF1; position:relative; z-index:0; }
		.geo-legend{ display:flex; flex-direction:column; gap:9px; margin-bottom:16px; padding-bottom:16px; border-bottom:1px solid var(--border); }
		.geo-legend-item{ display:flex; align-items:center; gap:9px; font-size:12.5px; color:var(--ink-dim); }
		.geo-dot{ width:9px; height:9px; border-radius:50%; flex-shrink:0; }
		.geo-dot.green{ background:var(--green); box-shadow:0 0 0 3px var(--green-soft); }
		.geo-dot.amber{ background:var(--amber); box-shadow:0 0 0 3px var(--amber-soft); }
		.geo-dot.red{ background:var(--red); box-shadow:0 0 0 3px var(--red-soft); }
		.geo-zone-list{ margin-top:2px; display:flex; flex-direction:column; gap:2px; max-height:260px; overflow-y:auto; }
		.geo-zone-row{ display:flex; align-items:flex-start; gap:10px; padding:10px 0; border-bottom:1px solid var(--border); }
		.geo-zone-row:last-child{ border-bottom:none; }
		.geo-zone-row .geo-dot{ margin-top:5px; }
		.geo-zone-name{ font-size:13px; font-weight:500; }
		.geo-zone-meta{ font-size:11.5px; color:var(--ink-faint); margin-top:2px; }
		.geo-zone-empty{ font-size:12.5px; color:var(--ink-faint); padding:6px 0; }
		.geo-map .leaflet-control-attribution{ font-size:9.5px; background:rgba(255,255,255,0.75); }

		/* ===== GALERÍA · MINI MAPA ===== */
		.gal-map{ height:300px; border-radius:8px; border:1px solid var(--border); background:#EDEFF1; }

		@media (max-width:880px){
			.topbar{ flex-wrap:wrap; gap:12px; padding:14px 20px; }
			.dock{ order:3; width:100%; }
			.desk{ padding:20px 20px 60px; grid-template-columns:1fr; }
			.s12,.s7,.s5,.s6,.s4,.s8{ grid-column:span 1; }
			.data-grid{ grid-template-columns:repeat(2,1fr); row-gap:16px; }
			.data-item{ border-left:none; padding-left:0; }
			.chart-grid{ grid-template-columns:1fr; gap:26px; }
			.sun-grid{ grid-template-columns:repeat(2,1fr); row-gap:16px; }
			.sun-grid .data-item{ border-left:none; padding-left:0; }
			.geo-body{ grid-template-columns:1fr; }
			.geo-map{ height:260px; }
		}
	</style>
	<script src="<?php echo URL ?>/assets/js/chart.umd.min.js"></script>
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLEAPI ?>&libraries=places" defer async></script>
</head>
<body>

	<div class="response"></div>
	<div class="sbalerts"></div>
