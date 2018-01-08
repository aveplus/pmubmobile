<?php 
	define('NANAPHP_ZONE','../');
	require_once NANAPHP_ZONE . 'app/Const.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/Config.php';
	require_once NANAPHP_ZONE . 'app/config/'.\app\RUNNING_APP.'.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Loader.php';
	\nanaPHP\Core\Application::runWebService( new \nanaPHP\HttpTools\Request() );

	//require_once NANAPHP_ZONE . 'vendor/nanaPHP/FileFormatter/JsonFile.php';
	//$content = \nanaPHP\Core\Application::runWebService(new \nanaPHP\HttpTools\Request());
	//$content = \nanaPHP\FileFormatter\JsonFile::json_encode($content);
	//print($content);
