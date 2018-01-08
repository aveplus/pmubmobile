<?php	
	namespace nanaPHP\Core;
	
	use nanaPHP\HttpTools\Request as nanaPHPRequest;
	use nanaPHP\HttpTools\Session as nanaPHPSession;
	use nanaPHP\Security\Firewall as nanaPHPFirewall;
	
	/*
	* Classe de routage des requêtes entrantes.
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Application 
	{
		//private $Config = null;
		//private $Request = null;
		/**
		* Méthode principale appelée par le contrôleur frontal
		* Examine la requête et exécute la methode appropriée
		*/
		public static function run(nanaPHPRequest $request, nanaPHPSession $session = null, nanaPHPFirewall $firewall = null) 
		{
			// Fusion des paramètres GET et POST de la requête
			// Permet de gérer uniformément ces deux types de requête HTTP
			
			try {	
				$controller = self::getController($request, $session, $firewall);
				$controller->execute();
			}
			catch (\Exception $e) 
			{
				$ControllerClass = "ControllerApp";
				$ControllerFile = NANAPHP_ZONE . NANAPHP_CONTROLLER_PATH . $ControllerClass . NANAPHP_APP_FILE_TYPE;
				if (file_exists($ControllerFile)) 
				{
					try {
						$val = array('code'=>$e->getCode(),'message'=>$e->getMessage());
						$session->setErr($val);					
						require_once($ControllerFile);
						
						$controller = new $ControllerClass($request, $session);
						$controller->executeErr();
					}
					catch (\Exception $e) {
						self::throwError(\app\Config::view_default_params(),$e);
					}
				}
				else
				{
					self::throwError(\app\Config::view_default_params());
				}
			}
		}

		public static function runWebService(nanaPHPRequest $request) 
		{
			// Fusion des paramètres GET et POST de la requête
			// Permet de gérer uniformément ces deux types de requête HTTP
			try {	
				$controller = self::getController_for_call($request, null); //($request, $other);
				return $controller->execute_service();
			}
			catch (\Exception $e) {
				die($e->getMessage());
			}
		}

		public static function call($action, $param = array()) 
		{
			// Fusion des paramètres GET et POST de la requête
			// Permet de gérer uniformément ces deux types de requête HTTP
			try {	
				$GET = self::textToGet($action,$param);
				$controller = self::getController_for_call(null, $GET);
				return $controller->execute_service();
			}
			catch (\Exception $e) {
				die($e->getMessage());
			}
		}
		
		/**
		* Instancie le contrôleur approprié en fonction de la requête reçue
		* 
		* @param Request $request Requête reçue
		* @return Instance d'un contrôleur
		* @throws Exception Si la création du contrôleur échoue
		*/
		private static function getController(nanaPHPRequest $request, nanaPHPSession $session = null, nanaPHPFirewall $firewall = null) 
		{
			// Grâce à la redirection, toutes les URL entrantes sont du type :
			// index.php?controller=XXX&methode=YYY&paramMethode=ZZZ
			
			if( self::isAuthenfied($request, $session, $firewall) ) 
			{
				// Création du nom du fichier du contrôleur
				// La convention de nommage des fichiers controllers est : Controller/Controller<$controller>.php
				$ControllerClass = 'Controller' . $request->getParam('nanaPHPController');
				$ControllerFile = NANAPHP_ZONE . NANAPHP_CONTROLLER_PATH . $ControllerClass . NANAPHP_APP_FILE_TYPE;
				
				if (file_exists($ControllerFile)) 
				{
					// Instanciation du contrôleur adapté à la requête
					require_once($ControllerFile);
					return new $ControllerClass($request, $session);
				}
				else {
					throw new \Exception('Désoler! Le module "'.$request->getParam('nanaPHPController').'" demandé est introuvable...',1001);
				}
			}
			else
			{
				$request->redirect($firewall->getController(),$firewall->getMethode());
			}
		}

		private static function getController_for_call(nanaPHPRequest $request = null, $other = null) 
		{
			// Grâce à la redirection, toutes les URL entrantes sont du type :
			// index.php?controller=XXX&methode=YYY&paramMethode=ZZZ
			
			// Création du nom du fichier du contrôleur
			// La convention de nommage des fichiers controllers est : Controller/Controller<$controller>.php
			$controller = ($other == null) ? ucfirst(strtolower($request->getParam('nanaPHPController'))) : ucfirst(strtolower($other['nanaPHPController']));
			$ControllerClass = 'Controller' . $controller;
			$ControllerFile = NANAPHP_ZONE . NANAPHP_CONTROLLER_PATH . $ControllerClass . NANAPHP_APP_FILE_TYPE;
			if (file_exists($ControllerFile)) 
			{
				// Instanciation du contrôleur adapté à la requête
				$session = null;
				require_once($ControllerFile);
				return new $ControllerClass($request, $session, $other);
			}
			else
				die('Désoler! Le module "'.$controller.'" demandé est introuvable...');
		}

		private static function textToGet($text, $params)
		{
			$tab_text = explode('::',$text);
			$get['nanaPHPController'] 	= (isset($tab_text[0]) && trim($tab_text[0])!='') ? $tab_text[0] 		: \app\Config::APP_DEFAULT_CONTROLLER;
			$get['nanaPHPMethode'] 		= isset($tab_text[1]) ? $tab_text[1] 									: 'index';
			$get['nanaPHPParam'] 		= isset($tab_text[2]) ? array_merge(explode('/',$tab_text[2]),$params) 	: $params;
			if(count($get['nanaPHPParam'])==1)
				$get['nanaPHPParam'] = $get['nanaPHPParam'][0];
			elseif(count($get['nanaPHPParam'])==0)
				$get['nanaPHPParam']= null;			
			return $get;
		}

		private static function isAuthenfied(nanaPHPRequest $request, nanaPHPSession $session = null, nanaPHPFirewall $firewall = null)
		{
			if($session == null)
				return TRUE;
			elseif($session->nana_is_connected())
			{
				if($firewall->privilege->getAccess_level() == \app\Config::PRIVILEGE_GREP_NULL)
					return TRUE;
				elseif($firewall->privilege->isInDmz($request->getParam('nanaPHPController')) || 
						$firewall->privilege->isInDmz($request->getParam('nanaPHPController').'/'.$request->getParam('nanaPHPMethode')) )
					return TRUE;
				elseif($firewall->privilege->getAccess_level() == \app\Config::PRIVILEGE_GREP_CONTROLLER)
					$route = $request->getParam('nanaPHPController');
				else
					$route = $request->getParam('nanaPHPController').'::'.$request->getParam('nanaPHPMethode');
				
				if($session->nana_is_access_route($route))
					return TRUE;
				else{
					$msg = \app\Config::app_throw();
					$msg = $msg['ROUTE_ACCESS_DENIED'];
					throw new \Exception(str_replace('[ROUTE]',$route,$msg['message'][\app\Config::APP_MODE]),$msg['code']);
				}
			}
			elseif($firewall == null)
				return TRUE;
			elseif(!$firewall->isRunning())
				return TRUE;
			elseif($request->getParam('nanaPHPController') == $firewall->getController() && 
				   strtolower($request->getParam('nanaPHPMethode')) == strtolower($firewall->getMethode()))
				return TRUE;
			else
				return FALSE;
		}
		
		/**
		* Gère une erreur d'exécution (exception)
		* 
		* @param Exception $exception Exception qui s'est produite
		*/
		private static function throwError($Param, \Exception $exception = null) 
		{
			if($exception!=null)
			{
				$Param['EXCEPTION_CODE'] = $exception->getCode(); 
				$Param['EXCEPTION_MESSAGE'] = $exception->getMessage(); 
				$traces = $exception->getTrace();
				$ex_trace = "";
				$i = 1;
				foreach($traces as $trace)
				{
					$ligne = '<strong>Niveau ('.$i.')<br><hr>Position de l\'erreur: </strong>';
					$ligne .= $trace['file'].' à la ligne <strong>n°'.$trace['line'].'</strong>; <br><strong>Méthode/Function évoquée: </strong>';
					$ligne .= $trace['function'].' [de la classe '.$trace['class'];
					$ligne .= ']; <br><strong>Arguments utilisés:</strong> '.@implode(' - ',$trace['args']);
					if($ex_trace=="") $ex_trace = $ligne;
					else $ex_trace = $ex_trace . '<br><br>' . $ligne;
					$i++;
				}
				$Param['EXCEPTION_TRACE'] = $ex_trace;
			}
			ob_start();
			require_once NANAPHP_ZONE . 'vendor/nanaPHP/Errdocs/index.php';
			echo ob_get_clean();
		}
	}