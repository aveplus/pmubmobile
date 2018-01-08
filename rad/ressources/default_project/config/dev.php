<?php
	/** Configuration de production */	
	define("PATH","/nanaPHP/"); 				//Le documentRoot de cette application
	define("PROJECTS_PATH","projects/");		//Dossier projet du framework / Dossier source 'src/' de l'application en production.
	define("APP_PROJECT_PATH","project_path/"); 		//Dossier contenant les sources du projet en développement. Se trouve dans 'projects/'
	
	define("APP_ID","project_id");								//ID de l'application
	define("APP_NAME","project_name");					//Un nom comme vous voulez
	define("APP_DEFAULT_CONTROLLER","Home");				//Nom du controleur par défaut
	define("APP_LAYOUT_FILE","layout".APP_FILE_EXTENSION);	//Nom du fichier gabarit de l'application
	define("APP_ERROR_VIEW_FILE","404");					//Nom de la vue des exceptions