<div class="topbar">
		<div class="brand">
			<div class="brand-mark">
				<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
			</div>
			<div class="brand-name">RADAR</div>
		</div>

		<div class="dock">
			<button class="is-active" data-target="win-estado">Estado</button>
			<button data-target="win-mapa">GeoCercas</button>
			<button data-target="win-bitacora">Bitácora</button>
			<button data-target="win-noticias">Noticias</button>
			<button data-target="win-equipo">Equipo</button>
		</div>

		<div class="pilot-id">
			<span class="dot"></span>
			<?php
				// nombre e identificador del piloto en sesión, si existe
				echo !empty($pilotName) ? strtoupper($pilotName) : "INVITADO";
				echo !empty($pilotLicense) ? " · ".strtoupper($pilotLicense) : "";
			?>
		</div>
	</div>

	<div class="intro">
		<div class="eyebrow"><?php echo !empty($briefingLabel) ? $briefingLabel : "Briefing de hoy"; ?></div>
		<h1><?php echo !empty($briefingTitle) ? $briefingTitle : "Bienvenido a RADAR"; ?></h1>
		<p><?php echo !empty($briefingSub) ? $briefingSub : "Cada panel funciona por separado — revisa el que necesites sin depender de los demás."; ?></p>
	</div>

	<div class="desk">