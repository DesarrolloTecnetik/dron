<?php

	$User = new Users();
	global $UserID;
	
	// Spanish language [Lenguaje en Español]
	$lang = array(

		// translate to navigations text [body.tpl]
		'nav_home'			=> 'Inicio',
		'nav_db'				=> 'Base de datos',
		'nav_addserver'	=> 'Añadir servidor',
		'nav_premium'		=> 'Premium',
		'nav_ads'			=> 'Anúnciate',
		'nav_contact'		=> 'Contacto',

		// lang and account user [body.tpl]
		'text_lang' => 'Idioma',
		'text_login' => 'Iniciar sesión',
		'acc_avatar' => 'Avatar',
		'acc_hello' => 'Hola',
		'acc_credits' => 'Créditos',
		'acc_welcome' => 'Bienvenid@',
		'acc_avatar_default' => 'Avatar default',
		'acc_login' => 'Iniciar sesión ahora',
		'acc_myprofile' => 'Mi perfil',
		'acc_panel' => 'Mi Panel',
		'acc_premium' => 'Premium',
		'text_register' => 'Crear cuenta',
		'text_recovery_pass' => 'Recuperar contraseña',
		'text_logout' => 'Cerrar sesión',

		// texts footer [footer.tpl]
		'foot_box1' => 'Más sobre nosotros',
		'foot_box2' => 'Contenido sobre nosotros en el píe de la página.',
		'foot_box3' => 'Mantente conectado',
		'foot_box4' => 'Encuentranos y siguenos en nuestras redes sociales.',
		'text_facebook' => 'Facebook',
		'text_instagram' => 'Instagram',
		'text_youtube' => 'Youtube',
		'text_twitter' => 'Twitter',
		'foot_fast_link' => 'Enlaces rápidos',
		'foot_fast_1' => 'Preguntas frecuentes',
		'foot_fast_2' => 'Nosotros',
		'foot_fast_3' => 'Términos y Condiciones: servidores',
		'foot_fast_4' => 'Términos y Condiciones: reviewers',
		'foot_fast_5' => 'Póliticas de privacidad',
		'foot_rights' => 'Todos los derechos reservados a <b>'.TITLE.'</b>',
		'foot_developed' => 'Developed by <a href="'.DEVELOPED_URL.'" target="_BLANK">'.DEVELOPED.'</a>',

		// anside texts [aside.tpl]
		'anside_1' => 'Anuncios',
		'anside_2' => 'Redes sociales',

		// texts themes [acceder.php]
		'acc_titleH2' => 'Inicia sesión',
		'acc_small' => 'Ingresa a tu cuenta registrada.',
		'acc_register_now' => 'Si aun no tienes tu cuenta, <a href="'.URL.'/registro">registrate ahora</a>.',
		'text_user' => 'Usuario',
		'text_pass' => 'Contraseña',
		'text_remember' => 'Recordar mi <b>cuenta</b> permanentemente.',

		// texts themes [account_adwords.php]
		'ad_title' => 'Anúnciate en nuestro sitio.',
		'ad_slogan' => 'Llega a las miles de personas que nos visitan diariamente.',
		'ad_add1' => 'Desde',
		'ad_add2' => 'Adquirir anuncio',
		'text_anuncio' => 'Anuncio',

		// texts themes [account_suscription.php]
		'sus_title' => 'Encuentra el plan adecuado para ti.',
		'sus_slogan' => 'Sí aun no tienes créditos, puedes <a href="'.URL.'/panel#addCoins">adquirirlos aquí</a>.',
		'sus_start' => 'Comenzar ahora',
		'sus_renew' => 'Renovar Premium',
		'sus_price_dro' => 'Costo en DragoCoins',
		'text_coin' => 'Dracoin - Moneda de '.TITLE.'',
		'text_days' => 'días',

		// texts themes [confirmaciones.php]
		'conf_back' => 'Regresar a inicio',

		// texts themes items [database_tables.php]
		'dbt_nav_1' => 'Todos',
		'dbt_nav_2' => 'Consumibles',
		'dbt_nav_3' => 'Cartas',
		'dbt_nav_4' => 'Armas',
		'dbt_nav_5' => 'Armaduras',
		'text_search' => 'Buscar...',
		'dbt_searching' => 'Buscando',

		// texts themes mobs [database_tables_mobs.php]
		'dbt_nav_mob_1' => 'Todos',
		'dbt_nav_mob_2' => 'MvP',
		'dbt_nav_mob_3' => 'Normal',
		'dbt_nav_mob_4' => 'Boss',

		// texts themes items [database_view_item.php]
		'text_info_general' => 'Información general',
		'text_price' => 'Precio venta',
		'text_peso' => 'Peso',
		'text_type' => 'Tipo',
		'text_subtype' => 'Sub-tipo',
		'text_location' => 'Ubicación',
		'text_compoun_in' => 'Compuesta en',
		'dbvi_can1' => 'Puede tirarse',
		'dbvi_can2' => 'Puede tradearse',
		'dbvi_can3' => 'Almacenable storage',
		'dbvi_can4' => 'Almacenable carrito',
		'dbvi_can5' => 'Puede venderse',
		'dbvi_can6' => 'Envío por mail',
		'dbvi_can7' => 'Vendible en subasta',
		'dbvi_can8' => 'Almacenable guild',
		'text_shared' => 'Compartir',
		'text_report' => 'Reportar',
		'dbvi_card_image' => 'Imagen carta',
		'text_description' => 'Descripción',
		'text_droped_by' => 'Dropeado por',
		'text_setof' => 'Set of',
		'text_sell_by' => 'Vendido por',
		'text_description_general' => 'Descripción general',
		'dbvi_tab_1_script' => 'Script',
		'text_no_results' => 'Oops no hay resultados por aquí.',
		'text_copied_navi' => 'Presione sobre el cuadro de <b>/navi</b> para copiar texto automaticamente.',

		// texts themes mobs [database_view_mob.php]
		'text_sprite' => 'Sprite',
		'text_race' => 'Raza',
		'text_size' => 'Tamaño',
		'text_level' => 'Nivel',
		'text_maps' => 'Mapas',
		'text_exp' => 'Exp',
		'text_elements' => 'Elementos',
		'text_unknown' => 'Desconocido',
		'text_subtype_item' => 'Subtipo de item',
		'text_view_more_info' => 'Ver más información',
		'text_type_item' => 'Tipo de item',
		'text_sub_map' => 'Sub mapa',
		'text_amount_time' => 'Cantidad / Tiempo',

		// texts themes [desconectar_cuenta.php]
		'logout_title' => 'Estás por cerrar la conexión a <b>'.TITLE.'</b>',
		'logout_small' => 'Aun puedes cancelar la desconexión <a href="'.URL.'/inicio">pulsando aquí</a>.',
		'logout_pulse' => '',

		// texts themes [errorpage.php]
		'text_404' => 'Error 404',
		'text_nofound' => 'Page not found',
		'text_nofound_span' => 'No hemos podido encontrar la página solicitada.',
		'text_back' => 'Regresar',

		// texts themes [inicio.php]
		'text_order' => 'Ordenar',
		'text_recents' => 'Recientes',
		'text_votes' => 'Votos',
		'text_visits' => 'Visitas',
		'text_version' => 'Versión',
		'text_rates' => 'Rates',
		'text_country' => 'País',

		// texts themes [panel_dashboard.php]
		'cp_nav_1' => 'Añadir servidor',
		'cp_nav_2' => 'Mis servidores',
		'cp_nav_3' => 'Añadir anuncio',
		'cp_nav_4' => 'Mis anuncios',
		'cp_nav_5' => 'Comprar Dracoins',
		'cp_nav_6' => 'Mis Dracoins',
		'cp_nav_7' => 'Historial de compras',
		'cp_nav_8' => 'Historial Premium',

		// texts themes [registro.php]
		'reg_create' => 'Crea tu cuenta',
		'reg_title' => 'Completa todos los campos para poder continuar con el registro.',
		'reg_small' => 'Si ya estás registrado, <a href="'.URL.'/cuenta">inicia sesión</a>.',
		'reg_username' => 'Nombre de usuario',
		'reg_email' => 'Email',
		'reg_captcha' => 'Captcha',
		'text_write' => 'Escribre',
		'reg_loading' => 'Cargando...',
		'reg_tos_1' => 'He leído y aceptado los <a href="'.URL.'/tys" target="_BLANK">términos y condiciones</a>.',
		'text_registrar' => 'Registrar',

		// texts themes [server_details.php]
		'sd_votos_all' => 'Votos totales',
		'sd_register_in' => 'Registro en',
		'text_last_update' => 'Última actualización',
		'text_information' => 'Información',
		'text_galeria' => 'Galería',
		'text_video' => 'Vídeos',
		'text_stadistics' => 'Estadisticas',
		'text_reviews' => 'Reseñas',
		'sd_vote_for_server' => 'Votar por el servidor',
		'sd_visit_page' => 'Visitar página web',
		'sd_tab1_rates' => 'Rates generales',
		'text_exp_base' => 'Experiencia base',
		'text_exp_job' => 'Experiencia Job',
		'text_exp_quest' => 'Experiencia Quests',
		'text_exp_drop' => 'Experiencia Drop',
		'text_drop_equip' => 'Drop Equipo',
		'text_drop_card' => 'Drop Cartas',
		'text_server_no_content' => 'Este servidor aún no ha subido contenido.',
		'text_load_info' => 'Cargando información...',
		'text_today' => 'HOY',
		'text_this_month' => 'Este mes',
		'text_votar' => 'Votar',

		// texts PATH files [account_validate.php]
		'accv_title' => 'Validación de cuenta',

		// texts PATH files [confirmation.php]
		'confirms_1_title' => 'Registro concluido',
		'confirms_1_color' => 'Ahora debes activar tu cuenta.',
		'confirms_1_send1' => '¡Gracias por completar tu registro!, ahora puedes <a href="'.URL.'/cuenta">iniciar sesión</a> y disfrutar de nuestro contenido. <br> Si tienes tu servidor, ¿que esperas para añadirlo?. <br><br> Puedes revisar nuestras <a href="'.URL.'/faqs">FAQS</a> para aclarar algunas dudas.',
		
		'confirms_2_title' => 'Error al validar cuenta',
		'confirms_2_color' => 'Contacta con la administración.',
		'confirms_2_send1' => 'El Token utilizado ha vencido o ya fue validado. <br> Si no reconoces está acción, puedes contactar con la administración, desde <a href="'.URL.'/contacto">este enlace</a>, para que revisen tu caso.',
		
		'confirms_3_title' => '¡Cuenta validada correctamente!',
		'confirms_3_color' => 'Ahora puedes iniciar sesión.',
		'confirms_3_send1' => 'Has confirmado el correo electrónico enviado a tu cuenta, <a href="'.URL.'/inicio">ahora puedes navegar</a> y disfrutar en nuestra comunidad. <br><br> Puedes consultar en nuestros <a href="'.URL.'/faqs">FAQS</a>, como utilizar todas nuestras herramientas y servicios.',

		'confirms_4_title' => '¡Bienvenido '.$User->user($UserID, 'user').'!',
		'confirms_4_color' => 'Navega en nuestra comunidad.',
		'confirms_4_send1' => 'Tu sesión ha sido iniciada, desde la <b>IP: '.$User->user($UserID, 'last_ip').'</b>. Cualquier otra sesión valida, cerrará las anteriores. Recomendamos guardar bien tus <b>datos de acceso</b> para evitar fraudes. <br> <br> Para continuar, ve al <a href="'.URL.'/inicio">inicio</a> o pulsa el botón situado debajo.',

		'confirms_5_title' => '¡Hasta pronto!',
		'confirms_5_color' => 'Te estaremos esperando.',
		'confirms_5_send1' => 'Hemos cerrado todas tus conexiones a nuestro sitio. Recuerda <b>proteger bien</b> tus datos de terceras personas. <br> <br> Recuerda que por conexiones diarias, recibes puntos que podrás canjear por otros articulos próximamente.',

		'confirms_6_color' => 'Cuenta bloqueada temporalmente.',
		'confirms_6_send1' => 'Tu cuenta ha sido bloqueada temporalmente. Si crees que ha ocurrido un error, favor de <a href="'.URL.'/contacto">contactar</a> a la administración. <br> <br> Tu cuenta será desbloqueada automaticamente en',

		'confirms_7_title' => 'Lo sentimos, compra rechazada',
		'confirms_7_color' => 'Intente más tarde nuevamente.',
		'confirms_7_send1' => 'Por el momento no pudimos procesar la compra de <b>Dracoins</b>. Puede intentar más tarde nuevamente con la misma u otra cuenta. <br><br> Si el problema persiste, contacte con la <a href="'.URL.'/contacto">administración</a>.',

		'confirms_8_title' => 'Has compado Dracoins',
		'confirms_8_color' => 'Has compado Dracoins.',
		'confirms_8_send1' => 'Tu compra ha sido efectuada correctamente, tus <b>Dracoins</b> han sido sumados a tu cuenta. <br><br> Puedes comprar más <b>Dracoins</b> desde <a href="'.URL.'/panel#addCoins">este enlace</a> o consultar tus <b>Dracoins</b> accediendo <a href="'.URL.'/panel#viewShops">desde aquí</a>.',

		'confirms_title' => 'Confirmación',

		// texts PATH files [cpanel.php]
		'cpanel_title' => 'Mi Panel',

		// texts PATH files [db_view.php]
		'text_view' => 'Viendo',
		'text_prefix' => 'Prefijo',
		'text_element' => 'Elemento',
		'text_no_descrip' => 'Sin descrición',
		'text_no_script' => 'Sin script',

		// texts PATH files [dra_adwords.php]
		'draad_title' => 'Anuncios',

		// texts PATH files [nofound.php]
		'nofound_title' => 'Página no encontrada',

		// texts PATH files [server.php]
		'text_no_aviable' => 'No disponible',
		'serv_flag' => 'Bandera de',
		'text_yes' => 'Sí',
		'text_no' => 'No',
		'serv_title' => 'Servidor Detalles',

		// texts PATH files [suscription.php]
		'sus_title' => 'Suscripciones',

		// texts AJAX [ALERTS]
		'alert_rellena_campos' => 'Rellena todos los campos.',
		'alert_error_session' => 'Ha ocurrido un error, inicie sesión nuevamente.',
		'alert_need_login' => 'Necesitas estar conectado para está función.',
		'alert_error_refresh' => 'Ha ocurrido un error, recargue la página.',

		// texts AJAX FILE [add_acount.php]
		'alert_tos' => 'Debes aceptar los términos y condiciones.',
		'alert_no_aviable_user' => 'El nombre de usuario no está disponible.',
		'alert_no_badword' => 'No puedes utilizar la palabra',
		'alert_no_aviable_email' => 'El correo electrónico ya está siendo utilizado.',
		'add_acc_email1' => 'Gracias por <b>crear tu cuenta</b> con nosotros. Somos una comunidad que busca ofrecerte un servicio <b>premium</b>. Tu nombre de usuario es',
		'add_acc_email2' => 'Para concluir tu registro, por favor, <b>haz clic en el botón</b> que se muestra a continuación para verificar tu identidad.',
		'text_verifica_account' => 'Verificar cuenta',
		'add_acc_email4' => 'Si no funciona el botón, presiona copia o presiona este enlace',
		'add_acc_email5' => '<h1 class="h1EMAIL">¿Tu no hiciste este registro?</h1>
		<div class="add_det">
			- No hagas nada, en 24 horas se borrarán los datos registrados con este correo.
		</div>',
		'add_acc_hello' => 'Tu cuenta se ha creado correctamente.',
		'add_acc_create' => 'Confirmación de registro',
		'alert_bad_captcha' => 'El código del Captcha, es incorrecto.',
		'alert_error_captcha' => 'Ha ocurrido un error, recarga el Captcha.',
		'alert_pass_characters' => 'La contraseña debe tener entre 8 y 45 caracteres.',
		'alert_error_pass1y2' => 'Las contraseñas no coinciden.',
		'alert_error_user_name' => 'El nombre de usuario debe ser entre 8 y 35 caracteres.',
		'alert_error_email_fake' => 'Debes ingresar un correo electrónico válido.',

		// texts AJAX FILE [add_adsword.php]
		'alert_error_server_select' => 'Ha ocurrido un error con el server seleccionado, actualiza página.',
		'alert_image_limit' => 'La imagen supera la medida permitida.',
		'alert_image_peso' => 'Selecciona una imagen con formato permitido no mayor a',
		'alert_image_weigth' => 'Selecciona una imagen con formato permitido.',
		'alert_image_type' => 'Selecciona un archivo tipo imagen.',
		'alert_image_need' => 'Tienes que subir una imagen.',
		'add_ads_finalice_shop' => 'Compra realizada correctamente.',
		'alert_no_dracoins1' => 'No cuentas con los DraCoins necesarios, compra',
		'alert_no_dracoins2' => 'DraCoins más',
		'alert_error_ads_price' => 'Ha ocurrido un error con el anuncio y el precio seleccionado.',
		'alert_error_ads_time' => 'Ha ocurrido un error con el tiempo seleccionado.',
		'alert_error_ads_refresh' => 'Ha ocurrido un error con el anuncio seleccionado, recargue página.',

		// texts AJAX FILE [add_server.php]
		'alert_server1' => 'Solo puedes tener',
		'alert_server2' => 'servidores',
		'alert_tags1' => 'Solo puedes colocar',
		'alert_server_name_error1' => 'El nombre del servidor, ya está siendo utilizado.',
		'alert_server_name_error2' => 'El nombre del servidor solo debe tener a-z/A-Z/0-9/- como caracteres.',
		'alert_server_name_url' => 'El enlace del sitio web, ya está siendo utilizado.',
		'alert_server_descrip1' => 'La descripción breve ha superado los',
		'alert_server_detail1' => 'La descripción detallada ha superado los ',
		'text_characters' => 'caracteres',
		'add_server_finalice' => 'Tu servidor ha sido añadido, espera sea autorizado.',
		'alert_select_rates' => 'Selecciona los Rates correctos.',
		'alert_select_mode' => 'Selecciona un Modo de juego correcto.',
		'alert_select_security' => 'Selecciona un Sistema de seguridad correcto.',
		'alert_select_emu' => 'Selecciona un emulador correctamente.',
		'alert_select_country' => 'Selecciona un país correctamente.',

		// texts AJAX FILE [change_lang.php]
		'changeLang_alert' => 'El idioma del sitio ha sido actualizado, espere un momento.',

		// texts AJAX FILE [close_account.php]
		'close_acc_logout' => 'Estamos cerrando su sesión ahora mismo.',
		'close_acc_logout_error' => 'Ya estabas desconectado, recargue la página.',

		// texts AJAX FILE [cpanel_addCoins.php]
		'cpanel_addC_title' => 'Compra tus <b>Dracoins</b>',
		'cpanel_addC_h2' => '¿Que son los <b>Dracoins</b>?',
		'cpanel_addC_p1' => 'Son las monedas (créditos) oficiales de <b>'.TITLE.'</b>, todos los productos y servicios con costo, serán adquiridos pagando solo con estos.',
		'cpanel_addC_p2' => 'Podrás identificarlos con este <b>icono</b>: <img src="'.URL.'/assets/images/dracoin.png">',
		'cpanel_addC_p3' => 'Por cada <b>1 USD</b> se te darán <b>'.CASH_PER_USD.' Dracoin</b>.',
		'cpanel_addC_buy_credits' => 'Comprar Créditos',
		'cpanel_addC_shoping_proccess' => 'Compra en proces...',

		// texts AJAX FILE [cpanel_addServer.php]
		'cpanel_addServer_title' => 'Añade tu servidor',
		'text_datos_generales' => 'Datos generales',
		'cpanel_addServer_url' => 'Enlace',
		'cpanel_addServer_url1' => 'Enlace a página principal',
		'cpanel_addServer_name1' => 'Nombre de tu servidor, solo <b>a-Z</b>, <b>0-9</b>, <b>guión (-)</b>',
		'cpanel_addServer_image' => 'Imagen banner',
		'text_drop_here' => 'Selecciona o arrastra el archivo aquí',
		'text_format' => 'Formato',
		'text_only' => 'solamente',
		'text_max' => 'Máximo',
		'cpanel_addServer_h3' => 'Caracteristicas principales',
		'text_accept_reviews' => 'Aceptar reseñas',
		'text_select' => '-- Selecciona --',
		'cpanel_addServer_comments_des' => 'Comentarios o reseñas en tu servidor',
		'cpanel_addServer_mode_game' => 'Modo de juego',
		'cpanel_addServer_place' => 'Lugar del servidor',
		'cpanel_addServer_rates1' => 'Generaliza los rates de tu servidor',
		'cpanel_addServer_emu' => 'Emulador utilizado',
		'text_emulator' => 'Emulador',
		'text_security' => 'Seguridad',
		'cpanel_addServer_security' => 'Seguridad en cliente utilizado',
		'cpanel_addServer_rates_title' => 'Rates de tu servidor',
		'cpanel_addServer_insert_per' => 'Inserta valores en porcentaje (%)',
		'text_info_extra' => 'Información extra',
		'text_small_descrip' => 'Descripción breve',
		'cpanel_addServer_max_chars' => 'Máximo 240 caracteres, aparecerá como texto principal en el Top de inicio',
		'text_large_descrip' => 'Descripción detallada',
		'text_name' => 'Nombre',
		
		'cpanel_addServer_chars_view' => 'aparecerá en la visualización completa',
		'text_data_premium' => 'Datos Premium',
		'cpanel_addServer_tags1' => 'Separa por coma o enter, máximo 10 tags (tags disponibles abajo)',
		'text_url_to' => 'Enlace a ',
		'text_cancel' => 'Cancelar',
		'text_continue' => 'Continuar',
		'text_files_selects' => 'archivos seleccionados',

		// texts AJAX FILE [cpanel_table_buys.php]
		'cP_tableB_th1' => 'Paypal ID',
		'cP_tableB_th2' => 'Dracoins',
		'cP_tableB_th3' => 'Monto',
		'cP_tableB_th4' => 'Fecha',
		'cP_tableB_th5' => 'Estatus',
		'text_actions' => 'Acciones',
		'text_view_details' => 'Ver detalles',
		'text_no_information' => 'No tenemos información por el momento.',

		// texts AJAX FILE [cpanel_table_premiums.php]
		'cP_tableP_th1' => 'Suscripción Premium',
		'cP_tableP_th2' => 'Costo en DragoCoins',
		'cP_tableP_th3' => 'Fecha de compra',
		'cP_tableP_th4' => 'Días de compra',
		'cP_tableP_th5' => 'Fecha de termino',
		'cP_tableP_th6' => 'Tiempo restante',
		'cP_tableP_th7' => 'Estatus',
		'cP_tableP_shop_more' => 'Comprar más',

		// texts AJAX FILE [cpanel_viewAds.php]
		'cP_viewA_th1' => 'Tipo Anuncio',
		'cP_viewA_th2' => 'Expira',
		'cP_viewA_th3' => 'Servidor',
		'cP_viewA_th4' => 'Clicks',
		'cP_viewA_th5' => 'Estatus',
		'text_image_ads' => 'Imagen Anuncio',
		'text_active' => 'Activo',
		'text_finalice' => 'Finalizado',
		'text_view_image' => 'Ver imagen',
		'text_delete_ads' => 'Eliminar Anuncio',

		// texts AJAX FILE [cpanel_viewCoins.php]
		'cP_viewC_title' => 'Tus <b>Dracoins</b> actuales',
		'cP_viewC_pression' => 'Presiona sobre el cuadro de abajo para actualizar nuevamente',

		// texts AJAX FILE [cpanel_viewPremium.php]
		'cP_viewP_title' => 'Historial de <b>suscripciones</b>',
		'text_presiona' => 'Presiona <b class="iFAs" onclick="loadPremiums()"><i class="fas fa-sync-alt"></i></b> para actualizar ',

		// texts AJAX FILE [cpanel_viewServer.php]
		'cP_viewS_th1' => 'Ranking',
		'cP_viewS_th2' => 'Servidor',
		'cP_viewS_th3' => 'Votos',
		'cP_viewS_th4' => 'Visitas',
		'cP_viewS_th5' => 'Estatus',
		'text_minimum' => 'Mínimo',
		'text_aprobado' => 'Aprobado',
		'text_noaprobado' => 'Sin aprobar',
		'cP_viewS_code_vote' => 'Código de votación',
		'cP_viewS_edit_server' => 'Editar servidor',
		'cP_viewS_edit_page_server' => 'Editar página servidor',
		'cP_viewS_upload_images' => 'Subir imagen',
		'cP_viewS_uploads_videos' => 'Subir Video',
		'cP_viewS_delete_server' => 'Eliminar servidor',

		// texts AJAX FILE [cpanel_viewShops.php]
		'cP_viewSH_title' => 'Historial de <b>transacciones</b>',
		'cP_viewSH_span' => 'Presiona <b class="iFAs" onclick="loadShops()"><i class="fas fa-sync-alt"></i></b> para actualizar',

		// texts AJAX FILE [database_pages.php]
		'db_pag1_trade' => 'Tradeable',
		'db_pag1_spawn' => 'Spawn',
		'text_no_found' => 'No hemos encontrado resultados.',
		'text_total_pages' => 'Total de páginas',	
		'text_go_pages' => 'Ir a la página',

		// texts AJAX FILE [database_report.php]
		'text_error_info' => 'Error en información',
		'text_error_images' => 'Error de imágenes',
		'text_error_content' => 'Error en contenido',
		'text_error_option' => 'Otra opción',
		'db_report_error_process' => 'Tu solicitud no puede ser procesada, recarga la página.',
		'db_report_finalice1' => 'Se ha reportado el ',
		'db_report_finalice2' => 'gracias',
		'db_report_error_report' => 'Selecciona una opción de reporte correcta.',
		'alert_error_info_refresh' => 'Ha ocurrido un error con la información, recargue página.',

		// texts AJAX FILE [delete_abwords.php]
		'del_ab_delete' => 'Se elimino tu anuncio.',
		'del_ab_error_delete' => 'El Anuncio ya había sido eliminado anteriormente.',

		// texts AJAX FILE [delete_server.php]
		'del_srv_delete' => 'Se elimino tu servidor',
		'del_srv_error_delete' => 'El servidor ya había sido eliminado anteriormente.',

		// texts AJAX FILE [delete_server_gallery.php]
		'del_srv_gal_delete' => 'Se eliminó la imagen.',

		// texts AJAX FILE [delete_server_trailer.php]
		'del_srv_video_delete' => 'Se eliminó el vídeo.',

		// texts AJAX FILE [modal_box_ab_delete.php]
		'modal_ab_confirm_action' => 'Una vez confirmada está acción, no hay vuelta atrás.',
		'modal_ab_error_info' => 'No hemos encontrado información',
		'modal_ab_delete_confirm' => '¿Estás seguro de eliminar esté anuncio?',
		'text_delete' => '¡ELIMINAR!',

		// texts AJAX FILE [modal_box_buys.php]
		'text_buy' => 'Compra',
		'text_details_completes' => 'Detalles completos de tu compra.',
		'modal_buy_aprobado' => 'Aprobado y entregado',
		'modal_buy_waiting' => 'En espera de validación',
		'modal_buy_transID' => 'Transacción ID',
		'modal_buy_total_pay' => 'Total pagado',
		'modal_buy_coins_obtenidos' => 'Dracoins obtenidos',
		'text_credits' => 'Créditos',
		'text_date_pay' => 'Fecha de pago',
		'text_status_pay' => 'Estatus de pago',

		// texts AJAX FILE [modal_box_code.php]
		'modal_code_codes' => 'Códigos del servidor',
		'modal_code_presion_field' => 'Presiona sobre el campo y se copiará el texto automaticamente.',
		'modal_code_waiting_admin' => 'Espere a que sea válidado por un administrador.',
		'modal_code_error1' => 'Su servidor aun no ha sido validado por un <b>Administrador</b>. 
		<br> Si ya pasaron más de 24 horas, puedes contactarnos mediante <a href="'.URL.'/contacto">un ticket</a>.',
		'modal_code_button_html' => 'Botones HTML',
		'modal_code_code_html' => 'Código HTML',

		// texts AJAX FILE [modal_box_draab.php]
		'text_need_login' => 'Debes estar conectado para realizar está acción.',
		'modal_drab_adquire' => 'Adquiere tu anuncio ahora mismo.',
		'modal_drab_time_required' => 'Tiempo requerido',
		'modal_drab_select_days' => '-- Selecciona los días --',
		'modal_drab_title_anun' => 'Titulo anuncio',
		'text_url' => 'Enlace',
		'modal_drab_server_anun' => 'Servidor a anunciar',
		'text_buy_now' => 'Comprar',
		'modal_drab_select_server' => '-- Selecciona un servidor --',

		// texts AJAX FILE [modal_box_editpage.php]
		'modal_editP_title' => 'Editar servidor',
		'modal_editP_infor' => 'Esta la información de la página de tu servidor.',
		'modal_editP_info_complete' => 'Información completa',
		'modal_editP_character' => 'caracteres, aparecerá en la visualización completa',
		'text_update' => 'Actualizar',

		// texts AJAX FILE [modal_box_editserver.php]
		'modal_editS_fields' => 'Deja los campos como están si no quieres aplicar cambios sobre ellos.',
		'modal_editS_view_banner' => 'Ver Banner actual',

		// texts AJAX FILE [modal_box_gallery_table.php]
		'modal_galtable_empty' => 'Tu galería está vacía.',

		// texts AJAX FILE [modal_box_gallerys.php]
		'modal_gall_can' => 'Puedes eliminar facilmente las imágenes que ya no deseas.',
		'modal_gall_upload_image' => 'Subir Imagen nueva',
		'modal_gall_image_for' => 'Imagen para galería',
		'text_upload' => 'Subir',
		'modal_gall_my' => 'Mi galería',

		// texts AJAX FILE [modal_box_reportdatabase.php]
		'modal_reportDB_error' => 'Ha ocurrido un error con tu solicitud.',
		'modal_reportDB_report_item' => 'Reportando item',
		'modal_reportDB_report_mob' => 'Reportando mob',
		'text_report_ya1' => 'Ya has reportado este',
		'text_report_ya2' => 'estamos revisando su reporte.',
		'modal_reportDB_confirm_report' => '¿Estás seguro de reportar este ',
		'modal_reportDB_report_by' => 'Reportar por',
		'text_select_option' => '--- Selecciona una opción ---',
		'text_more_details' => 'Más detalles',

		// texts AJAX FILE [modal_box_reportserver.php]
		'modal_reportSRV_report_ya1' => 'Ya has reportado este servidor, estamos tomando las acciones necesarias.',
		'modal_reportSRV_report_server' => 'Reportar servidor',
		'modal_reportSRV_confirm_report' => '¿Estás seguro de reportar este servidor?',
		'modal_reportSRV_refresh_page' => 'Le pedimos recargue la página del servidor para poder reportar.',

		// texts AJAX FILE [modal_box_server.php]
		'modal_boxS_delete' => 'Eliminar servidor',
		'modal_boxS_confirm' => '¿Estás seguro de eliminar toda la información almacenada de este servidor?',

		// texts AJAX FILE [modal_box_trailers.php]
		'modal_boxT_can' => 'Puedes eliminar facilmente los vídeos que ya no deseas.',
		'modal_boxT_upload_video' => 'Enlace directo Youtube',
		'text_add' => 'Añadir',
		'modal_boxT_my' => 'Mis Vídeos',

		// texts AJAX FILE [modal_box_trailers_table.php]
		'modal_tableT_no_videos' => 'No tienes vídeos aun en está sección.',

		// texts AJAX FILE [premium_buy.php]
		'premium_B_no_credits' => 'No tienes suficientes créditos, necesitas',
		'text_more' => 'más',
		'premium_B_renueve' => 'Se ha renovado tu suscripción.',
		'premium_B_suscription' => 'Te has suscrito a',
		'premium_B_error_process' => 'Ha ocurrido un error en el proceso, intenta más tarde.',
		'premium_B_error_plan' => 'Ha ocurrido un error con el plan seleccionado, recargue página.',

		// texts AJAX FILE [server_favorite.php]
		'srv_fav_add_favorite_ya' => 'Ya habías añadido a favoritos este servidor.',
		'srv_fav_add_success' => 'Se añadió a tus favoritos el servidor.',
		'srv_fav_delete_fav' => 'Se eliminó de tus favoritos el servidor.',
		'srv_fav_no_add_favorites' => 'No habías añadido a favoritos este servidor.',

		// texts AJAX FILE [server_report.php]
		'srv_rep_report_ya' => 'Parece que ya habías reportado a este servidor anteriormente.',
		'srv_rep_report_now' => 'Se reportó al servidor.',
		'srv_rep_select_option' => 'Selecciona una opción de reporte correcta.',

		// texts AJAX FILE [servers_chars.php]
		'srv_chars_color1' => 'Votos únicos',
		'srv_chars_color2' => 'Visitas únicas',
		'srv_chars_color3' => 'Visitas totales',

		// texts AJAX FILE [servers_top.php]
		'text_view_more' => 'Ver más',
		'srv_top_coloca' => 'Coloca tu servidor aquí',

		// texts AJAX FILE [servers_top_premium.php]
		'text_destacado' => 'Destacado',
		'text_anunciarme' => 'Anunciarme',

		// texts AJAX FILE [start_account.php]
		'star_acc_user_noregister' => 'El nombre de usuario no esta registrado.',
		'text_ban_full' => 'Tu cuenta ha sido bloqueada permanentemente',
		'text_ban_account' => 'Cuenta bloqueada. Te restan',
		'star_acc_max_try_today' => 'Has superado el máximo de intentos para iniciar sesión hoy',
		'star_acc_bad_pass1' => 'La contraseña es incorrecta, te quedan',
		'star_acc_bad_pass2' => 'intento(s).',
		'star_acc_login' => 'Estamos iniciando tu sesión, espera unos segundos.',
		'star_acc_no_valid' => 'Tu cuenta aun no ha sido validad por correo electrónico.',

		// texts AJAX FILE [update_server.php]
		'srv_upd_error1' => 'Ha ocurrido un error al editar el server, actualiza página.',
		'srv_upd_tags1' => 'Solo puedes colocar',
		'srv_upd_error_url' => 'El nombre del servidor, ya está siendo utilizado.',
		'srv_upd_descrip1' => 'El enlace del sitio web, ya está siendo utilizadoa por otro server.',
		'srv_upd_descrip2' => 'La descripción breve ha superado los',
		'srv_upd_update' => 'Tu servidor se ha actualizado.',

		// texts AJAX FILE [update_server_page.php]
		'srv_updP_update' => 'Se actualizo la información de la página de tu server.',
		'srv_updP_no_powers' => 'No tienes los privilegios para está acción.',

		// texts AJAX FILE [upload_server_gallery.php]
		'upload_srv_gal_error' => 'Tu galería está llena, elimina algunos elementos.',
		'upload_srv_gal_image_of' => 'Imagen de galería: ',
		'upload_srv_gal_add' => 'Imagen añadida correctamente.',

		// texts AJAX FILE [upload_server_trailer.php]
		'upload_srv_tra_error' => 'Tu galería de vídeos está llena, elimina algunos elementos.',
		'upload_srv_tra_embed_url' => 'Verifica sea una URL con EMBED.',
		'text_video_gallery' => 'Vídeo galería',
		'upload_srv_tra_add' => 'Vídeo añadido correctamente.',

		// texts AJAX FILE [xcopied.php]
		'text_copied' => 'Texto copiado al portapapeles.',

		// texts AJAX FILE [functions.php]
		'func_email1' => 'Mensaje electrónico',
		'func_email2' => 'Todos los derechos reservados a',
		'func_error_session' => 'Ha ocurrido un error con tu cuenta, inicia sesión nuevamente.',
		'func_vote_add' => 'Tu voto se ha añadido correctamente.',
		'func_error_try' => 'Ha ocurrido un error, intente más tarde.',
		
		// others texts
		'text_select_country' => 'Selecciona un país',
		'text_in' => 'en',

		// text to voting.php & vote_serv.php
		'vote_for' => 'Votar por',
		'vote_text1' => 'Tu botón estará listo en poco tiempo',
		'vote_omit1' => 'Omitir votación',
		'vote_add1' => 'Añadir voto',
		'vote_pre1' => 'Al votar aceptas el almacenamiento de tus datos, puedes leer nuestro <a href="'.URL.'/privacy">aviso de privacidad</a>.',
		'vote_text2' => 'Recomendaciones',
		
		// text to contact.php & (themes) contactanos.php
		'page_contact_00' => 'Contacto',
		'page_contact_01' => 'Contactanos',
		'page_contact_02' => 'Antes de enviar este formulario, lee <a href="'.URL.'/faqs">nuestras preguntas frecuentes</a>.',
		'page_contact_03' => 'Te estarás comunicando con <b>'.TITLE.'</b>, no con ningún servidor.',
		'page_contact_04' => 'Asunto',
		'page_contact_05' => 'Publicidad',
		'page_contact_06' => 'Errores en la web',
		'page_contact_07' => 'Errores en mi cuenta',
		'page_contact_08' => 'Reportar Cheater',
		'page_contact_09' => 'Reportar servidor',
		'page_contact_10' => 'Enviar noticias',
		'page_contact_11' => 'Sugerencias',
		'page_contact_12' => 'Problemas al acceder',
		'page_contact_13' => 'Otro',
		'page_contact_14' => 'Mensaje',
		'page_contact_15' => 'Máximo <b>1200</b> caracteres.',
		'page_contact_16' => 'Enviar mensaje',
		'page_contact_error1' => 'Ya tienes un ticket abierto de este tipo.',
		'page_contact_succes' => 'Tu mensaje se ha enviado correctamente, tendremos noticias pronto.',

		// text to page_pags.php & (themes) questionsf.php
		'page_fasq_01' => 'PREGUNTAS FRECUENTES',
		'page_fasq_02' => 'Encuentra las respuestas a las preguntas más comunes.',
		'page_fasq_03' => 'Si no encuentras la respuesta a tu pregunta, <a href="'.URL.'/contacto">contactanos</a>.',

		// text to page_tos.php & (themes) termines.php
		'page_tos_01' => 'Términos de uso',
		'page_tos_02' => 'Términos y condiciones: reviews',
		'page_tos_03' => 'Términos y condiciones: servidores',
		'page_tos_04' => 'Términos y servicios para players',
		'page_tos_05' => 'Términos y servicios para dueños de servidores',
		'page_tos_06' => 'Al registrarte como <b>Player</b>, aceptas lo siguiente:',
		'page_tos_07' => 'Al registrarte como <b>Dueño de servidor</b>, aceptas lo siguiente:',
		'page_tos_player' => TOS_P_ES,
		'page_tos_server' => TOS_S_ES,

		// text to page_pri.php & (themes) privacy.php
		'page_pri_01' => 'Política de privacidad',
		'page_pri_02' => 'Políticas de privacidad de '.TITLE.'',
		'page_pri_03' => 'La privacidad de nuestros visitantes es imporante',
		'page_pri_04' => 'Por favor tomese un momento ara leer nuestra política de privacidad.',
		'page_pri_05' => PRIVACY,

		// texto to modal_box_addreview.php
		'mb_addr_title1' => 'Reseña a:',
		'mb_addr_footer1' => '* Siendo <b>1</b> lo más bajo y <b>10</b> lo más alto, califique.',
		'mb_addr_h3server' => 'Ratings Servidor',
		'mb_addr_h3community' => 'Ratings Comunidad',
		'mb_addr_h3gms' => 'Ratings Game Master',
		'mb_addr_h3game' => 'Calificaciones del juego',
		'mb_addr_h3review' => 'Reseña (comentario)',
		'mb_addr_h3views' => 'Observaciones',
		'mb_addr_labela1' => 'Estabilidad',
		'mb_addr_labela2' => 'Disponibilidad',
		'mb_addr_labela3' => 'Amabilidad',
		'mb_addr_labela4' => 'Eventualidad',
		'mb_addr_labela5' => 'Amabilidad',
		'mb_addr_labela6' => 'Disponibilidad',
		'mb_addr_labela7' => 'Soporte',
		'mb_addr_labela8' => 'Economía',
		'mb_addr_labela9' => 'Guilds',
		'mb_addr_labela10' => 'Balance de Clases',
		'mb_addr_labelahow' => 'Meses jugando en el servidor',
		'mb_addr_labelaonl' => '¿Cuantos usuarios online promedio vez?',
		'mb_addr_small01' => '* Debe ser original, se eliminará el spam o textos copiados.',
		'mb_addr_maxtexta' => '* Tienes <b id="maxtextaSB">500</b> caracteres. ',
		'mb_addr_labeladona' => 'Nivel de donación',
		'mb_addr_dona01' => 'No disponible',
		'mb_addr_dona02' => 'Equilibrado',
		'mb_addr_dona03' => 'Desequlibrado',
		'mb_addr_donarule01' => 'La donación no da ventajas al jugador.',
		'mb_addr_donarule02' => 'Los no-donantes pueden competir con los donantes.',
		'mb_addr_donarule03' => 'La donación es requerida para poder competir.',
		'mb_addr_labelcustom' => 'Nivel Custom',
		'mb_addr_custom01' => 'No disponible',
		'mb_addr_custom02' => 'Regular',
		'mb_addr_custom03' => 'Exagerado',
		'mb_addr_customrule01' => 'No se observa nada o casi nada custom.',
		'mb_addr_customrule02' => 'Los custom no juegan un papel importante.',
		'mb_addr_customrule03' => 'Los custom son muy utilizados por los jugadores.',
		'mb_addr_sendform' => 'Enviar reseña',
		'mb_addr_error01' => 'Ha ocurrido un error con el servidor actual.',
		'mb_addr_error02' => 'Debes iniciar sesión para poder comentar.',

		// text to server_details.php (theme) (reviews)
		'servdetail_h1' => 'Server Ratings',
		'servdetail_rate01' => 'Rating basado en ',
		'servdetail_rate02' => 'comentarios de jugadores.',
		'servdetail_califpro' => 'Calificación promedio de',
		'servdetail_h1reviews' => 'Reseñas',
		'servdetail_writereview' => 'Escribir reseña',

		// text to report_review.php
		'reportrew_optioname' => 'Reporte de reseña',
		'reportrew_error01' => 'Gracias por tu reporte, en breve le daremos revisión.',
		
		// text to modal_box_reportreview.php
		'mbreportrew_error01' => 'Recargue la página e intente nuevamente.',
		'mbreportrew_error02' => 'Ya tenemos tu reporte, lo estamos analizando, muchas gracias.',
		'mbreportrew_error03' => 'Esta reseña ya no se encuentra disponible.',
		'mbreportrew_footser' => '* ¿Estás seguro de reportar esta reseña?',
		'mbreportrew_modalhe' => 'Reportar comentario del usuario',
		'mbreportrew_labelmo' => 'Describenos el motivo',
		'mbreportrew_reportr' => 'Reportar reseña',
		
		// text to add_review.php
		'addreview_error01' => 'Ya has comentado tu reseña en este servidor anteriormente.',
		'addreview_error02' => 'El servidor actualmente está desactivado o ha sido eliminado.',
		'addreview_error03' => 'Debes estar registrado para poder comentar tu reseña.',
		'addreview_error04' => 'Tu comentario no debe exceder los 500 caracteres.',
		'addreview_error05' => 'Las calificaciones son del 1 al 10.',
		'addreview_success' => 'Tu reseña ha sido añadida, actualizaremos la página.',
		
		// text to cpanel_table_buys.php (part2)
		'ctb_th3' => 'Monto',
		'ctb_th4' => 'Fecha',
		'ctb_th5' => 'Estatus',
		
		// text to cpanel_viewAds.php (part2)
		'cpva_serverad' => 'Banner imagen',
		'cpva_tdactions' => 'Acciones',

		// text to modal_box_draab.php (part2)
		'mbdab_asimage' => 'Imagen banner',
		'mbdab_titleh1' => 'Categoría',
		
		// text to servers_reviews.php (ajax)
		'srv_revw_commentno' => 'Comentario No.',
		'srv_revw_viewcalf' => 'Ver calificaciones',
		'srv_revw_pointers' => 'Puntuación:',
		'srv_revw_reportbut' => 'Reportar',
		'srv_revw_divflex1' => 'Servidor',
		'srv_revw_divflex2' => 'Comunidad',
		'srv_revw_divflex3' => 'GM',
		'srv_revw_divflex4' => 'Game-Play',
		
		// text to page_about.php 
		'pabout_title' => 'Nosotros',
		'pabout_titleh2' => 'Más que un <b>top</b>, somos una comunidad.',
		'pabout_small' => 'Explora con nosotros lo desconocido.',

		// text others translates DB
		'privacy_us' => PRIVACY_US,
		'about_us' => ABOUT_US,
		'about_es' => ABOUT_ES,
		'premium_examp_image' => 'Ejemplo atractivo de premium',
		'cpaddsrv_maxlevel_small' => 'Separa mediante / los niveles.',
		'cpaddsrv_maxlevel' => 'Max. Level',
		'cpaddsrv_episodio_small' => 'Coloca solo No. y titulo',
		'cpaddsrv_episodio_examp' => 'Ejem: 17.1 : Illusion',
		'cpaddsrv_episodio' => 'Episodio',
		'text_discord' => 'Discord',
		'more_about' => MORE_ABOUT,
		'contact_email' => 'También puedes escribirnos directamente al siguiente correo: <b><a href="mailto:'.EMAIL.'">'.EMAIL.'</a></b>',

		// news articles translates
		'pagenews_titlePage' => 'Blog',
		'pagenews_nav' => 'Blog',
		'pagenews_goblog' => 'Regresar al blog',
		'pagenews_sharedfb' => 'Compartir',
		'pagenews_continueread' => 'Continuar leyendo',

		// CR SB 
		'crsb_titleH' => 'CR StudiosBIT',
		'crsb_loader_ab' => 'DESACTIVA EL BLOQUEADOR DE ANUNCIOS PARA CONTINUAR NAVEGANDO.',

	);

?>