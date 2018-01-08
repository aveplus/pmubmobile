<?php	
	/** 
	* Le contenu de ce fichier ne doit pas être modifier manuellement 
	* Au cas ou vous devez lz faire limitez cous à la seule ligne n°13
	*/

	/** Paramètres de configuration dépendants de l'environnement (Production / Développement) */
	include_once "conf.env" . APP_FILE_EXTENSION;
	
	define("THROW_MSG_TITLE", "Error | ".APP_ID);		
	/** BEGIN ************************ JAMAIS MODIFIER ********************************************************/
	define("APP_PATH",PROJECTS_PATH . APP_PROJECT_PATH);
	define("APP_CONTROLLER_PATH",APP_PATH."controller/");
	define("APP_VIEW_PATH",APP_PATH."view/");
	define("APP_MODEL_PATH",APP_PATH."model/");
	define("APP_RESSOURCES_PATH",PATH.APP_PATH."ressources/");	
	define("APP_LOG_PATH", LOG_PATH.APP_PROJECT_PATH);
	define("APP_CACHE_PATH",CACHE_PATH.APP_PROJECT_PATH);

	/** Autres fichiers inclus */
	include_once "conf.app" . APP_FILE_EXTENSION;	/** Fichier de configuration spécifique de l'app.. Vous devez le modifier à votre guise */
	include_once "conf.db" . APP_FILE_EXTENSION;		/** Fichier de configuration de la base de donnée */