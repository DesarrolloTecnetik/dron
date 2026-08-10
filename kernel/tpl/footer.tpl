</div><!-- /.desk -->

	<script>
		const buttons = document.querySelectorAll('.dock button');
		buttons.forEach(btn => {
			btn.addEventListener('click', () => {
				buttons.forEach(b => b.classList.remove('is-active'));
				btn.classList.add('is-active');
				const target = document.getElementById(btn.dataset.target);
				if(!target) return;
				target.scrollIntoView({behavior:'smooth', block:'start'});
				document.querySelectorAll('.win').forEach(w => w.classList.remove('is-focus'));
				target.classList.add('is-focus');
				setTimeout(() => target.classList.remove('is-focus'), 1400);
			});
		});
	</script>

</body>
</html>