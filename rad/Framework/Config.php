<?php
	/** 
	* Fichier de configuration 
	* Vous ne serez pas amenez à modifier ce fichier, donc ne le faites jamais.
	* Dans le cas contraire les prochaines versions du framework et de l'ide s'intégreront difficilement à votre
	* travail actuel.
	*
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	
	define("APP_FILE_EXTENSION",".php");	// Application file extension

	/** BEGIN ************************ THESE WERE NOT UPDATABLE ********************************************************/
	define("FW_PATH","Framework/"); 										//Application framework directory
	define("FW_CONTROLLER_FILE",FW_PATH."Controller".APP_FILE_EXTENSION);	//Application framework controller file
	define("FW_VIEW_FILE",FW_PATH."View".APP_FILE_EXTENSION);				//Application framework view file
	define("FW_MODEL_FILE",FW_PATH."Model".APP_FILE_EXTENSION);				//Application framework model file
	define("FW_REQUEST_FILE",FW_PATH."Request".APP_FILE_EXTENSION);			//Application framework request file
	define("FW_APPLICATION_FILE",FW_PATH."Application".APP_FILE_EXTENSION);	//Application framework application file
	define("FW_DATABASE_FILE",FW_PATH."Database".APP_FILE_EXTENSION);		//Application framework application file
	define("FW_SESSION_FILE",FW_PATH."Session".APP_FILE_EXTENSION);			//Application framework session file
	define("FW_SERVICE_FILE",FW_PATH."Service".APP_FILE_EXTENSION);			//Application framework session file
	
	define("WWW_FOLDER",$_SERVER['DOCUMENT_ROOT']);							//Serveur documentRoot

	define("LOG_PATH","app/var/logs/");										//Application logs directory
	define("CACHE_PATH","app/var/caches/");									//Application caches directory	
	
	require_once FW_SERVICE_FILE;		/** Utilisable à n'importe quel niveau de vos projets */
	
	if (file_exists('app/config' . APP_FILE_EXTENSION)) 
		require_once 'app/config' . APP_FILE_EXTENSION;
	else 
		require_once 'Default.conf' . APP_FILE_EXTENSION;