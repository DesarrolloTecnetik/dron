<?php
	// Tema de invocación para la pantalla de login.
	// Se renderiza desde index.php con la acción login.
?>

<div class="login-layout">
	<div class="login-art">
		<div class="login-art-content">
			<div class="brand-badge">
				<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
			</div>
			<h1>RADAR OPS</h1>
			<p class="login-kicker">Sistema de monitoreo y vuelo</p>
			<div class="login-status-grid">
				<div>
					<span class="small-label">Estado</span>
					<strong>Operativo</strong>
				</div>
				<div>
					<span class="small-label">Zona</span>
					<strong><?php echo !empty($geoCenterLat) ? 'Monitor' : 'Sistema'; ?></strong>
				</div>
			</div>
		</div>
	</div>

	<div class="login-panel-wrap">
		<form class="login-panel" id="login-form">
			<div class="login-top">
				<div>
					<p class="eyebrow login-eyebrow">Acceso</p>
					<h2>Iniciar sesión</h2>
				</div>
				<div class="login-orb">
					<span></span>
				</div>
			</div>

			<label class="field-label" for="login-user">Usuario o correo</label>
			<input class="field-input" type="text" id="login-user" name="user" autocomplete="username" placeholder="usuario@radar.local">

			<label class="field-label" for="login-pass">Contraseña</label>
			<input class="field-input" type="password" id="login-pass" name="pass" autocomplete="current-password" placeholder="••••••••">

			<div class="login-options">
				<label class="check-row">
					<input type="checkbox" name="remember" value="on">
					<span>Recordarme</span>
				</label>
				<a href="#" class="text-link">Recuperar acceso</a>
			</div>

			<button class="btn btn-accent btn-login" id="login-button" type="submit">Entrar al panel</button>
			<div class="login-message" id="login-message"></div>
		</form>
	</div>
</div>

<script>
	(function setupLoginForm() {
		const form = document.getElementById('login-form');
		if (!form) { return; }
		form.addEventListener('submit', function (event) {
			event.preventDefault();
			const formData = new FormData(form);
			const button = document.getElementById('login-button');
			if (button) { button.disabled = true; button.textContent = 'Conectando...'; }
			const appBase = '<?php echo defined('URL') ? URL : ''; ?>';
			fetch(appBase + '/ajax/account_start.php', {
				method: 'POST',
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				},
				body: formData
			})
			.then(response => response.text())
			.then(html => {
				const message = document.getElementById('login-message');
				if (message) { message.innerHTML = html; }
			})
			.finally(() => {
				if (button) {
					button.disabled = false;
					button.textContent = 'Entrar al panel';
				}
			});
		});
	})();
</script>
