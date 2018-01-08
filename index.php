<?php   
	define('NANAPHP_ZONE',dirname(__FILE__).DIRECTORY_SEPARATOR);
	require_once NANAPHP_ZONE . 'app/Const.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/Config.php';
	require_once NANAPHP_ZONE . 'app/config/'.\app\RUNNING_APP.'.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Loader.php';
	$Firewall = new \nanaPHP\Security\Firewall();
	$Firewall->privilege->setDmz(array('Utilisateur/deconnexion'));
	// $Firewall->privilege->setAccess_level(\app\Config::PRIVILEGE_GREP_CONTROLLER);
	$Firewall->privilege->setAccess_level(\app\Config::PRIVILEGE_GREP_NULL);
	$Firewall->start('Utilisateur','login');
	\nanaPHP\Core\Application::run(new \nanaPHP\HttpTools\Request(), new \nanaPHP\HttpTools\Session(), $Firewall);
	