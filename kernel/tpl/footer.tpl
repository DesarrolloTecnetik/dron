<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>

	// ---------------------------------------------------------------
	// Helpers globales usados por $CR->updateJS() en las respuestas AJAX
	// (ej. modal-basic.php hace: alerta("...", "danger"); button(true); modalX();)
	// ---------------------------------------------------------------

	function alerta(mensaje, tipo) {

		tipo = tipo || 'success';
		var colores = { success: '#1FA463', danger: '#D6483C', warning: '#C98A12' };
		var caja = document.createElement('div');
		caja.textContent = mensaje;
		caja.style.cssText = 'position:fixed;top:18px;right:18px;z-index:200;background:#fff;'
			+ 'border-left:3px solid ' + (colores[tipo] || colores.success) + ';border-radius:8px;'
			+ 'box-shadow:0 8px 24px -8px rgba(20,23,26,.25);padding:12px 16px;font-family:Inter,sans-serif;'
			+ 'font-size:13px;max-width:280px;';
		document.querySelector('.sbalerts').appendChild(caja);
		setTimeout(function() { caja.remove(); }, 4000);

	}

	function modalX() {

		document.querySelectorAll('.modalSB').forEach(function(m) { m.style.display = 'none'; });
		document.querySelectorAll('.loadermodals').forEach(function(m) { m.innerHTML = ''; });

	}

	function button(activar) {

		document.querySelectorAll('button[disabled]').forEach(function(b) { if (activar) b.disabled = false; });

	}

</script>
</body>
</html>
