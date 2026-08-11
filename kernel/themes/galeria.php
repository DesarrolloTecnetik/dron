<?php
	// Esta vista asume que init.conf YA fue cargado por index.php (mismo patrón que inicio.php)
	// Variables disponibles: $UserID, $CR, $db, URL, GOOGLEAPI, $avatarUser
?>

<style>
	.rd-wrap{ --rd-bg:#F3F4F6; --rd-win:#FFFFFF; --rd-border:#E5E7EB; --rd-border-strong:#D6D9DE;
		--rd-ink:#14171A; --rd-dim:#767C87; --rd-faint:#A7ACB4; --rd-accent:#FF5A29; --rd-accent-soft:#FFF0EA;
		--rd-green:#1FA463; --rd-green-soft:#EAF7F0;
		font-family:'Inter',sans-serif; color:var(--rd-ink); background:var(--rd-bg); padding:28px 32px 70px; }
	.rd-wrap *{ box-sizing:border-box; }
	.rd-head{ max-width:1160px; margin:0 auto 20px; }
	.rd-eyebrow{ font-family:'JetBrains Mono',monospace; font-size:11px; color:var(--rd-accent); letter-spacing:1.5px; text-transform:uppercase; margin-bottom:8px; }
	.rd-head h1{ font-family:'Space Grotesk',sans-serif; font-size:26px; font-weight:600; }
	.rd-head p{ color:var(--rd-dim); font-size:13.5px; margin-top:6px; max-width:520px; }

	.rd-grid{ max-width:1160px; margin:0 auto; display:grid; grid-template-columns:280px 1fr; gap:18px; }
	.rd-win{ background:var(--rd-win); border:1px solid var(--rd-border); border-radius:11px; overflow:hidden;
		box-shadow:0 1px 2px rgba(20,23,26,.04), 0 8px 24px -14px rgba(20,23,26,.10); }
	.rd-bar{ display:flex; align-items:center; justify-content:space-between; padding:11px 16px; border-bottom:1px solid var(--rd-border); }
	.rd-bar-left{ display:flex; align-items:center; gap:9px; }
	.rd-dots{ display:flex; gap:5px; } .rd-dots span{ width:7px; height:7px; border-radius:50%; background:var(--rd-border-strong); }
	.rd-title{ font-size:12.5px; font-weight:500; color:var(--rd-dim); }
	.rd-body{ padding:16px; }

	/* sidebar filtro */
	.rd-site-item{ display:flex; align-items:center; justify-content:space-between; padding:9px 8px; border-radius:7px; cursor:pointer; font-size:13px; }
	.rd-site-item:hover{ background:var(--rd-bg); }
	.rd-site-item.is-active{ background:var(--rd-accent-soft); color:var(--rd-accent); font-weight:500; }
	.rd-site-count{ font-family:'JetBrains Mono',monospace; font-size:10.5px; color:var(--rd-faint); }

	.rd-top-item{ display:flex; align-items:center; gap:10px; padding:9px 0; border-bottom:1px solid var(--rd-border); cursor:pointer; }
	.rd-top-item:last-child{ border-bottom:none; }
	.rd-top-rank{ font-family:'Space Grotesk',sans-serif; font-weight:600; font-size:13px; color:var(--rd-faint); width:16px; }
	.rd-top-thumb{ width:38px; height:38px; border-radius:6px; object-fit:cover; background:var(--rd-bg); flex-shrink:0; }
	.rd-top-info{ min-width:0; }
	.rd-top-title{ font-size:12.5px; font-weight:500; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
	.rd-top-votos{ font-family:'JetBrains Mono',monospace; font-size:10.5px; color:var(--rd-accent); }

	/* mapa */
	#rdMapa{ height:420px; width:100%; }
	.rd-map-toolbar{ display:flex; align-items:center; justify-content:space-between; padding:10px 16px; border-bottom:1px solid var(--rd-border); }
	.rd-toggle{ display:flex; gap:4px; background:var(--rd-bg); border-radius:8px; padding:3px; }
	.rd-toggle button{ font-size:12px; border:none; background:none; padding:6px 12px; border-radius:6px; cursor:pointer; color:var(--rd-dim); }
	.rd-toggle button.is-active{ background:var(--rd-win); color:var(--rd-ink); font-weight:500; }
	.rd-btn{ font-weight:600; font-size:13px; color:#fff; background:var(--rd-accent); border:none; border-radius:7px; padding:9px 16px; cursor:pointer; }

	/* feed grid */
	.rd-feed{ max-width:1160px; margin:18px auto 0; display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
	.rd-card{ background:var(--rd-win); border:1px solid var(--rd-border); border-radius:10px; overflow:hidden; cursor:pointer; }
	.rd-card-media{ position:relative; height:130px; background:#eee; }
	.rd-card-media img, .rd-card-media video{ width:100%; height:100%; object-fit:cover; display:block; }
	.rd-card-tag{ position:absolute; top:8px; left:8px; background:rgba(20,23,26,.7); color:#fff; font-size:10px; padding:2px 7px; border-radius:100px; font-family:'JetBrains Mono',monospace; }
	.rd-card-body{ padding:10px 12px; }
	.rd-card-title{ font-size:12.5px; font-weight:500; margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
	.rd-card-meta{ display:flex; align-items:center; justify-content:space-between; font-size:11px; color:var(--rd-faint); }
	.rd-vote{ display:flex; align-items:center; gap:4px; cursor:pointer; }
	.rd-vote.is-active{ color:var(--rd-accent); font-weight:600; }

	/* modal publicar */
	.rd-modal-bg{ display:none; position:fixed; inset:0; background:rgba(20,23,26,.45); z-index:100; align-items:center; justify-content:center; }
	.rd-modal-bg.is-open{ display:flex; }
	.rd-modal{ background:var(--rd-win); border-radius:12px; width:420px; max-width:92vw; max-height:88vh; overflow-y:auto; }
	.rd-modal-head{ display:flex; align-items:center; justify-content:space-between; padding:16px 18px; border-bottom:1px solid var(--rd-border); }
	.rd-modal-head h3{ font-family:'Space Grotesk',sans-serif; font-size:15px; font-weight:600; }
	.rd-modal-close{ cursor:pointer; color:var(--rd-faint); font-size:18px; line-height:1; }
	.rd-modal-body{ padding:18px; display:flex; flex-direction:column; gap:12px; }
	.rd-field label{ display:block; font-size:11.5px; color:var(--rd-dim); margin-bottom:5px; }
	.rd-field input[type=text], .rd-field textarea, .rd-field select{
		width:100%; border:1px solid var(--rd-border); border-radius:7px; padding:9px 11px; font-size:13px; font-family:'Inter',sans-serif; }
	.rd-drop{ border:1.5px dashed var(--rd-border-strong); border-radius:9px; padding:22px; text-align:center; font-size:12.5px; color:var(--rd-dim); cursor:pointer; }
	.rd-fab{ position:fixed; bottom:26px; right:26px; background:var(--rd-accent); color:#fff; border:none; border-radius:100px;
		padding:13px 20px; font-weight:600; font-size:13.5px; cursor:pointer; box-shadow:0 10px 24px -8px rgba(255,90,41,.5); z-index:40; }

	/* imagen/video que no cargó */
	.rd-noimg{ width:100%; height:100%; display:flex; align-items:center; justify-content:center;
		background:var(--rd-bg); color:var(--rd-faint); font-size:11px; text-align:center; padding:6px; }

	/* modal detalle / comentarios */
	.rd-det-media{ width:100%; max-height:320px; min-height:120px; border-radius:9px; overflow:hidden; background:#000; }
	.rd-det-media img, .rd-det-media video{ width:100%; max-height:320px; object-fit:contain; display:block; margin:0 auto; }
	.rd-det-autor{ display:flex; align-items:center; gap:9px; }
	.rd-det-avatar{ width:32px; height:32px; border-radius:50%; object-fit:cover; background:var(--rd-bg); flex-shrink:0; }
	.rd-det-autor-name{ font-size:12.5px; font-weight:600; }
	.rd-det-autor-meta{ font-size:11px; color:var(--rd-faint); }
	.rd-det-like{ display:flex; align-items:center; gap:6px; cursor:pointer; font-size:13px; font-weight:600;
		color:var(--rd-dim); border:1px solid var(--rd-border); border-radius:100px; padding:7px 14px; width:fit-content; }
	.rd-det-like.is-active{ color:var(--rd-accent); border-color:var(--rd-accent-soft); background:var(--rd-accent-soft); }
	.rd-det-desc{ font-size:12.5px; color:var(--rd-dim); line-height:1.5; white-space:pre-wrap; }
	.rd-comentarios h4{ font-size:12.5px; font-weight:600; margin-bottom:2px; }
	.rd-comment-item{ display:flex; gap:8px; padding:8px 0; border-bottom:1px solid var(--rd-border); }
	.rd-comment-item:last-child{ border-bottom:none; }
	.rd-comment-avatar{ width:24px; height:24px; border-radius:50%; object-fit:cover; background:var(--rd-bg); flex-shrink:0; }
	.rd-comment-body{ min-width:0; }
	.rd-comment-autor{ font-size:11.5px; font-weight:600; }
	.rd-comment-texto{ font-size:12.5px; color:var(--rd-dim); word-break:break-word; }
	.rd-comment-fecha{ font-size:10px; color:var(--rd-faint); margin-top:2px; }
	.rd-comment-empty{ font-size:12px; color:var(--rd-faint); padding:6px 0; }
	.rd-comment-form{ display:flex; gap:8px; margin-top:4px; }
	.rd-comment-form textarea{ flex:1; resize:none; height:38px; border:1px solid var(--rd-border); border-radius:7px;
		padding:8px 10px; font-size:12.5px; font-family:'Inter',sans-serif; }

	@media (max-width:880px){
		.rd-grid{ grid-template-columns:1fr; }
		.rd-feed{ grid-template-columns:repeat(2,1fr); }
	}
</style>

<div class="rd-wrap">

	<div class="rd-head">
		<div class="rd-eyebrow">Comunidad</div>
		<h1>Galería en mapa</h1>
		<p>Fotos y videos tomados con dron, ubicados por sitio de interés. Vota tus favoritos para armar el Top.</p>
	</div>

	<div class="rd-grid">

		<!-- SIDEBAR: sitios + top -->
		<div style="display:flex; flex-direction:column; gap:18px;">

			<div class="rd-win">
				<div class="rd-bar">
					<div class="rd-bar-left"><div class="rd-dots"><span></span><span></span><span></span></div><div class="rd-title">Sitios de interés</div></div>
				</div>
				<div class="rd-body" id="rdSitios" style="padding:8px;">
					<div class="rd-site-item is-active" data-sitio="">
						<span>Todos</span><span class="rd-site-count" id="rdTotalTodos">—</span>
					</div>
					<!-- se llena por JS -->
				</div>
			</div>

			<div class="rd-win">
				<div class="rd-bar">
					<div class="rd-bar-left"><div class="rd-dots"><span></span><span></span><span></span></div><div class="rd-title">Top de la semana</div></div>
				</div>
				<div class="rd-body" id="rdTop">Cargando…</div>
			</div>

		</div>

		<!-- MAPA -->
		<div class="rd-win">
			<div class="rd-map-toolbar">
				<div class="rd-toggle">
					<button class="is-active" id="rdOrdenRecientes">Recientes</button>
					<button id="rdOrdenTop">Top</button>
				</div>
				<button class="rd-btn" onclick="rdAbrirModal()">+ Compartir</button>
			</div>
			<div id="rdMapa"></div>
		</div>

	</div>

	<!-- FEED -->
	<div class="rd-feed" id="rdFeed"></div>

	<!-- FAB móvil -->
	<button class="rd-fab" onclick="rdAbrirModal()">+ Compartir</button>

	<!-- MODAL: publicar -->
	<!-- IMPORTANTE: este modal debe vivir DENTRO de .rd-wrap: las variables
	     --rd-win/--rd-border/--rd-ink/etc. se definen como custom properties
	     en el selector .rd-wrap, así que solo están disponibles para sus
	     descendientes. Si el modal queda fuera (como estaba antes), esas
	     variables llegan "unset" y el cuadro se ve sin fondo, sin borde y
	     casi sin texto -> parece que "no se ve nada" al abrirlo. -->
	<div class="rd-modal-bg" id="rdModalBg">
		<div class="rd-modal">
			<div class="rd-modal-head">
				<h3>Compartir foto o video</h3>
				<div class="rd-modal-close" onclick="rdCerrarModal()">✕</div>
			</div>
			<div class="rd-modal-body">

				<div class="rd-field">
					<label>Archivo (foto o video)</label>
					<div class="rd-drop" id="rdDropZone">Toca para elegir un archivo</div>
					<input type="file" id="rdArchivo" accept="image/*,video/*" style="display:none;">
				</div>

				<div class="rd-field">
					<label>Título</label>
					<input type="text" id="rdTitulo" placeholder="Ej. Atardecer sobre la presa">
				</div>

				<div class="rd-field">
					<label>Sitio de interés</label>
					<select id="rdSitioSelect"><option value="">Sin sitio específico</option></select>
				</div>

				<div class="rd-field">
					<label>Ubicación</label>
					<div id="rdMapaModal" style="height:160px; border-radius:8px; border:1px solid var(--rd-border);"></div>
					<button type="button" class="rd-btn" style="margin-top:8px; background:var(--rd-ink);" onclick="rdUsarMiUbicacion()">Usar mi ubicación actual</button>
				</div>

				<button class="rd-btn" style="width:100%; padding:11px;" onclick="rdPublicar()">Publicar</button>

			</div>
		</div>
	</div>

	<!-- MODAL: detalle de publicación (autor, like, comentarios) -->
	<div class="rd-modal-bg" id="rdDetalleBg">
		<div class="rd-modal" style="width:520px;">
			<div class="rd-modal-head">
				<h3 id="rdDetTitulo">Publicación</h3>
				<div class="rd-modal-close" onclick="rdCerrarDetalle()">✕</div>
			</div>
			<div class="rd-modal-body">

				<div class="rd-det-media" id="rdDetMedia"></div>

				<div class="rd-det-autor">
					<img class="rd-det-avatar" id="rdDetAvatar" src="">
					<div>
						<div class="rd-det-autor-name" id="rdDetAutor">—</div>
						<div class="rd-det-autor-meta" id="rdDetMeta">—</div>
					</div>
				</div>

				<div class="rd-det-like" id="rdDetLike">▲ <span id="rdDetLikeTexto">Me gusta</span></div>

				<div class="rd-det-desc" id="rdDetDesc"></div>

				<div class="rd-comentarios">
					<h4>Comentarios</h4>
					<div id="rdComentariosList">Cargando…</div>
					<div class="rd-comment-form">
						<textarea id="rdComentarioTexto" placeholder="Escribe un comentario…" maxlength="500"></textarea>
						<button class="rd-btn" onclick="rdEnviarComentario()">Enviar</button>
					</div>
				</div>

			</div>
		</div>
	</div>

</div>

<!-- El Google Maps JS API ya se carga una vez en kernel/tpl/head.tpl;
     cargarlo de nuevo aquí disparaba el warning:
     "You have included the Google Maps JavaScript API multiple times" -->
<script>
	var RD_URL = "<?php echo URL ?>";
	var RD_LOGGED = <?php echo ($UserID != null) ? 'true' : 'false' ?>;
	var rdMap, rdMapModal, rdMarker, rdMarkers = [];
	var rdSitioActivo = "";
	var rdOrden = "recientes";
	var rdDetalleID = null;
	var rdLatSel = 20.6597, rdLngSel = -103.3496; // Guadalajara por defecto

	document.addEventListener("DOMContentLoaded", function() {
		rdCargarSitios();
		rdCargarPublicaciones();
		rdInitMapa();
		document.getElementById("rdDropZone").addEventListener("click", function() { document.getElementById("rdArchivo").click(); });
		document.getElementById("rdArchivo").addEventListener("change", function(e) {
			var f = e.target.files[0];
			document.getElementById("rdDropZone").textContent = f ? f.name : "Toca para elegir un archivo";
		});
		document.getElementById("rdOrdenRecientes").addEventListener("click", function() { rdCambiarOrden("recientes", this); });
		document.getElementById("rdOrdenTop").addEventListener("click", function() { rdCambiarOrden("top", this); });
	});

	function rdInitMapa() {
		setTimeout(function() {
			rdMap = new google.maps.Map(document.getElementById("rdMapa"), { center: { lat: rdLatSel, lng: rdLngSel }, zoom: 10 });
		}, 600);
	}

	function rdCambiarOrden(orden, btn) {
		rdOrden = orden;
		document.querySelectorAll(".rd-toggle button").forEach(function(b) { b.classList.remove("is-active"); });
		btn.classList.add("is-active");
		rdCargarPublicaciones();
	}

	// ---------------- SITIOS ----------------
	function rdCargarSitios() {
		fetch(RD_URL + "/ajax/galeria_sitios.php", { method: "POST", body: new URLSearchParams({ accion: "listar" }) })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data.ok) return;
				var cont = document.getElementById("rdSitios");
				var select = document.getElementById("rdSitioSelect");
				var totalTodos = 0;
				data.sitios.forEach(function(s) {
					totalTodos += parseInt(s.total);
					var item = document.createElement("div");
					item.className = "rd-site-item";
					item.dataset.sitio = s.id;
					item.innerHTML = "<span>" + s.nombre + "</span><span class='rd-site-count'>" + s.total + "</span>";
					item.addEventListener("click", function() { rdFiltrarSitio(s.id, item); });
					cont.appendChild(item);

					var opt = document.createElement("option");
					opt.value = s.id; opt.textContent = s.nombre;
					select.appendChild(opt);
				});
				document.getElementById("rdTotalTodos").textContent = totalTodos;
				document.querySelector(".rd-site-item[data-sitio='']").addEventListener("click", function() { rdFiltrarSitio("", this); });
			});
	}

	function rdFiltrarSitio(id, el) {
		rdSitioActivo = id;
		document.querySelectorAll(".rd-site-item").forEach(function(i) { i.classList.remove("is-active"); });
		el.classList.add("is-active");
		rdCargarPublicaciones();
	}

	// ---------------- PUBLICACIONES ----------------
	function rdCargarPublicaciones() {
		var params = { orden: rdOrden, limite: 60 };
		if (rdSitioActivo) params.sitio_id = rdSitioActivo;

		fetch(RD_URL + "/ajax/galeria_listar.php", { method: "POST", body: new URLSearchParams(params) })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data.ok) return;
				rdPintarMapa(data.publicaciones);
				rdPintarFeed(data.publicaciones);
				rdPintarTop(data.publicaciones.slice().sort(function(a, b) { return b.votos - a.votos; }).slice(0, 5));
			});
	}

	function rdPintarMapa(pubs) {
		rdMarkers.forEach(function(m) { m.setMap(null); });
		rdMarkers = [];
		if (!rdMap) { setTimeout(function() { rdPintarMapa(pubs); }, 700); return; }

		pubs.forEach(function(p) {
			if (!p.lat || !p.lng) return;
			var marker = new google.maps.Marker({
				position: { lat: parseFloat(p.lat), lng: parseFloat(p.lng) },
				map: rdMap,
				title: p.titulo || (p.tipo === "video" ? "Video" : "Foto")
			});
			var info = new google.maps.InfoWindow({
				content: "<div style='max-width:200px;font-family:Inter,sans-serif;'>" +
					"<strong style='font-size:13px;'>" + (p.titulo || "Sin título") + "</strong><br>" +
					"<span style='font-size:11px;color:#767C87;'>" + (p.sitio_nombre || "Sin sitio") + " · " + p.votos + " votos</span></div>"
			});
			marker.addListener("click", function() { info.open(rdMap, marker); });
			rdMarkers.push(marker);
		});
	}

	function rdPintarFeed(pubs) {
		var feed = document.getElementById("rdFeed");
		feed.innerHTML = "";
		pubs.forEach(function(p) {
			var card = document.createElement("div");
			card.className = "rd-card";
			var mediaURL = RD_URL + "/assets/media/galeria/" + encodeURIComponent(p.archivo);

			card.innerHTML =
				"<div class='rd-card-media'></div>" +
				"<div class='rd-card-body'>" +
					"<div class='rd-card-title'>" + (p.titulo || "Sin título") + "</div>" +
					"<div class='rd-card-meta'>" +
						"<span>" + (p.sitio_nombre || "Sin sitio") + "</span>" +
						"<span class='rd-vote" + (p.yaVote ? " is-active" : "") + "' data-id='" + p.id + "'>▲ " + p.votos + "</span>" +
					"</div>" +
				"</div>";

			// el <img>/<video> se arma aparte (no con innerHTML) para poder
			// engancharle un listener de "error" y mostrar un aviso legible
			// en vez del ícono roto del navegador cuando el archivo no carga.
			var mediaCont = card.querySelector(".rd-card-media");
			var mediaEl = p.tipo === "video" ? document.createElement("video") : document.createElement("img");
			mediaEl.src = mediaURL;
			if (p.tipo === "video") { mediaEl.muted = true; } else { mediaEl.loading = "lazy"; }
			mediaEl.addEventListener("error", function() {
				mediaCont.innerHTML = "<div class='rd-noimg'>Imagen no disponible</div>";
				mediaCont.appendChild(rdCrearTag(p.tipo));
			});
			mediaCont.appendChild(mediaEl);
			mediaCont.appendChild(rdCrearTag(p.tipo));

			card.querySelector(".rd-vote").addEventListener("click", function(e) { e.stopPropagation(); rdVotar(p.id, this); });
			card.addEventListener("click", function() { rdAbrirDetalle(p.id); });
			feed.appendChild(card);
		});
	}

	function rdCrearTag(tipo) {
		var tag = document.createElement("span");
		tag.className = "rd-card-tag";
		tag.textContent = tipo === "video" ? "VIDEO" : "FOTO";
		return tag;
	}

	function rdPintarTop(top5) {
		var cont = document.getElementById("rdTop");
		if (!top5.length) { cont.innerHTML = "<p style='font-size:12.5px;color:#A7ACB4;'>Aún no hay publicaciones.</p>"; return; }
		cont.innerHTML = "";
		top5.forEach(function(p, i) {
			var mediaURL = RD_URL + "/assets/media/galeria/" + encodeURIComponent(p.archivo);
			var row = document.createElement("div");
			row.className = "rd-top-item";
			row.innerHTML =
				"<span class='rd-top-rank'>" + (i + 1) + "</span>" +
				"<img class='rd-top-thumb'>" +
				"<div class='rd-top-info'>" +
					"<div class='rd-top-title'>" + (p.titulo || "Sin título") + "</div>" +
					"<div class='rd-top-votos'>" + p.votos + " votos</div>" +
				"</div>";

			var thumb = row.querySelector(".rd-top-thumb");
			thumb.src = mediaURL;
			thumb.addEventListener("error", function() { thumb.removeAttribute("src"); thumb.style.background = "var(--rd-border-strong)"; });

			row.addEventListener("click", function() { rdAbrirDetalle(p.id); });
			cont.appendChild(row);
		});
	}

	function rdVotar(id, el, onDone) {
		if (!RD_LOGGED) { if (window.openLoginModal) { window.openLoginModal(); } else { alert("Inicia sesión para votar."); } return; }
		fetch(RD_URL + "/ajax/galeria_votar.php", { method: "POST", body: new URLSearchParams({ publicacion_id: id }) })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data.ok) return;
				el.classList.toggle("is-active", data.voto_activo);
				if (typeof onDone === "function") { onDone(data.votos); }
				else { el.textContent = "▲ " + data.votos; }
			});
	}

	// ---------------- MODAL DETALLE (autor · like · comentarios) ----------------
	function rdAbrirDetalle(id) {
		rdDetalleID = id;
		document.getElementById("rdDetTitulo").textContent = "Cargando…";
		document.getElementById("rdDetMedia").innerHTML = "";
		document.getElementById("rdDetDesc").textContent = "";
		document.getElementById("rdComentariosList").innerHTML = "Cargando…";
		document.getElementById("rdDetalleBg").classList.add("is-open");

		fetch(RD_URL + "/ajax/galeria_detalle.php", { method: "POST", body: new URLSearchParams({ accion: "ver", publicacion_id: id }) })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data.ok) { document.getElementById("rdDetTitulo").textContent = "No se pudo cargar"; return; }
				rdPintarDetalle(data.publicacion, data.comentarios);
			})
			.catch(function() { document.getElementById("rdDetTitulo").textContent = "No se pudo cargar"; });
	}

	function rdCerrarDetalle() {
		document.getElementById("rdDetalleBg").classList.remove("is-open");
		rdDetalleID = null;
	}

	function rdPintarDetalle(p, comentarios) {
		document.getElementById("rdDetTitulo").textContent = p.titulo || "Sin título";

		var mediaURL = RD_URL + "/assets/media/galeria/" + encodeURIComponent(p.archivo);
		var mediaCont = document.getElementById("rdDetMedia");
		mediaCont.innerHTML = "";
		var mediaEl = p.tipo === "video" ? document.createElement("video") : document.createElement("img");
		mediaEl.src = mediaURL;
		if (p.tipo === "video") { mediaEl.controls = true; } else { mediaEl.loading = "lazy"; }
		mediaEl.addEventListener("error", function() { mediaCont.innerHTML = "<div class='rd-noimg'>Imagen no disponible</div>"; });
		mediaCont.appendChild(mediaEl);

		var avatarURL = p.autor_avatar ? (RD_URL + "/assets/images/avatars/" + p.autor_avatar) : (RD_URL + "/assets/images/avatars/default.jpg");
		var avatarEl = document.getElementById("rdDetAvatar");
		avatarEl.style.visibility = "visible";
		avatarEl.src = avatarURL;
		avatarEl.onerror = function() { avatarEl.style.visibility = "hidden"; };

		document.getElementById("rdDetAutor").textContent = p.autor_nombre || p.autor || "Piloto";
		document.getElementById("rdDetMeta").textContent = (p.sitio_nombre || "Sin sitio") + " · " + p.vistas + " vistas";
		document.getElementById("rdDetDesc").textContent = p.descripcion || "";

		var likeBtn = document.getElementById("rdDetLike");
		likeBtn.classList.toggle("is-active", !!p.yaVote);
		document.getElementById("rdDetLikeTexto").textContent = p.votos + " Me gusta";
		likeBtn.onclick = function() {
			rdVotar(p.id, likeBtn, function(nuevoVotos) {
				document.getElementById("rdDetLikeTexto").textContent = nuevoVotos + " Me gusta";
			});
		};

		rdPintarComentarios(comentarios);
	}

	function rdPintarComentarios(comentarios) {
		var cont = document.getElementById("rdComentariosList");
		if (!comentarios.length) { cont.innerHTML = "<div class='rd-comment-empty'>Sé el primero en comentar.</div>"; return; }
		cont.innerHTML = "";
		comentarios.forEach(function(c) { cont.appendChild(rdCrearComentarioEl(c)); });
	}

	function rdCrearComentarioEl(c) {
		var item = document.createElement("div");
		item.className = "rd-comment-item";
		var avatarURL = c.autor_avatar ? (RD_URL + "/assets/images/avatars/" + c.autor_avatar) : (RD_URL + "/assets/images/avatars/default.jpg");
		item.innerHTML =
			"<img class='rd-comment-avatar' src='" + avatarURL + "'>" +
			"<div class='rd-comment-body'>" +
				"<div class='rd-comment-autor'>" + (c.autor || "Piloto") + "</div>" +
				"<div class='rd-comment-texto'></div>" +
				"<div class='rd-comment-fecha'>" + c.idatetime + "</div>" +
			"</div>";
		// el texto del comentario se inserta con textContent (no innerHTML)
		// para que un comentario no pueda inyectar HTML/JS de otro usuario
		item.querySelector(".rd-comment-texto").textContent = c.comentario;
		item.querySelector(".rd-comment-avatar").addEventListener("error", function() { this.style.visibility = "hidden"; });
		return item;
	}

	function rdEnviarComentario() {
		if (!RD_LOGGED) { if (window.openLoginModal) { window.openLoginModal(); } else { alert("Inicia sesión para comentar."); } return; }
		if (!rdDetalleID) return;

		var textarea = document.getElementById("rdComentarioTexto");
		var texto = textarea.value.trim();
		if (!texto) return;

		fetch(RD_URL + "/ajax/galeria_detalle.php", { method: "POST", body: new URLSearchParams({ accion: "comentar", publicacion_id: rdDetalleID, comentario: texto }) })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (!data.ok) { alert("No se pudo enviar el comentario."); return; }
				var cont = document.getElementById("rdComentariosList");
				var vacio = cont.querySelector(".rd-comment-empty");
				if (vacio) { cont.innerHTML = ""; }
				cont.appendChild(rdCrearComentarioEl(data.comentario));
				textarea.value = "";
			});
	}

	// ---------------- MODAL PUBLICAR ----------------
	function rdAbrirModal() {
		if (!RD_LOGGED) { if (window.openLoginModal) { window.openLoginModal(); } else { alert("Inicia sesión para compartir contenido."); } return; }
		document.getElementById("rdModalBg").classList.add("is-open");
		setTimeout(function() {
			rdMapModal = new google.maps.Map(document.getElementById("rdMapaModal"), { center: { lat: rdLatSel, lng: rdLngSel }, zoom: 11 });
			rdMarker = new google.maps.Marker({ position: { lat: rdLatSel, lng: rdLngSel }, map: rdMapModal, draggable: true });
			rdMarker.addListener("dragend", function() {
				rdLatSel = rdMarker.getPosition().lat();
				rdLngSel = rdMarker.getPosition().lng();
			});
		}, 300);
	}

	function rdCerrarModal() { document.getElementById("rdModalBg").classList.remove("is-open"); }

	function rdUsarMiUbicacion() {
		if (!navigator.geolocation) { alert("Tu navegador no soporta geolocalización."); return; }
		navigator.geolocation.getCurrentPosition(function(pos) {
			rdLatSel = pos.coords.latitude; rdLngSel = pos.coords.longitude;
			if (rdMapModal) { rdMapModal.setCenter({ lat: rdLatSel, lng: rdLngSel }); rdMarker.setPosition({ lat: rdLatSel, lng: rdLngSel }); }
		});
	}

	function rdPublicar() {
		var archivo = document.getElementById("rdArchivo").files[0];
		if (!archivo) { alert("Elige una foto o video."); return; }

		var fd = new FormData();
		fd.append("archivo", archivo);
		fd.append("titulo", document.getElementById("rdTitulo").value);
		fd.append("sitio_id", document.getElementById("rdSitioSelect").value);
		fd.append("lat", rdLatSel);
		fd.append("lng", rdLngSel);

		fetch(RD_URL + "/ajax/galeria_publicar.php", { method: "POST", body: fd })
			.then(function(r) { return r.json(); })
			.then(function(data) {
				if (data.ok) { rdCerrarModal(); rdCargarPublicaciones(); }
				else { alert("No se pudo publicar: " + data.error); }
			});
	}
</script>