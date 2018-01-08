<?php	
	//VARIABLE GLOBALE RIQUISE
	$_APP = array();
	
	//TOUTES CES CLES DU TABLEAU SONT REQUISENT PAR LE FRAMEWORK
	$_APP['ACTIVE_MENU_CLASS'] = "current";
	
	$_APP['VIEW_DEFAULT_PARAM'] = array("_PATH_" => PATH,
									 "_PUBLIC_PATH_" => APP_RESSOURCES_PATH . "publics/",
								   	 "_LANG_" => "fr",
									 "_TITLE_" => "Home | ".APP_ID,
									 "_PHONE-1_" => "(+226) 70 62 66 83",
									 "_PHONE-2_" => "76 91 30 29",
									 );	
	
	
	//LES CONTROLEURS DE L'APPLICTION
	$_APP['NAVBAR_BUNDLE'] = array("Home" => "",
								 	 "Support" => "",
									 "About" => "",
									 "Blog" => "",
									 "Contact" => "",
									 );
	
	//TITLE DES VUES POUR CHAQUE CONTROLEUR	
	$_APP['BUNDLE_TITLE'] =  array("Home" => "Home | ".APP_ID,
									 "Support" => "Support | ".APP_ID,
									 "About" => "About | ".APP_ID,
									 "Blog" => "Blog | ".APP_ID,
									 "Contact" => "Contact-us | ".APP_ID,
									 );