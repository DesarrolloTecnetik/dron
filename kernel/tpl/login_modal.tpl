<?php // solo tiene sentido para visitantes -> $UserID viene de init.conf ?>
<?php if( $UserID == null ) { ?>

<div class="login-modal-overlay" id="loginModalOverlay">
	<div class="login-modal-shell">
		<button type="button" class="modal-close" id="loginModalClose" aria-label="Cerrar">&times;</button>

		<div class="login-modal-body">

			<div class="login-modal-brand">
				<div class="brand-mark"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg></div>
				<div class="brand-name"><?php echo TITLE ?></div>
			</div>

			<div class="auth-tabs">
				<button type="button" id="tabLogin" class="auth-tab is-active">Iniciar sesión</button>
				<button type="button" id="tabRegister" class="auth-tab">Crear cuenta</button>
			</div>

			<!-- LOGIN -->
			<div id="panelLogin">

				<p class="desc" style="margin:14px 0 16px;">Accede con tu cuenta de piloto para publicar en la galería, guardar tu bitácora y sincronizar tu equipo.</p>

				<form id="formLogin">
					<div style="margin-bottom:12px;">
						<label class="field-label">Usuario o correo</label>
						<input type="text" id="loginUser" name="user" required class="field-input">
					</div>
					<div style="margin-bottom:16px;">
						<label class="field-label">Contraseña</label>
						<input type="password" id="loginPass" name="pass" required class="field-input">
					</div>
					<button type="submit" id="btnLogin" class="btn accent" style="width:100%;justify-content:center;">Entrar</button>
				</form>

			</div>

			<!-- REGISTRO -->
			<div id="panelRegister" style="display:none;">

				<p class="desc" style="margin:14px 0 16px;">Crea tu cuenta de piloto. Es gratis y te logueamos automáticamente al terminar.</p>

				<form id="formRegister">
					<div class="field-row">
						<div>
							<label class="field-label">Nombre</label>
							<input type="text" name="name" required class="field-input">
						</div>
						<div>
							<label class="field-label">Apellido</label>
							<input type="text" name="lastname" required class="field-input">
						</div>
					</div>
					<div class="field-row">
						<div>
							<label class="field-label">Fecha de nacimiento</label>
							<input type="date" name="nacimiento" required class="field-input">
						</div>
						<div>
							<label class="field-label">País</label>
							<input type="text" name="country" required class="field-input" placeholder="México">
						</div>
					</div>
					<div style="margin-bottom:12px;">
						<label class="field-label">Correo electrónico</label>
						<input type="email" name="email" required class="field-input">
					</div>
					<div class="field-row">
						<div>
							<label class="field-label">Contraseña</label>
							<input type="password" name="pass" required minlength="6" class="field-input">
						</div>
						<div>
							<label class="field-label">Confirmar contraseña</label>
							<input type="password" name="pass2" required minlength="6" class="field-input">
						</div>
					</div>
					<button type="submit" id="btnRegister" class="btn accent" style="width:100%;justify-content:center;margin-top:6px;">Crear cuenta</button>
				</form>

			</div>

		</div>
	</div>
</div>

<script>
	window.__autoOpenLogin = <?php echo !empty($autoOpenLogin) ? 'true' : 'false' ?>;

	(function() {

		var overlay = document.getElementById('loginModalOverlay');
		var openBtns = document.querySelectorAll('[data-open-login]');
		var closeBtn = document.getElementById('loginModalClose');

		function openModal() { overlay.classList.add('is-open'); document.body.style.overflow = 'hidden'; }
		function closeModal() { overlay.classList.remove('is-open'); document.body.style.overflow = ''; }
		window.openLoginModal = openModal;

		openBtns.forEach(function(b) { b.addEventListener('click', openModal); });
		closeBtn.addEventListener('click', closeModal);
		overlay.addEventListener('click', function(e) { if (e.target === overlay) closeModal(); });
		document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeModal(); });

		if (window.__autoOpenLogin) { openModal(); }

		// -----------------------------------------------------------
		// TABS
		// -----------------------------------------------------------
		var tabLogin = document.getElementById('tabLogin');
		var tabRegister = document.getElementById('tabRegister');
		var panelLogin = document.getElementById('panelLogin');
		var panelRegister = document.getElementById('panelRegister');

		tabLogin.addEventListener('click', function() {
			tabLogin.classList.add('is-active'); tabRegister.classList.remove('is-active');
			panelLogin.style.display = ''; panelRegister.style.display = 'none';
		});
		tabRegister.addEventListener('click', function() {
			tabRegister.classList.add('is-active'); tabLogin.classList.remove('is-active');
			panelRegister.style.display = ''; panelLogin.style.display = 'none';
		});

		// -----------------------------------------------------------
		// LOGIN
		// -----------------------------------------------------------
		document.getElementById('formLogin').addEventListener('submit', function(e) {
			e.preventDefault();
			var btn = document.getElementById('btnLogin');
			btn.disabled = true; btn.textContent = 'Entrando…';

			var fd = new URLSearchParams({
				user: document.getElementById('loginUser').value,
				pass: document.getElementById('loginPass').value,
				remember: 'on'
			});

			fetch('<?php echo URL ?>/ajax/account_start.php', {
				method: 'POST',
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				body: fd
			})
				.then(function(r) { return r.text(); })
				.then(function(html) {
					runServerScript(html);
					btn.disabled = false; btn.textContent = 'Entrar';
				})
				.catch(function() {
					alerta('No se pudo conectar con el servidor.', 'danger');
					btn.disabled = false; btn.textContent = 'Entrar';
				});
		});

		// -----------------------------------------------------------
		// REGISTRO
		// -----------------------------------------------------------
		document.getElementById('formRegister').addEventListener('submit', function(e) {
			e.preventDefault();
			var form = document.getElementById('formRegister');
			var btn = document.getElementById('btnRegister');
			btn.disabled = true; btn.textContent = 'Creando cuenta…';

			var fd = new URLSearchParams(new FormData(form));

			fetch('<?php echo URL ?>/ajax/account_signup.php', {
				method: 'POST',
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
				body: fd
			})
				.then(function(r) { return r.text(); })
				.then(function(html) {
					runServerScript(html);
					btn.disabled = false; btn.textContent = 'Crear cuenta';
				})
				.catch(function() {
					alerta('No se pudo conectar con el servidor.', 'danger');
					btn.disabled = false; btn.textContent = 'Crear cuenta';
				});
		});

	})();
</script>

<?php } ?>
