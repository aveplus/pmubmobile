
<?php	
	require_once 'Config.php';			/** Need: All */
	require_once FW_SESSION_FILE; 		/** Need: Request */
	require_once FW_CONTROLLER_FILE;	/** Need: Router */
	require_once FW_REQUEST_FILE; 		/** Need: Router, Controller */
	require_once FW_VIEW_FILE;			/** Need: Router, Controller */
	require_once FW_DATABASE_FILE;		/** Need: Controller */
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
		/**
		* Méthode principale appelée par le contrôleur frontal
		* Examine la requête et exécute la methode appropriée
		*/
		public static function run() 
		{
			try {
				// Fusion des paramètres GET et POST de la requête
				// Permet de gérer uniformément ces deux types de requête HTTP
				$request = new Request(array_merge($_GET, $_POST));
				
				$controller = self::getController($request);
				$methode = self::getMethode($request);
				$param = self::getMethodeParam($request);
				
				$controller->executeMethode($methode,$param);
			}
			catch (Exception $e) {
				self::throwError($e);
			}
		}
		
		/**
		* Instancie le contrôleur approprié en fonction de la requête reçue
		* 
		* @param Request $request Requête reçue
		* @return Instance d'un contrôleur
		* @throws Exception Si la création du contrôleur échoue
		*/
		private static function getController(Request $request) 
		{
			// Grâce à la redirection, toutes les URL entrantes sont du type :
			// index.php?controller=XXX&methode=YYY&id=ZZZ
			
			$controller = APP_DEFAULT_CONTROLLER;  // Contrôleur par défaut
			if ($request->existParam('controller') && $request->getParam('controller') != "") {
				$controller = $request->getParam('controller');
				// Première lettre en majuscules
				$controller = ucfirst(strtolower($controller));
			}
			// Création du nom du fichier du contrôleur
			// La convention de nommage des fichiers controllers est : Controller/Controller<$controller>.php
			$copie_controller = $controller; // Pour passer à la section trhrow new Exception()
			$ControllerClass = "Controller" . $controller;
			$ControllerFile = APP_CONTROLLER_PATH . $ControllerClass . APP_FILE_EXTENSION;
			if (file_exists($ControllerFile)) {
				// Instanciation du contrôleur adapté à la requête
				require($ControllerFile);
				$controller = new $ControllerClass();
				$controller->setRequest($request);
				return $controller;
			}
			else {
				throw new Exception(str_replace("[PARAM]",$copie_controller,THROW_CONTROLLER_NOT_FOUND_MSG));
			}
		}
		
		/**
		* Détermine la methode à exécuter en fonction de la requête reçue
		* 
		* @param Request $request Requête reçue
		* @return string Methode à exécuter
		*/
		private static function getMethode(Request $request) 
		{
			if ($request->existParam('methode') && $request->getParam('methode') != "") return $request->getParam('methode');
			return "index"; // Methode par défaut
		}
		
		private static function getMethodeParam(Request $request) {
			if ($request->existParam('id') && $request->getParam('id') != "") return str_replace("SLACH","/",urldecode($request->getParam('id')));
			return null; // paramètre par défaut
		}
		
		/**
		* Gère une erreur d'exécution (exception)
		* 
		* @param Exception $exception Exception qui s'est produite
		*/
		private static function throwError(Exception $exception) {
			$view = new View(APP_ERROR_VIEW_FILE);
			$view->setViewDefaultParam("_TITLE_" ,THROW_MSG_TITLE);
			$view->setContent(array(THROW_MSG_LABEL => $exception->getMessage()));
		}
	}