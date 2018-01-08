<?php
	define('NANAPHP_APP_FILE_TYPE','.php');
	define('NANAPHP_APP_EOL',"\r\n");
	define('NANAPHP_APP_LAYOUT_FILE','layout.php');
	define('NANAPHP_APP_ERROR_VIEW_FILE','404.php');
	
	define('NANAPHP_CONTROLLER_PATH', \app\Config::APP_SRC_PATH . 'controller/');
	define('NANAPHP_VIEW_PATH', \app\Config::APP_SRC_PATH . 'view/');
	define('NANAPHP_MODEL_PATH', \app\Config::APP_SRC_PATH . 'model/');
	define('NANAPHP_SERVICE_PATH', \app\Config::APP_SRC_PATH . 'service/');
	
	define('NANAPHP_APP_RSRC_PATH', \app\PATH . \app\Config::APP_SRC_PATH . 'ressources/');
	define('NANAPHP_PUBLIC_PATH', NANAPHP_APP_RSRC_PATH . 'publics/');
	define('NANAPHP_WEB_URL_BASE', \app\APP_URL_BASE . 'web/');
	
	define('NANAPHP_DOWNLOAD_PATH', NANAPHP_APP_RSRC_PATH . 'download/');
	define('NANAPHP_UPLOAD_PATH', \app\Config::APP_SRC_PATH . 'upload/');
	define('NANAPHP_THROW_MSG_TITLE','Error | ' . \app\Config::APP_NAME);
	define('NANAPHP_THROW_MSG_LABEL','_ERROR-MESSAGE_');