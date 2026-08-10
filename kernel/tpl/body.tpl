<div class="topbar">
		<div class="brand">
			<div class="brand-mark">
				<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
			</div>
			<div class="brand-name">RADAR</div>
		</div>

		<div class="dock">
			<button class="is-active" data-target="win-estado">Estado</button>
			<button data-target="win-clima">Clima</button>
			<button data-target="win-sol">Sol</button>
			<button data-target="win-mapa">GeoCercas</button>
			<button data-target="win-bitacora">Bitácora</button>
			<button data-target="win-noticias">Noticias</button>
			<button data-target="win-equipo">Equipo</button>
		</div>

		<div class="topbar-profile">
			<?php if (!empty($UserID)) : ?>
				<div class="user-chip">
					<img class="avatar-thumb" src="<?php echo htmlspecialchars($avatarUser); ?>" alt="Avatar">
					<div class="user-chip-text">
						<div class="user-name"><?php echo htmlspecialchars(!empty($pilotName) ? $pilotName : (!empty($pilotUser) ? $pilotUser : 'Usuario')); ?></div>
						<div class="user-sub"><?php echo htmlspecialchars(!empty($pilotEmail) ? $pilotEmail : 'Sin correo'); ?></div>
					</div>
					<a href="<?php echo URL; ?>/ajax/account_logout.php" class="logout-button" aria-label="Cerrar sesión">Salir</a>
				</div>
			<?php else : ?>
				<a class="login-button" href="#" data-open-login>Iniciar sesión</a>
				<a class="login-button" href="#" data-open-register style="margin-left:8px;">Crear cuenta</a>
			<?php endif; ?>
		</div>
	</div>

	<div class="login-modal-overlay" id="login-modal">
		<div class="login-modal-shell">
			<button class="modal-close" type="button" aria-label="Cerrar">×</button>
			<div class="login-modal-body">
				<div class="login-modal-brand">
					<span class="brand-mark small">
						<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
					</span>
					<div>
						<div class="login-modal-eyebrow">RADAR OPS</div>
						<h2>Acceso al panel</h2>
					</div>
				</div>
				<form class="login-modal-form" id="modal-login-form" method="post" action="<?php echo URL; ?>/ajax/account_start.php">
					<label class="field-label" for="modal-login-user">Usuario o correo</label>
					<input class="field-input" type="text" id="modal-login-user" name="user" autocomplete="username" placeholder="usuario@radar.local">
					<label class="field-label" for="modal-login-pass">Contraseña</label>
					<input class="field-input" type="password" id="modal-login-pass" name="pass" autocomplete="current-password" placeholder="••••••••">
					<div class="login-options">
						<label class="check-row">
							<input type="checkbox" name="remember" value="on">
							<span>Recordarme</span>
						</label>
						<a href="#" class="text-link">Recuperar acceso</a>
					</div>
					<button class="btn btn-accent btn-login" id="modal-login-button" type="submit">Entrar</button>
					<div class="login-message" id="modal-login-message"></div>
					<div class="auth-switch">¿No tienes cuenta? <a href="#" class="text-link" data-open-register>Regístrate</a></div>
				</form>
			</div>
		</div>
	</div>

	<div class="login-modal-overlay" id="register-modal">
		<div class="login-modal-shell">
			<button class="modal-close" type="button" aria-label="Cerrar">×</button>
			<div class="login-modal-body">
				<div class="login-modal-brand">
					<span class="brand-mark small">
						<svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg>
					</span>
					<div>
						<div class="login-modal-eyebrow">RADAR OPS</div>
						<h2>Crear cuenta</h2>
					</div>
				</div>
				<form class="login-modal-form" id="modal-register-form" method="post" action="<?php echo URL; ?>/ajax/account_signup.php">
					<div class="field-row">
						<div>
							<label class="field-label" for="register-name">Nombre</label>
							<input class="field-input" type="text" id="register-name" name="name" autocomplete="given-name" placeholder="Nombre" required>
						</div>
						<div>
							<label class="field-label" for="register-lastname">Apellido</label>
							<input class="field-input" type="text" id="register-lastname" name="lastname" autocomplete="family-name" placeholder="Apellido" required>
						</div>
					</div>

					<label class="field-label" for="register-nacimiento">Fecha de nacimiento</label>
					<input class="field-input" type="date" id="register-nacimiento" name="nacimiento" autocomplete="bday" required>

					<label class="field-label" for="register-country">País / Región</label>
					<select class="field-input" id="register-country" name="country" required>
						<option value="">Selecciona tu país</option>
						<option value="MX">México</option>
						<option value="US">Estados Unidos</option>
						<option value="CA">Canadá</option>
						<option value="AR">Argentina</option>
						<option value="BO">Bolivia</option>
						<option value="BR">Brasil</option>
						<option value="CL">Chile</option>
						<option value="CO">Colombia</option>
						<option value="CR">Costa Rica</option>
						<option value="CU">Cuba</option>
						<option value="DO">República Dominicana</option>
						<option value="EC">Ecuador</option>
						<option value="SV">El Salvador</option>
						<option value="ES">España</option>
						<option value="GT">Guatemala</option>
						<option value="HN">Honduras</option>
						<option value="NI">Nicaragua</option>
						<option value="PA">Panamá</option>
						<option value="PY">Paraguay</option>
						<option value="PE">Perú</option>
						<option value="PR">Puerto Rico</option>
						<option value="UY">Uruguay</option>
						<option value="VE">Venezuela</option>
						<option value="OT">Otro</option>
					</select>

					<label class="field-label" for="register-email">Correo electrónico</label>
					<input class="field-input" type="email" id="register-email" name="email" autocomplete="email" placeholder="correo@ejemplo.com" required>

					<div class="field-row">
						<div>
							<label class="field-label" for="register-pass">Contraseña</label>
							<input class="field-input" type="password" id="register-pass" name="pass" autocomplete="new-password" placeholder="••••••••" minlength="6" required>
						</div>
						<div>
							<label class="field-label" for="register-pass2">Confirmar contraseña</label>
							<input class="field-input" type="password" id="register-pass2" name="pass2" autocomplete="new-password" placeholder="••••••••" minlength="6" required>
						</div>
					</div>

					<button class="btn btn-accent btn-login" id="modal-register-button" type="submit" style="margin-top:20px;">Crear cuenta</button>
					<div class="login-message" id="modal-register-message"></div>
					<div class="auth-switch">¿Ya tienes cuenta? <a href="#" class="text-link" data-open-login>Inicia sesión</a></div>
				</form>
			</div>
		</div>
	</div>

	<div class="intro">
		<div class="eyebrow"><?php echo !empty($briefingLabel) ? $briefingLabel : "Briefing de hoy"; ?></div>
		<h1><?php echo !empty($briefingTitle) ? $briefingTitle : "Bienvenido a RADAR"; ?></h1>
		<p><?php echo !empty($briefingSub) ? $briefingSub : "Cada panel funciona por separado — revisa el que necesites sin depender de los demás."; ?></p>
	</div>

	<div class="desk">