<?php	
	define("IDE",true);  //Propre à l'IDE nanaPHP
	
	//VARIABLE GLOBALE RIQUISE
	$_APP = array();

	//LES CONTROLEURS DE L'APPLICTION								 
	$_APP['NAVBAR_BUNDLE'] = array("Home" => "",
						 "Projet" => "",
						 "Solutions" => "",
						 "Forum" => "",
						 "Faq" => "",
						 "Contact" => "",
						);
	
	// Mise à jour du paramètre pourtant le nom du controleur courant avec la classe CSS "ACTIF" de l'application
	$_APP['NAVBAR_BUNDLE'][Service::getControllerName()] = "current";
	
	$_APP['VIEW_DEFAULT_PARAM'] = array("_PATH_" => PATH,
									 "_PUBLIC_PATH_" => APP_RESSOURCES_PATH . "publics/",
								   	 "_LANG_" => "fr",
									 "_TITLE_" => Service::getControllerName() . " | ".APP_ID,
									 );	
	
	$_APP['VIEW_DEFAULT_PARAM'] = array_merge($_APP['VIEW_DEFAULT_PARAM'],$_APP['NAVBAR_BUNDLE']);
