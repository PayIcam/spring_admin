<?php 

// Paramètres de BDD
$_CONFIG['sql_host'] = "localhost";
$_CONFIG['sql_user'] = "root";
$_CONFIG['sql_pass'] = "";
$_CONFIG['sql_db']   = "icam_galadesicam";

// Chemin vers le serveur CAS (avec le / final)
$_CONFIG['cas_url'] = "";

// URL publique http(s) vers galadesicam (avec le / final)
$_CONFIG['galadesicam_url'] = "http://xxx/galadesicam/";

// Paramêtres de sauvegarde de la base de donnée
$_CONFIG['dropbox_save'] = array(
	'mail_dropbox' => 'xxx',
	'pwsd_dropbox' => 'xxx',
	'tmp_save_dir' => __DIR__.DS,
	'dropbox_dir' => 'saves_db',
	'path_to_mysqldump' => 'C:\wamp\bin\mysql\mysql5.6.12\bin\\',
	'db_to_save' => array(
		'icam_galadesicam' => array(
			'sql_host' => $_CONFIG['sql_host'],
			'sql_user' => $_CONFIG['sql_user'],
			'sql_pass' => $_CONFIG['sql_pass'],
			'sql_db'   => $_CONFIG['sql_db']
		)
	)
);