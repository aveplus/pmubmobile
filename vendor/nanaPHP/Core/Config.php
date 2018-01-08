<?php
	namespace nanaPHP\Core;
	/** 
	* Fichier de configuration 
	* Vous ne serez pas amenez à modifier ce fichier, donc ne le faites jamais.
	* Dans le cas contraire les prochaines versions du framework et de l'ide s'intégreront difficilement à votre
	* travail actuel.
	*
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Config
	{
		const APP_SRC_PATH 	= 'projects/default_app/';
		const APP_MODE 		= 'dev';
		const APP_NAME 		= 'nanaPHP default app';
		const APP_LANG 		= 'fr';
		
		const APP_DEFAULT_CONTROLLER 	= 'Home';
		const APP_VIEW_TITLE_MODEL 		= '[CONTROLER] | [APP_NAME]';
		
		const APP_USE_SGBD 		= TRUE;
		const APP_USE_INI_FILE 	= FALSE;
		const APP_USE_JSON_FILE = FALSE;
		
		const PRIVILEGE_GREP_NULL 		= 0;
		const PRIVILEGE_GREP_CONTROLLER = 1;
		const PRIVILEGE_GREP_METHOD 	= 2;

		public static function view_default_params(){
			return array();	
		}

		public static function view_default_sub_layouts(){
			return array();	
		}

		public static function view_default_imported_files(){
			return array('entete','menu');	
		}

		public static function app_required_models($action){
			return array();	
		}

		public static function db_url(){
			return array(
							/*1=>array('DB_DSN' => 'mysql:host=localhost;port=3306;dbname=test',
									 'DB_USER' => 'root',
									 'DB_PWD' => ''
							   ),
							2=>array('DB_DSN' => 'mysql:host=localhost;port=3306;dbname=test',
									 'DB_USER' => 'root',
									 'DB_PWD' => ''
							   )*/
						 );	
		}

		public static function app_throw(){
			return array(
							'REQUEST_PARAM_NOT_FOUND' => array(
															'message' => 
																		array('dev' => 'Le paramètre "[PARAM]" est absent de la présente requête http...', 
																			  'prod' => 'Le paramètre "[PARAM]" est absent de la présente requête http...'), 
															'code' => 1000
														),
							'CONTROLLER_NOT_FOUND' => 	 array(
															'message' => 
																		array('dev' => 'La page "[PARAM]" est introuvable dans ce projet...', 
																			  'prod' => 'La page "[PARAM]" est introuvable dans ce projet...'), 
															'code' => 1001
														),
							'CLASS_NOT_FOUND' => 		 array(
															'message' => 
																		array('dev' => 'L\'action "[PARAM]" n\'est pas définie dans la classe "[PARAM_1]"..', 
																			  'prod' => 'L\'action "[PARAM]" n\'est pas définie dans la classe "[PARAM_1]"..'), 
															'code' => 1002
														),
							'VIEW_FILE_NOT_FOUND' => 	array(
															'message' => 
																		array('dev' => 'La vue "[PARAM]" est introuvable dans ce projet...', 
																			  'prod' => 'La vue "[PARAM]" est introuvable dans ce projet...'), 
															'code' => 1003
														),
							'SGBD_NOT_SET' => array(
															'message' => 
																		array('dev' => 'Application database system error...', 
																			  'prod' => 'Désoler! La prise en charge d\'une base de données est temporairement désactivée, consultez votre administrateur pour plus d\'informations...'), 
															'code' => 2000
														),
							'INI_FILE_SYS_NOT_SET' => array(
															'message' => 
																		array('dev' => 'Application INI file system error...', 
																			  'prod' => 'Désoler! La prise en charge d\'un système de fichier (.INI) requis est temporairement désactivée, consultez votre administrateur pour plus d\'informations...'), 
															'code' => 2001
														  ),
							'JSON_FILE_SYS_NOT_SET' => array(
															'message' => 
																		array('dev' => 'Application JSON file system error...', 
																			  'prod' => 'Désoler! La prise en charge d\'un système de fichier (.JSON) requis est temporairement désactivée, consultez votre administrateur pour plus d\'informations...'), 
															'code' => 2002
															),
							'ROUTE_ACCESS_DENIED' => array(
															'message' => 
																		array('dev' => 'Désolé! Vous n\'avez pas accès à cette fonctionnalité "[ROUTE]"...', 
																			  'prod' => 'Désolé! Vous n\'avez pas accès à cette fonctionnalité...'), 
															'code' => 3001
															)
							);	
		}

		public final static function required($type = 'lib',$classNames = array()) {
			switch($type ){
				case "model":
					foreach($classNames as $className){
						// Classes requises par tous les contrôleurs
						require_once NANAPHP_ZONE.NANAPHP_MODEL_PATH.$className.'.class'.NANAPHP_APP_FILE_TYPE;	
					}
					break;
				case "service":
					// Class des services
					require_once NANAPHP_ZONE.NANAPHP_SERVICE_PATH.$classNames.NANAPHP_APP_FILE_TYPE;				
					break;
				default:
					foreach($classNames as $required){
						//Librairies requises
						require_once NANAPHP_ZONE . 'vendor/'.$type.'/'.$required.NANAPHP_APP_FILE_TYPE;
					}
					break;
			}
		}	
	}