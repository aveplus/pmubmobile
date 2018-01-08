<?php
	/** Configuration de production */	
	define("PATH","/nanaPHP/rad/"); 	//Le documentRoot de cette application
	define("PROJECTS_PATH","src/");		//Dossier projet du framework / Dossier source 'src/' de l'application en production.
	define("APP_PROJECT_PATH",""); 		//Dossier contenant les sources du projet en développement. Se trouve dans 'projects/'
	
	define("APP_ID","nanaPHP");								//ID de l'application, le documentRoot racine sans les '/'
	define("APP_VERSION","1.0");
	define("APP_DEFAULT_CONTROLLER","Home");				//Nom du controleur par défaut
	define("APP_LAYOUT_FILE","layout".APP_FILE_EXTENSION);	//Nom du fichier gabarit de l'application
	define("APP_ERROR_VIEW_FILE","404");					//Nom de la vue des exceptionss