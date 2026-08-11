<?php
	// $action, $UserID, $User, $avatarUser vienen ya definidos por init.conf / index.php
	$dockItems = array(
		'inicio'     => 'Inicio',
		'galeria'    => 'Galería',
		'clima'      => 'Clima',
		'geocercas'  => 'GeoCercas',
		'bitacora'   => 'Bitácora',
		'noticias'   => 'Noticias',
		'equipo'     => 'Equipo',
	);
	$pilotUser = ($UserID >= 1) ? $User->user($UserID, 'user') : null;
?>
<div class="topbar">

	<div class="brand">
		<div class="brand-mark"><svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M2 12h4M18 12h4"/></svg></div>
		<div class="brand-name"><?php echo TITLE ?></div>
	</div>

	<div class="dock">
		<?php foreach( $dockItems as $slug => $label ) { ?>
			<a href="<?php echo URL ?>/inicio/<?php echo $slug ?>" class="<?php echo ($action == $slug) ? 'is-active' : '' ?>"><?php echo $label ?></a>
		<?php } ?>
	</div>

	<?php if( $UserID >= 1 ) { ?>
		<div class="pilot-id"><span class="dot"></span><?php echo strtoupper($pilotUser) ?></div>
	<?php } else { ?>
		<div class="pilot-id is-guest"><span class="dot"></span><a class="login-link" href="<?php echo URL ?>/login">Iniciar sesión</a></div>
	<?php } ?>

</div>
