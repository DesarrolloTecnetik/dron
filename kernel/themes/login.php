<div class="desk">
	<div class="win s5" style="grid-column:4 / span 6; margin-top:40px;">
		<div class="win-bar">
			<div class="win-bar-left"><div class="win-dots"><span></span><span></span><span></span></div><div class="win-title">Iniciar sesión</div></div>
		</div>
		<div class="win-body">

			<p class="desc" style="margin-bottom:16px;">Accede con tu cuenta de piloto para publicar en la galería, guardar tu bitácora y sincronizar tu equipo.</p>

			<form id="formLogin">
				<div style="margin-bottom:12px;">
					<label style="display:block;font-size:11.5px;color:var(--ink-dim);margin-bottom:5px;">Usuario o correo</label>
					<input type="text" id="loginUser" name="user" required style="width:100%;border:1px solid var(--border);border-radius:7px;padding:9px 11px;font-size:13px;font-family:var(--sans);">
				</div>
				<div style="margin-bottom:16px;">
					<label style="display:block;font-size:11.5px;color:var(--ink-dim);margin-bottom:5px;">Contraseña</label>
					<input type="password" id="loginPass" name="pass" required style="width:100%;border:1px solid var(--border);border-radius:7px;padding:9px 11px;font-size:13px;font-family:var(--sans);">
				</div>
				<button type="submit" id="btnLogin" class="btn accent" style="width:100%;justify-content:center;">Entrar</button>
			</form>

		</div>
	</div>
</div>

<script>
	document.getElementById('formLogin').addEventListener('submit', function(e) {
		e.preventDefault();
		var btn = document.getElementById('btnLogin');
		btn.disabled = true; btn.textContent = 'Entrando…';

		var fd = new URLSearchParams({
			user: document.getElementById('loginUser').value,
			pass: document.getElementById('loginPass').value,
			remember: 'on'
		});

		fetch('<?php echo URL ?>/ajax/account_start.php', { method: 'POST', body: fd })
			.then(function(r) { return r.text(); })
			.then(function(html) {
				// account_start.php responde con $CR->updateJS(...) -> lo inyectamos para que corra alerta()/redirect
				var script = document.createElement('div');
				script.innerHTML = html;
				document.body.appendChild(script);
				btn.disabled = false; btn.textContent = 'Entrar';
			});
	});
</script>
