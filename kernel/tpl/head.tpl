<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo isset($pageTitle) ? $pageTitle : TITLE ?></title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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

		@media (max-width:880px){
			.topbar{ flex-wrap:wrap; gap:12px; padding:14px 20px; }
			.dock{ order:3; width:100%; }
			.desk{ padding:20px 20px 60px; grid-template-columns:1fr; }
			.s12,.s7,.s5,.s6,.s4,.s8{ grid-column:span 1; }
			.data-grid{ grid-template-columns:repeat(2,1fr); row-gap:16px; }
			.data-item{ border-left:none; padding-left:0; }
		}
	</style>
</head>
<body>

	<div class="response"></div>
	<div class="sbalerts"></div>
