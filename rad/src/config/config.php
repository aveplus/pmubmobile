<?php	
	/** 
	* Le contenu de ce fichier ne doit pas être modifier manuellement 
	* Au cas ou vous devez lz faire limitez cous à la seule ligne n°13
	*/

	/** Paramètres de configuration dépendants de l'environnement (Production / Développement) */
	include_once "conf.env" . APP_FILE_EXTENSION;
	
	define("THROW_MSG_TITLE", "Error | ".APP_ID);		
	
	//JAMAIS MODIFIER 
	define("APP_PATH",PROJECTS_PATH . APP_PROJECT_PATH);
	define("APP_CONTROLLER_PATH",APP_PATH."controller/");
	define("APP_VIEW_PATH",APP_PATH."view/");
	define("APP_MODEL_PATH",APP_PATH."model/");
	define("APP_RESSOURCES_PATH",PATH.APP_PATH."ressources/");	
	define("APP_LOG_PATH", LOG_PATH.APP_PROJECT_PATH);
	define("APP_CACHE_PATH",CACHE_PATH.APP_PROJECT_PATH);
	define("APP_DOWNLOAD_PATH",APP_RESSOURCES_PATH."download/");
	define("APP_UPLOAD_PATH",APP_RESSOURCES_PATH."upload/");
	
	//LES MESSAGES D'EXCEPTION DU FRAMEWORK
	define("THROW_CLASS_NOT_FOUND_MSG","L'action ''[PARAM]'' n'est pas définie dans la classe ''[PARAM_1]''...");
	define("THROW_REQUEST_PARAM_NOT_FOUND_MSG","Le paramètre ''[PARAM]'' est absent de la présente requête http...");
	define("THROW_CONTROLLER_NOT_FOUND_MSG","La page ''[PARAM]'' est introuvable dans ce projet...");
	define("THROW_SESSION_ATTRIBUTE_NOT_FOUND_MSG","L'attribut ''[PARAM]'' est absent de la session présente...");
	define("THROW_VIEW_FILE_NOT_FOUND_MSG","La vue ''[PARAM]'' est introuvable dans ce projet...");
	
	//LE LABEL (CLE DANS LE TABLEAU $_APP) DU PARAMETRE DANS LA VUE DES ERREURS
	define("THROW_MSG_LABEL","_ERROR-MESSAGE_");
	
	/** Autres fichiers inclus */
	include_once "conf.app" . APP_FILE_EXTENSION;	/** Fichier de configuration spécifique de l'app.. Vous devez le modifier à votre guise */
	include_once "conf.db" . APP_FILE_EXTENSION;		/** Fichier de configuration de la base de donnée */