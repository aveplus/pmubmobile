<?php
	/** 
	* Autoloder de nanaPHP
	* Vous ne serez pas amenez à modifier ce fichier, donc ne le faites jamais.
	* Dans le cas contraire les prochaines versions du framework et du RAD s'intégreront difficilement à votre
	* travail actuel.
	*
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	require_once NANAPHP_ZONE . "vendor/nanaPHP/Const.php";	

	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Security/Privilege.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Security/Firewall.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Security/Filter.php';
	
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Service/Service.php';
	
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/HttpTools/Session.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/HttpTools/Request.php';
	
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/Controller.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/View.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/Model.php';
	require_once NANAPHP_ZONE . 'vendor/nanaPHP/Core/Application.php';
	
	if(\app\Config::APP_USE_SGBD)
		require_once NANAPHP_ZONE . 'vendor/nanaPHP/DataConnector/Database.php';
		
	if(\app\Config::APP_USE_INI_FILE)
		require_once NANAPHP_ZONE . 'vendor/nanaPHP/FileFormatter/IniFile.php';

	if(\app\Config::APP_USE_JSON_FILE)
		require_once NANAPHP_ZONE . 'vendor/nanaPHP/FileFormatter/JsonFile.php';		