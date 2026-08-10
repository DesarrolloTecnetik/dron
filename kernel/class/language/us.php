<?php

	$User = new Users();
	global $UserID;
	
	// English language [Lenguaje en Inglés]
	$lang = array(

		// translate to navigations text [body.tpl]
		'nav_home'			=> 'Home',
		'nav_db'				=> 'Database',
		'nav_addserver'	=> 'Add server',
		'nav_premium'		=> 'Premium',
		'nav_ads'			=> 'Advertise',
		'nav_contact'		=> 'Contact',

		// lang and account user [body.tpl]
		'text_lang' => 'Language',
		'text_login' => 'Log in',
		'acc_avatar' => 'Avatar',
		'acc_hello' => 'Hi',
		'acc_credits' => 'Credits',
		'acc_welcome' => 'Welcome',
		'acc_avatar_default' => 'Avatar default',
		'acc_login' => 'Login now',
		'acc_myprofile' => 'My profile',
		'acc_panel' => 'My panel',
		'acc_premium' => 'Premium',
		'text_register' => 'Register',
		'text_recovery_pass' => 'Recovery password',
		'text_logout' => 'Log out',

		// texts footer [footer.tpl]
		'foot_box1' => 'More about us',
		'foot_box2' => 'Content about us at the bottom of the page.',
		'foot_box3' => 'Stay connected',
		'foot_box4' => 'Find us and follow us on our social networks.',
		'text_facebook' => 'Facebook',
		'text_instagram' => 'Instagram',
		'text_youtube' => 'Youtube',
		'text_twitter' => 'Twitter',
		'foot_fast_link' => 'Quick Links',
		'foot_fast_1' => 'Frequent questions',
		'foot_fast_2' => 'About us',
		'foot_fast_3' => 'Terms and Conditions: servers',
		'foot_fast_4' => 'Terms and Conditions: reviewers',
		'foot_fast_5' => 'Privacy policies',
		'foot_rights' => 'All rights reserved to <b>'.TITLE.'</b>',
		'foot_developed' => 'Developed by <a href="'.DEVELOPED_URL.'" target="_BLANK">'.DEVELOPED.'</a>',

		// anside texts [aside.tpl]
		'anside_1' => 'Advertisements',
		'anside_2' => 'Social networks',

		// texts themes [acceder.php]
		'acc_titleH2' => 'Log in',
		'acc_small' => 'Login to your registered account.',
		'acc_register_now' => 'If you don´t have your account yet, <a href="'.URL.'/register">register now</a>.',
		'text_user' => 'Username',
		'text_pass' => 'Password',
		'text_remember' => 'Remember my <b>account</b> permanently.',

		// texts themes [account_adwords.php]
		'ad_title' => 'Advertise on our site.',
		'ad_slogan' => 'It reaches the thousands of people who visit us daily.',
		'ad_add1' => 'Since',
		'ad_add2' => 'Adquire Ad',
		'text_anuncio' => 'Advert',

		// texts themes [account_suscription.php]
		'sus_title' => 'Find the right plan for you.',
		'sus_slogan' => 'If you don´t have credits yet, you can <a href="'.URL.'/panel#addCoins">buy them here</a>.',
		'sus_start' => 'Start now',
		'sus_renew' => 'Renew Premium',
		'sus_price_dro' => 'Cost in DragoCoins',
		'text_coin' => 'Dracoin - Coin of '.TITLE.'',
		'text_days' => 'days',

		// texts themes [confirmaciones.php]
		'conf_back' => 'Back to top',

		// texts themes items [database_tables.php]
		'dbt_nav_1' => 'All',
		'dbt_nav_2' => 'Consumables',
		'dbt_nav_3' => 'Cards',
		'dbt_nav_4' => 'Weapons',
		'dbt_nav_5' => 'Armors',
		'text_search' => 'Search...',
		'dbt_searching' => 'Searching',

		// texts themes mobs [database_tables_mobs.php]
		'dbt_nav_mob_1' => 'All',
		'dbt_nav_mob_2' => 'MvP',
		'dbt_nav_mob_3' => 'Normal',
		'dbt_nav_mob_4' => 'Boss',

		// texts themes items [database_view_item.php]
		'text_info_general' => 'General information',
		'text_price' => 'Sale price',
		'text_peso' => 'Weight',
		'text_type' => 'Type',
		'text_subtype' => 'Sub-type',
		'text_location' => 'Location',
		'text_compoun_in' => 'Composed in',
		'dbvi_can1' => 'Can be thrown',
		'dbvi_can2' => 'Can be traded',
		'dbvi_can3' => 'Storable storage',
		'dbvi_can4' => 'Storable cart',
		'dbvi_can5' => 'Can be sold',
		'dbvi_can6' => 'Shipping by mail',
		'dbvi_can7' => 'Sold by auction',
		'dbvi_can8' => 'Storable guild',
		'text_shared' => 'Share',
		'text_report' => 'Report',
		'dbvi_card_image' => 'Image of card',
		'text_description' => 'Description',
		'text_droped_by' => 'Dropped by',
		'text_setof' => 'Set of',
		'text_sell_by' => 'Sold by',
		'text_description_general' => 'General description',
		'dbvi_tab_1_script' => 'Script',
		'text_no_results' => 'Oops there are no results here.',
		'text_copied_navi' => 'Click on the <b>/navi</b> box to copy text automatically.',

		// texts themes mobs [database_view_mob.php]
		'text_sprite' => 'Sprite',
		'text_race' => 'Race',
		'text_size' => 'Size',
		'text_level' => 'Level',
		'text_maps' => 'Maps',
		'text_exp' => 'Exp',
		'text_elements' => 'Elements',
		'text_unknown' => 'Unknown',
		'text_subtype_item' => 'Item sub-type',
		'text_view_more_info' => 'See more information',
		'text_type_item' => 'Item type',
		'text_sub_map' => 'Sub-map',
		'text_amount_time' => 'Amount/Time',

		// texts themes [desconectar_cuenta.php]
		'logout_title' => 'You are about to close the connection to <b>'.TITLE.'</b>',
		'logout_small' => 'You can still cancel the disconnection <a href="'.URL.'/inicio">by clicking here</a>.',
		'logout_pulse' => '',

		// texts themes [errorpage.php]
		'text_404' => 'Error 404',
		'text_nofound' => 'Page not found',
		'text_nofound_span' => 'We could not find the requested page.',
		'text_back' => 'Go back',

		// texts themes [inicio.php]
		'text_order' => 'Order',
		'text_recents' => 'Recent',
		'text_votes' => 'Votes',
		'text_visits' => 'Visits',
		'text_version' => 'Version',
		'text_rates' => 'Rates',
		'text_country' => 'Country',

		// texts themes [panel_dashboard.php]
		'cp_nav_1' => 'Add server',
		'cp_nav_2' => 'My servers',
		'cp_nav_3' => 'Add ad',
		'cp_nav_4' => 'My ads',
		'cp_nav_5' => 'Buy Dracoins',
		'cp_nav_6' => 'My Dracoins',
		'cp_nav_7' => 'Shopping history',
		'cp_nav_8' => 'Premium history',

		// texts themes [registro.php]
		'reg_create' => 'Creat your account',
		'reg_title' => 'Complete all fields to continue with the registration.',
		'reg_small' => 'If you are already registered, <a href="'.URL.'/cuenta">log in</a>.',
		'reg_username' => 'Username',
		'reg_email' => 'Email',
		'reg_captcha' => 'Captcha',
		'text_write' => 'Write',
		'reg_loading' => 'Loading...',
		'reg_tos_1' => 'I have read and accepted the <a href="'.URL.'/tys" target="_BLANK">terms and conditions</a>.',
		'text_registrar' => 'Register',

		// texts themes [server_details.php]
		'sd_votos_all' => 'Total votes',
		'sd_register_in' => 'Registration in',
		'text_last_update' => 'Last update',
		'text_information' => 'Information',
		'text_galeria' => 'Gallery',
		'text_video' => 'Videos',
		'text_stadistics' => 'Statistics',
		'text_reviews' => 'Reviews',
		'sd_vote_for_server' => 'Vote for the server',
		'sd_visit_page' => 'Visit website',
		'sd_tab1_rates' => 'General rates',
		'text_exp_base' => 'Base Experience',
		'text_exp_job' => 'Job Experience',
		'text_exp_quest' => 'Quests Experience',
		'text_exp_drop' => 'Drop Experience',
		'text_drop_equip' => 'Drop Equip',
		'text_drop_card' => 'Drop Cards',
		'text_server_no_content' => 'This server has not yet uploaded content.',
		'text_load_info' => 'Loading information...',
		'text_today' => 'Today',
		'text_this_month' => 'This month',
		'text_votar' => 'Vote',

		// texts PATH files [account_validate.php]
		'accv_title' => 'Account validation',

		// texts PATH files [confirmation.php]
		'confirms_1_title' => 'Registration completed',
		'confirms_1_color' => 'Now you must activate your account',
		'confirms_1_send1' => 'Thank you for completing your registration! Now you can <a href="'.URL.'/cuenta"> login </a> and enjoy our content. <br> If you have your server, what are you waiting for to add it? <br> <br> You can check our <a href="'.URL.'/faqs"> FAQS </a> to answer some questions.',
		
		'confirms_2_title' => 'Account validation failed',
		'confirms_2_color' => 'Contact the administration',
		'confirms_2_send1' => 'The Token used has expired or has already been validated. <br> If you do not recognize this action, you can contact the administration, from <a href="'.URL.'/contacto">this link</a>, to review your case.',
		
		'confirms_3_title' => 'Account validated correctly!',
		'confirms_3_color' => 'Now you can login',
		'confirms_3_send1' => 'You have confirmed the email sent to your account, <a href="'.URL.'/inicio">now you can browse</a> and enjoy in our community. <br> <br> You can consult our <a href="'.URL.'/faqs">FAQS</a>, how to use all our tools and services.',

		'confirms_4_title' => '¡Welcome '.$User->user($UserID, 'user').'!',
		'confirms_4_color' => 'Browse in our community',
		'confirms_4_send1' => 'Your session has been started, from the <b> IP: '.$User->user($UserID, 'last_ip').'</b>. Any other valid session will close the previous ones. We recommend keeping your <b> access data</b> well to avoid fraud. <br> <br> To continue, go to <a href="'.URL.'/inicio">start</a> or press the button below.',

		'confirms_5_title' => 'See you soon!',
		'confirms_5_color' => 'We´ll be waiting for you',
		'confirms_5_send1' => 'We have closed all your connections to our site. Remember to <b> protect your data from third parties well</b>. <br> <br> Remember that for daily connections, you receive points that you can exchange for other articles soon.',

		'confirms_6_color' => 'Account temporarily locked',
		'confirms_6_send1' => 'Your account has been temporarily locked. If you think an error has occurred, please <a href="'.URL.'/contacto">contact</a> the administration. <br> <br> Your account will be automatically unlocked at',

		'confirms_7_title' => 'Sorry, purchase declined',
		'confirms_7_color' => 'Try again later',
		'confirms_7_send1' => 'At the moment we were unable to process the purchase of <b> Dracoins</b>. You can try again later with the same or another account. <br> <br> If the problem persists, contact <a href="'.URL.'/contacto">administration</a>.',

		'confirms_8_title' => 'You bought Dracoins',
		'confirms_8_color' => 'You have bought Dracoins',
		'confirms_8_send1' => 'Your purchase was successful, your <b> Dracoins</b> have been added to your account. <br> <br> You can buy more <b> Dracoins</b> from <a href="'.URL.'/panel#addCoins">this link</a> or check your <b> Dracoins</b> accessing <a href="'.URL.'/panel#viewShops">from here</a>.',

		'confirms_title' => 'Confirmation',

		// texts PATH files [cpanel.php]
		'cpanel_title' => 'My panel',

		// texts PATH files [db_view.php]
		'text_view' => 'Looking',
		'text_prefix' => 'Prefix',
		'text_element' => 'Element',
		'text_no_descrip' => 'Without description',
		'text_no_script' => 'No script',

		// texts PATH files [dra_adwords.php]
		'draad_title' => 'Advertisements',

		// texts PATH files [nofound.php]
		'nofound_title' => 'Page not found',

		// texts PATH files [server.php]
		'text_no_aviable' => 'Not available',
		'serv_flag' => 'Flag of',
		'text_yes' => 'Yes',
		'text_no' => 'No',
		'serv_title' => 'Server Details',

		// texts PATH files [suscription.php]
		'sus_title' => 'Subscriptions',

		// texts AJAX [ALERTS]
		'alert_rellena_campos' => 'Fill in all the fields.',
		'alert_error_session' => 'An error has occurred, log in again.',
		'alert_need_login' => 'You need to be connected for this function.',
		'alert_error_refresh' => 'An error has occurred, reload the page.',

		// texts AJAX FILE [add_acount.php]
		'alert_tos' => 'You must agree to terms and conditions.',
		'alert_no_aviable_user' => 'The username is not available.',
		'alert_no_badword' => 'You can´t use the word',
		'alert_no_aviable_email' => 'Email is already being used.',
		'add_acc_email1' => 'Thank you for <b> creating your account</b> with us. We are a community that seeks to offer you a <b> premium</b> service. Your username is',
		'add_acc_email2' => 'To complete your registration, please <b> click on the</b> button below to verify your identity.',
		'text_verifica_account' => 'Verify account',
		'add_acc_email4' => 'If the button does not work, press copy or press this link',
		'add_acc_email5' => '<h1 class="h1EMAIL">You didn´t make this register?</h1>
		<div class="add_det">
			- Do not do anything, in 24 hours the data registered with this email will be deleted.
		</div>',
		'add_acc_hello' => 'Your account has been created successfully.',
		'add_acc_create' => 'Confirmation of registration',
		'alert_bad_captcha' => 'The Captcha code is incorrect.',
		'alert_error_captcha' => 'An error has occurred, reload the Captcha.',
		'alert_pass_characters' => 'The password must be between 8 and 45 characters.',
		'alert_error_pass1y2' => 'Passwords do not match.',
		'alert_error_user_name' => 'The username must be between 8 and 35 characters.',
		'alert_error_email_fake' => 'You must enter a valid email.',

		// texts AJAX FILE [add_adsword.php]
		'alert_error_server_select' => 'An error has occurred with the selected server, refresh page.',
		'alert_image_limit' => 'The image exceeds the allowed size.',
		'alert_image_peso' => 'Select an image with a format allowed no larger than',
		'alert_image_weigth' => 'Select an image with the allowed format.',
		'alert_image_type' => 'Select an image file.',
		'alert_image_need' => 'You have to upload an image.',
		'add_ads_finalice_shop' => 'Purchase made correctly.',
		'alert_no_dracoins1' => 'You don´t have the necessary DraCoins, buy',
		'alert_no_dracoins2' => 'DraCoins more',
		'alert_error_ads_price' => 'An error has occurred with the ad and the selected price.',
		'alert_error_ads_time' => 'An error has occurred with the selected time.',
		'alert_error_ads_refresh' => 'An error has occurred with the selected ad, reload page.',

		// texts AJAX FILE [add_server.php]
		'alert_server1' => 'You can only have',
		'alert_server2' => 'servers',
		'alert_tags1' => 'You can only place',
		'alert_server_name_error1' => 'The name of the server is already being used.',
		'alert_server_name_error2' => 'The server name should only have a-z/A-Z/0-9/- as characters.',
		'alert_server_name_url' => 'The website link is already being used.',
		'alert_server_descrip1' => 'The brief description has exceeded',
		'alert_server_detail1' => 'The detailed description has exceeded ',
		'text_characters' => 'characters',
		'add_server_finalice' => 'Your server has been added, wait to be authorized.',
		'alert_select_rates' => 'Select the correct Rates.',
		'alert_select_mode' => 'Select a correct Game Mode.',
		'alert_select_security' => 'Select a correct Security System.',
		'alert_select_emu' => 'Select an emulator correctly.',
		'alert_select_country' => 'Select a country correctly.',

		// texts AJAX FILE [change_lang.php]
		'changeLang_alert' => 'The language of the site has been updated, wait a moment.',

		// texts AJAX FILE [close_account.php]
		'close_acc_logout' => 'We are closing your session right now.',
		'close_acc_logout_error' => 'You were already disconnected, reload the page.',

		// texts AJAX FILE [cpanel_addCoins.php]
		'cpanel_addC_title' => 'Buy your <b>Dracoins</b>',
		'cpanel_addC_h2' => 'What are the <b>Dracoins</b>?',
		'cpanel_addC_p1' => 'Are the official currencies (credits) of <b>'.TITLE.'</b>, all products and services with cost, will be acquired by paying only with these.',
		'cpanel_addC_p2' => 'You can identify them with this <b> icon</b>: <img src = "'.URL.'/assets/images/dracoin.png">',
		'cpanel_addC_p3' => 'For every <b> 1 USD</b> you will be given <b>'.CASH_PER_USD.' Dracoin</b>. ',
		'cpanel_addC_buy_credits' => 'Buy Credits',
		'cpanel_addC_shoping_proccess' => 'Purchase in process ...',

		// texts AJAX FILE [cpanel_addServer.php]
		'cpanel_addServer_title' => 'Add your server',
		'text_datos_generales' => 'General data',
		'cpanel_addServer_url' => 'Link',
		'cpanel_addServer_url1' => 'Link to main page',
		'cpanel_addServer_name1' => 'Name of your server, just <b>a-Z</b>, <b> 0-9</b>, <b> hyphen (-)</b>',
		'cpanel_addServer_image' => 'Banner image',
		'text_drop_here' => 'Select or drag the file here',
		'text_format' => 'Format',
		'text_only' => 'only',
		'text_max' => 'Maximum',
		'cpanel_addServer_h3' => 'Main features',
		'text_accept_reviews' => 'Accept reviews',
		'text_select' => '--- Choose ---',
		'cpanel_addServer_comments_des' => 'Comments or reviews on your server',
		'cpanel_addServer_mode_game' => 'Game mode of your server',
		'cpanel_addServer_place' => 'Server location',
		'cpanel_addServer_rates1' => 'Generalize the rates of your server',
		'cpanel_addServer_emu' => 'Emulator used',
		'text_emulator' => 'Emulator',
		'text_security' => 'Security',
		'cpanel_addServer_security' => 'Client security used',
		'cpanel_addServer_rates_title' => 'Rates of your server',
		'cpanel_addServer_insert_per' => 'Insert values (%)',
		'text_info_extra' => 'Extra information',
		'text_small_descrip' => 'Brief description',
		'cpanel_addServer_max_chars' => 'Maximum 240 characters, will appear as main text in the Start Top',
		'text_large_descrip' => 'Detailed description',
		'text_name' => 'Name',
		
		'cpanel_addServer_chars_view' => 'will appear in full view',
		'text_data_premium' => 'Premium Data',
		'cpanel_addServer_tags1' => 'Separate by comma or enter, maximum 10 tags (tags available below)',
		'text_url_to' => 'Link to ',
		'text_cancel' => 'Cancel',
		'text_continue' => 'Continue',
		'text_files_selects' => 'selected files',

		// texts AJAX FILE [cpanel_table_buys.php]
		'cP_tableB_th1' => 'Paypal ID',
		'cP_tableB_th2' => 'Dracoins',
		'cP_tableB_th3' => 'Amount',
		'cP_tableB_th4' => 'Date',
		'cP_tableB_th5' => 'Status',
		'text_actions' => 'Actions',
		'text_view_details' => 'View details',
		'text_no_information' => 'We have no information at the moment.',

		// texts AJAX FILE [cpanel_table_premiums.php]
		'cP_tableP_th1' => 'Premium Subscription',
		'cP_tableP_th2' => 'Cost at DragoCoins',
		'cP_tableP_th3' => 'Date of purchase',
		'cP_tableP_th4' => 'Purchase days',
		'cP_tableP_th5' => 'End date',
		'cP_tableP_th6' => 'Time remaining',
		'cP_tableP_th7' => 'Status',
		'cP_tableP_shop_more' => 'Buy more',

		// texts AJAX FILE [cpanel_viewAds.php]
		'cP_viewA_th1' => 'Type Ad',
		'cP_viewA_th2' => 'Expires',
		'cP_viewA_th3' => 'Server',
		'cP_viewA_th4' => 'Clicks',
		'cP_viewA_th5' => 'Status',
		'text_image_ads' => 'Image Ad',
		'text_active' => 'Active',
		'text_finalice' => 'Finalized',
		'text_view_image' => 'View image',
		'text_delete_ads' => 'Remove Ad',

		// texts AJAX FILE [cpanel_viewCoins.php]
		'cP_viewC_title' => 'Your current <b> Dracoins</b>',
		'cP_viewC_pression' => 'Click on the box below to update again',

		// texts AJAX FILE [cpanel_viewPremium.php]
		'cP_viewP_title' => 'History of <b> subscriptions</b>',
		'text_presiona' => 'Press <b class="iFAs" onclick="loadPremiums()"><i class="fas fa-sync-alt"></i></b> to update ',

		// texts AJAX FILE [cpanel_viewServer.php]
		'cP_viewS_th1' => 'Ranking',
		'cP_viewS_th2' => 'Server',
		'cP_viewS_th3' => 'Votes',
		'cP_viewS_th4' => 'Visits',
		'cP_viewS_th5' => 'Status',
		'text_minimum' => 'Minimum',
		'text_aprobado' => 'Approved',
		'text_noaprobado' => 'Not approved',
		'cP_viewS_code_vote' => 'Voting code',
		'cP_viewS_edit_server' => 'Edit Server',
		'cP_viewS_edit_page_server' => 'Edit Server Page',
		'cP_viewS_upload_images' => 'Upload Image',
		'cP_viewS_uploads_videos' => 'Upload a video',
		'cP_viewS_delete_server' => 'Remove server',

		// texts AJAX FILE [cpanel_viewShops.php]
		'cP_viewSH_title' => 'History of <b> transactions</b>',
		'cP_viewSH_span' => 'Press <b class="iFAs" onclick="loadShops()"><i class="fas fa-sync-alt"></i></b> to update ',

		// texts AJAX FILE [database_pages.php]
		'db_pag1_trade' => 'Tradeable',
		'db_pag1_spawn' => 'Spawn',
		'text_no_found' => 'We haven´t found anything.',
		'text_total_pages' => 'Total pages',	
		'text_go_pages' => 'Go to page',

		// texts AJAX FILE [database_report.php]
		'text_error_info' => 'Information error',
		'text_error_images' => 'Image error',
		'text_error_content' => 'Content Error',
		'text_error_option' => 'Another option',
		'db_report_error_process' => 'Your request cannot be processed, reload the page.',
		'db_report_finalice1' => 'Has been reported the ',
		'db_report_finalice2' => 'Thank you',
		'db_report_error_report' => 'Select a correct report option.',
		'alert_error_info_refresh' => 'An error has occurred with the information, reload page.',

		// texts AJAX FILE [delete_abwords.php]
		'del_ab_delete' => 'Your ad has been deleted.',
		'del_ab_error_delete' => 'The Announcement had already been removed previously.',

		// texts AJAX FILE [delete_server.php]
		'del_srv_delete' => 'Your server was deleted.',
		'del_srv_error_delete' => 'The server had already been removed previously.',

		// texts AJAX FILE [delete_server_gallery.php]
		'del_srv_gal_delete' => 'Image deleted.',

		// texts AJAX FILE [delete_server_trailer.php]
		'del_srv_video_delete' => 'Video was deleted.',

		// texts AJAX FILE [modal_box_ab_delete.php]
		'modal_ab_confirm_action' => 'Once this action is confirmed, there is no going back.',
		'modal_ab_error_info' => 'We have not found information',
		'modal_ab_delete_confirm' => 'Are you sure to remove this ad?',
		'text_delete' => '¡REMOVE!',

		// texts AJAX FILE [modal_box_buys.php]
		'text_buy' => 'Buy',
		'text_details_completes' => 'Full details of your purchase.',
		'modal_buy_aprobado' => 'Approved and delivered',
		'modal_buy_waiting' => 'Awaiting validation',
		'modal_buy_transID' => 'Transaction ID',
		'modal_buy_total_pay' => 'Total paid',
		'modal_buy_coins_obtenidos' => 'Dracoins obtained',
		'text_credits' => 'Credits',
		'text_date_pay' => 'Payment date',
		'text_status_pay' => 'Payment status',

		// texts AJAX FILE [modal_box_code.php]
		'modal_code_codes' => 'Server codes',
		'modal_code_presion_field' => 'Click on the field and the text will be copied automatically.',
		'modal_code_waiting_admin' => 'Wait for it to be validated by an administrator.',
		'modal_code_error1' => 'Your server has not yet been validated by an <b> Administrator</b>.
		<br> If more than 24 hours have passed, you can contact us using <a href="'.URL.'/contacto">a ticket</a>. ',
		'modal_code_button_html' => 'HTML buttons',
		'modal_code_code_html' => 'HTML code',

		// texts AJAX FILE [modal_box_draab.php]
		'text_need_login' => 'You must be logged in to perform this action.',
		'modal_drab_adquire' => 'Acquire your ad right now.',
		'modal_drab_time_required' => 'Time required',
		'modal_drab_select_days' => '--- Select the days ---',
		'modal_drab_title_anun' => 'Ad title',
		'text_url' => 'Link',
		'modal_drab_server_anun' => 'Server to advertise',
		'text_buy_now' => 'Buy',
		'modal_drab_select_server' => '--- Select a server ---',

		// texts AJAX FILE [modal_box_editpage.php]
		'modal_editP_title' => 'Edit server',
		'modal_editP_infor' => 'This is the information on your server´s page.',
		'modal_editP_info_complete' => 'Full information',
		'modal_editP_character' => 'characters, will appear in full view',
		'text_update' => 'Update',

		// texts AJAX FILE [modal_box_editserver.php]
		'modal_editS_fields' => 'Leave the fields as they are if you don´t want to apply changes to them.',
		'modal_editS_view_banner' => 'View current Banner',

		// texts AJAX FILE [modal_box_gallery_table.php]
		'modal_galtable_empty' => 'Your gallery is empty.',

		// texts AJAX FILE [modal_box_gallerys.php]
		'modal_gall_can' => 'You can easily delete the images you no longer want.',
		'modal_gall_upload_image' => 'Upload New Image',
		'modal_gall_image_for' => 'Image for gallery',
		'text_upload' => 'Upload',
		'modal_gall_my' => 'My gallery',

		// texts AJAX FILE [modal_box_reportdatabase.php]
		'modal_reportDB_error' => 'An error occurred with your request.',
		'modal_reportDB_report_item' => 'Reporting item',
		'modal_reportDB_report_mob' => 'Reporting mob',
		'text_report_ya1' => 'You have already reported this',
		'text_report_ya2' => 'we are reviewing your report.',
		'modal_reportDB_confirm_report' => 'Are you sure to report this',
		'modal_reportDB_report_by' => 'Report by',
		'text_select_option' => '--- Select an option ---',
		'text_more_details' => 'More details',

		// texts AJAX FILE [modal_box_reportserver.php]
		'modal_reportSRV_report_ya1' => 'You have already reported this server, we are taking the necessary actions.',
		'modal_reportSRV_report_server' => 'Report server',
		'modal_reportSRV_confirm_report' => 'Are you sure to report this server?',
		'modal_reportSRV_refresh_page' => 'We ask you to reload the server page to be able to report.',

		// texts AJAX FILE [modal_box_server.php]
		'modal_boxS_delete' => 'Delete server',
		'modal_boxS_confirm' => 'Are you sure you delete all stored information from this server?',

		// texts AJAX FILE [modal_box_trailers.php]
		'modal_boxT_can' => 'You can easily delete videos that you no longer want.',
		'modal_boxT_upload_video' => 'Youtube direct link',
		'text_add' => 'Add',
		'modal_boxT_my' => 'My Videos',

		// texts AJAX FILE [modal_box_trailers_table.php]
		'modal_tableT_no_videos' => 'You don´t have videos yet in this section.',

		// texts AJAX FILE [premium_buy.php]
		'premium_B_no_credits' => 'You don´t have enough credits, you need',
		'text_more' => 'more',
		'premium_B_renueve' => 'Your subscription has been renewed.',
		'premium_B_suscription' => 'You have subscribed to',
		'premium_B_error_process' => 'An error has occurred in the process, try again later.',
		'premium_B_error_plan' => 'An error has occurred with the selected plan, reload page.',

		// texts AJAX FILE [server_favorite.php]
		'srv_fav_add_favorite_ya' => 'You had already added this server to favorites.',
		'srv_fav_add_success' => 'The server was added to your favorites.',
		'srv_fav_delete_fav' => 'The server was removed from your favorites.',
		'srv_fav_no_add_favorites' => 'You had not added this server to favorites.',

		// texts AJAX FILE [server_report.php]
		'srv_rep_report_ya' => 'Looks like you´ve previously reported to this server before.',
		'srv_rep_report_now' => 'It was reported to the server.',
		'srv_rep_select_option' => 'Select a correct report option.',

		// texts AJAX FILE [servers_chars.php]
		'srv_chars_color1' => 'Unique votes',
		'srv_chars_color2' => 'Unique visits',
		'srv_chars_color3' => 'Total visits',

		// texts AJAX FILE [servers_top.php]
		'text_view_more' => 'See more',
		'srv_top_coloca' => 'Place your server here',

		// texts AJAX FILE [servers_top_premium.php]
		'text_destacado' => 'Featured',
		'text_anunciarme' => 'Advertise me',

		// texts AJAX FILE [start_account.php]
		'star_acc_user_noregister' => 'The username is not registered.',
		'text_ban_full' => 'Your account has been permanently blocked',
		'text_ban_account' => 'Account locked. They subtract you ',
		'star_acc_max_try_today' => 'You have exceeded the maximum number of attempts to log in today',
		'star_acc_bad_pass1' => 'The password is incorrect, you have left',
		'star_acc_bad_pass2' => 'attempt(s).',
		'star_acc_login' => 'We are logging in, wait a few seconds.',
		'star_acc_no_valid' => 'Your account has not yet been validated by email.',

		// texts AJAX FILE [update_server.php]
		'srv_upd_error1' => 'An error occurred while editing the server, refresh page.',
		'srv_upd_tags1' => 'You can only place',
		'srv_upd_error_url' => 'The name of the server is already being used.',
		'srv_upd_descrip1' => 'The website link is already being used by another server.',
		'srv_upd_descrip2' => 'The brief description has exceeded',
		'srv_upd_update' => 'Your server has been updated.',

		// texts AJAX FILE [update_server_page.php]
		'srv_updP_update' => 'Your server page information was updated.',
		'srv_updP_no_powers' => 'You do not have the privileges for this action.',

		// texts AJAX FILE [upload_server_gallery.php]
		'upload_srv_gal_error' => 'Your gallery is full, delete some items.',
		'upload_srv_gal_image_of' => 'Image of gallery: ',
		'upload_srv_gal_add' => 'Image added successfully.',

		// texts AJAX FILE [upload_server_trailer.php]
		'upload_srv_tra_error' => 'Your video gallery is full, delete some items.',
		'upload_srv_tra_embed_url' => 'Verify a URL with EMBED.',
		'text_video_gallery' => 'Video gallery',
		'upload_srv_tra_add' => 'Video successfully added.',

		// texts AJAX FILE [xcopied.php]
		'text_copied' => 'Text copied to clipboard.',

		// texts AJAX FILE [functions.php]
		'func_email1' => 'Electronic message',
		'func_email2' => 'All rights reserved to',
		'func_error_session' => 'An error has occurred with your account, log in again.',
		'func_vote_add' => 'Your vote has been successfully added.',
		'func_error_try' => 'An error has occurred, try again later.',
		
		// others texts
		'text_select_country' => 'Select a country',
		'text_in' => 'in',

		// text to voting.php & vote_serv.php
		'vote_for' => 'Vote for',
		'vote_text1' => 'Your button will be ready in no time',
		'vote_omit1' => 'Skip voting',
		'vote_add1' => 'Add vote',
		'vote_pre1' => 'By voting you accept the storage of your data, you can read our <a href="'.URL.'/privacy">privacy notice</a>.',
		'vote_text2' => 'Recommendations',
		
		// text to contact.php & (themes) contactanos.php
		'page_contact_00' => 'Contact',
		'page_contact_01' => 'Contact us',
		'page_contact_02' => 'Before submitting this form, read <a href="'.URL.'/faqs">our frequently asked questions</a>.',
		'page_contact_03' => 'You will be communicating with <b>'.TITLE.'</b>, not with any server.',
		'page_contact_04' => 'Subject',
		'page_contact_05' => 'Advertising',
		'page_contact_06' => 'Errors on the web',
		'page_contact_07' => 'Errors in my account',
		'page_contact_08' => 'Report Cheater',
		'page_contact_09' => 'Report server',
		'page_contact_10' => 'Send news',
		'page_contact_11' => 'Suggestions',
		'page_contact_12' => 'Problems accessing',
		'page_contact_13' => 'Other',
		'page_contact_14' => 'Message',
		'page_contact_15' => 'Maximum <b> 1200 </b> characters.',
		'page_contact_16' => 'Send message',
		'page_contact_error1' => 'You already have an open ticket of this type.',
		'page_contact_succes' => 'Your message has been sent correctly, we will have news soon.',

		// text to page_pags.php & (themes) questionsf.php
		'page_fasq_01' => 'FREQUENTLY ASKED QUESTIONS',
		'page_fasq_02' => 'Find the answers to the most common questions.',
		'page_fasq_03' => 'If you can´t find the answer to your question, <a href="'.URL.'/contacto"> contact us </a>.',

		// text to page_tos.php & (themes) termines.php
		'page_tos_01' => 'Terms of use',
		'page_tos_02' => 'Terms and conditions: reviews',
		'page_tos_03' => 'Terms and conditions: servers',
		'page_tos_04' => 'Terms and services for players',
		'page_tos_05' => 'Terms and services for server owners',
		'page_tos_06' => 'By registering as <b>Player</b>, you agree to the following:',
		'page_tos_07' => 'By registering as <b>Server Owner</b>, you agree to the following:',
		'page_tos_player' => TOS_P_US,
		'page_tos_server' => TOS_S_US,

		// text to page_pri.php & (themes) privacy.php
		'page_pri_01' => 'Privacy policy',
		'page_pri_02' => 'Privacy policies of' .TITLE. '',
		'page_pri_03' => 'The privacy of our visitors is important',
		'page_pri_04' => 'Please take a moment to read our privacy policy.',
		'page_pri_05' => PRIVACY_US,

		// texto to modal_box_addreview.php
		'mb_addr_title1' => 'Review to:',
		'mb_addr_footer1' => '* With <b>1</b> being the lowest and <b>10</b> the highest, qualify.',
		'mb_addr_h3server' => 'Ratings Server',
		'mb_addr_h3community' => 'Ratings Community',
		'mb_addr_h3gms' => 'Ratings Game Master',
		'mb_addr_h3game' => 'Game ratings',
		'mb_addr_h3review' => 'Review (comment)',
		'mb_addr_h3views' => 'Observations',
		'mb_addr_labela1' => 'Stability',
		'mb_addr_labela2' => 'Availability',
		'mb_addr_labela3' => 'Kindness',
		'mb_addr_labela4' => 'Eventuality',
		'mb_addr_labela5' => 'Kindness',
		'mb_addr_labela6' => 'Availability',
		'mb_addr_labela7' => 'Support',
		'mb_addr_labela8' => 'Economy',
		'mb_addr_labela9' => 'Guilds competition',
		'mb_addr_labela10' => 'Class Balance',
		'mb_addr_labelahow' => 'Months playing on the server',
		'mb_addr_labelaonl' => 'How many users online average time?',
		'mb_addr_small01' => '* Must be original, spam or copied texts will be deleted.',
		'mb_addr_maxtexta' => '* You have <b id="maxtextaSB">500</b> characters. ',
		'mb_addr_labeladona' => 'Donation level',
		'mb_addr_dona01' => 'Not available',
		'mb_addr_dona02' => 'Balanced',
		'mb_addr_dona03' => 'Unbranched',
		'mb_addr_donarule01' => 'Donation does not give advantages to the player.',
		'mb_addr_donarule02' => 'Non-donors can compete with donors.',
		'mb_addr_donarule03' => 'Donation is required to compete.',
		'mb_addr_labelcustom' => 'Custom level',
		'mb_addr_custom01' => 'Not available',
		'mb_addr_custom02' => 'Regular',
		'mb_addr_custom03' => 'Exaggerated',
		'mb_addr_customrule01' => 'Nothing or almost nothing custom is observed.',
		'mb_addr_customrule02' => 'Customs do not play an important role.',
		'mb_addr_customrule03' => 'Customs are widely used by players.',
		'mb_addr_sendform' => 'Send review',
		'mb_addr_error01' => 'An error has occurred with the current server.',
		'mb_addr_error02' => 'You must be logged in to be able to comment.',

		// text to server_details.php (theme) (reviews)
		'servdetail_h1' => 'Server Ratings',
		'servdetail_rate01' => 'Rating based on',
		'servdetail_rate02' => 'player comments.',
		'servdetail_califpro' => 'Average rating of',
		'servdetail_h1reviews' => 'Reviews',
		'servdetail_writereview' => 'Write review',

		// text to report_review.php
		'reportrew_optioname' => 'Review report',
		'reportrew_error01' => 'Thank you for your report, we will review it shortly.',
		
		// text to modal_box_reportreview.php
		'mbreportrew_error01' => 'Reload the page and try again.',
		'mbreportrew_error02' => 'We already have your report, we are analyzing it, thank you very much.',
		'mbreportrew_error03' => 'This review is no longer available.',
		'mbreportrew_footser' => '* Are you sure to report this review?',
		'mbreportrew_modalhe' => 'Report user comment',
		'mbreportrew_labelmo' => 'Describe the reason',
		'mbreportrew_reportr' => 'Report review',
		
		// text to add_review.php
		'addreview_error01' => 'You have already commented on your review on this server before.',
		'addreview_error02' => 'The server is currently disabled or has been deleted.',
		'addreview_error03' => 'You must be registered to comment on your review.',
		'addreview_error04' => 'Your comment should not exceed 500 characters.',
		'addreview_error05' => 'Grades are from 1 to 10.',
		'addreview_success' => 'Your review has been added, we will update the page.',
		
		// text to cpanel_table_buys.php (part2)
		'ctb th3' => 'Amount', 
		'ctb th4' => 'Date', 
		'ctb th5' => 'Status',
		
		// text to cpanel_viewAds.php (part2)
		'cpva_serverad' => 'Banner image',
		'cpva_tdactions' => 'Actions',

		// text to modal_box_draab.php (part2)
		'mbdab_asimage' => 'Image banner',
		'mbdab_titleh1' => 'Category',
		
		// text to servers_reviews.php (ajax)
		'srv_revw_commentno' => 'Comment No.',
		'srv_revw_viewcalf' => 'See ratings',
		'srv_revw_pointers' => 'Score:',
		'srv_revw_reportbut' => 'Report',
		'srv_revw_divflex1' => 'Server',
		'srv_revw_divflex2' => 'Community',
		'srv_revw_divflex3' => 'GM',
		'srv_revw_divflex4' => 'Game-Play',
		
		// text to page_about.php 
		'pabout_title' => 'About us',
		'pabout_titleh2' => 'More than a <b>top</b>, we are a community.',
		'pabout_small' => 'Explore with us the unknown.',	

		// text others translates DB
		'privacy_us' => PRIVACY_US,
		'about_us' => ABOUT_US,
		'about_es' => ABOUT_ES,
		'premium_examp_image' => 'Attractive example of premium',
		'cpaddsrv_maxlevel_small' => 'Separate through/levels.',
		'cpaddsrv_maxlevel' => 'Max. Level ',
		'cpaddsrv_episodio_small' => 'Place only No. and title',
		'cpaddsrv_episodio_examp' => 'Example: 17.1: Illusion',
		'cpaddsrv_episodio' => 'Episode',
		'text_discord' => 'Discord',
		'more_about' => MORE_ABOUT_US,
		'contact_email' => 'You can also write directly to the following email: <b><a href="mailto:'.EMAIL.'">'.EMAIL.'</a></b>',

		// news articles translates
		'pagenews_titlePage' => 'Blog',
		'pagenews_nav' => 'Blog',
		'pagenews_goblog' => 'Back to blog',
		'pagenews_sharedfb' => 'Share',
		'pagenews_continueread' => 'Continue reading',

		// CR SB 
		'crsb_titleH' => 'CR StudiosBIT',
		'crsb_loader_ab' => 'DEACTIVATE THE AD BLOCKER TO CONTINUE BROWSING.',

	);

?>