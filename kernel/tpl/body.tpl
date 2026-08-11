<?php
	// $action, $UserID, $User, $avatarUser vienen ya definidos por init.conf / index.php
	// slugs que ya son páginas propias (kernel/themes/{slug}.php) vs. anclas
	// a una ventana ("win-{slug}") dentro del dashboard /inicio.
	$dockItems = array(
		'inicio'     => array('label' => 'Inicio', 'href' => URL.'/inicio'),
		'galeria'    => array('label' => 'Galería', 'href' => URL.'/inicio/galeria'),
		'clima'      => array('label' => 'Clima', 'href' => URL.'/inicio#win-clima'),
		'geocercas'  => array('label' => 'GeoCercas', 'href' => URL.'/inicio#win-geocercas'),
		'bitacora'   => array('label' => 'Bitácora', 'href' => URL.'/inicio/bitacora'),
		'noticias'   => array('label' => 'Noticias', 'href' => URL.'/inicio/noticias'),
		'equipo'     => array('label' => 'Equipo', 'href' => URL.'/inicio/equipo'),
	);
	$pilotUser = ($UserID >= 1) ? $User->user($UserID, 'user') : null;
?>
<div class="topbar">

	<div class="brand">
		<div class="brand-mark"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg></div>
		<div class="brand-name"><?php echo TITLE ?></div>
	</div>

	<div class="dock">
		<?php foreach( $dockItems as $slug => $item ) { ?>
			<a href="<?php echo $item['href'] ?>" class="<?php echo ($action == $slug) ? 'is-active' : '' ?>"><?php echo $item['label'] ?></a>
		<?php } ?>
	</div>

	<div class="topbar-profile">
		<?php if( $UserID >= 1 ) { ?>
			<div class="pilot-id"><span class="dot"></span><?php echo strtoupper($pilotUser) ?></div>
			<a class="logout-button" href="<?php echo URL ?>/ajax/account_logout.php">Cerrar sesión</a>
		<?php } else { ?>
			<button type="button" class="login-button" data-open-login>Iniciar sesión</button>
		<?php } ?>
	</div>

</div>

<?php require PATH.'/kernel/tpl/login_modal.tpl'; ?>
