<?php
	namespace nanaPHP\Core;
	
	/**
	*
	* Classe abstraite Controller
	* Fournit des services communs aux classes controleurs dérivées
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	
	abstract class Controller 
	{
		public static $me;
		/** Methode à réaliser par le controleur */
		private $methode;
		private $params = array();
				
		/** Requête entrante récupérer par le controleur + config de l'app et la session courante */
		protected $request;
		protected $session;
		
		protected $connexion = array();
		
		/** Vue du contoleur */
		private $view = null;
		
		public function __construct(\nanaPHP\HttpTools\Request $request = null, \nanaPHP\HttpTools\Session $session = null, array $other = null) 
		{
			$this->request 	= $request;
			$this->session 	= $session;
			if($other==null){
				self::$me 		= $this->request->getParam('nanaPHPController');
				$this->methode 	= $this->request->getParam('nanaPHPMethode');
				$this->params 	= $this->request->getParam('nanaPHPParam');
			}
			else{
				self::$me 		= $other['nanaPHPController'];
				$this->methode 	= $other['nanaPHPMethode'];
				$this->params 	= $other['nanaPHPParam'];
			}
		}

		public function url_base()
		{
			return \app\APP_URL_BASE;
		}		

		public final function nana_set_login($privilege = null)
		{
			$this->session->nana_connect();
			if($privilege)
				$this->session->nana_set_privileges($privilege);
		}

		public final function nana_set_logout()
		{
			$this->session->nana_disconnect();
		}

		public final function redirect($controleur, $methode = null, $param = null)
		{
			// Redirection vers l'URL /racine_site/controleur/methode/paramètres
			$this->request->redirect($controleur, $methode = null, $param = null);
		}
		
		private final function setView()
		{
			// Détermination du nom du fichier de la vue à partir du nom du contrôleur actuel
			$controller = self::$me . '/';
			// Instanciation et d'un objet vue
			$this->view = new View($controller . $this->methode, $this->session);			
		}
		
		public final function setViewFile($view_file)
		{
			if($this->view == null) $this->setView();
			$this->view->set_file($view_file);
		}

		/**
		* Génère et affiche la vue
		* 
		* @param array $data Données nécessaires à la génération de la vue
		*/
		public final function render($_APP = array()) 
		{
			if($this->view == null) $this->setView();
			$this->view->render($_APP);
		}

		public final function importFile($file){
			if($this->view == null) $this->setView();
			$this->view->add_imported_file($file);
		}

		public final function addSubLayout($layout){
			if($this->view == null) $this->setView();
			$this->view->add_sub_layout($layout);
		}

		public final function removeFile($file){
			if($this->view == null) $this->setView();
			$this->view->remove_imported_file($file);
		}

		public final function removeSubLayout($layout){
			if($this->view == null) $this->setView();
			$this->view->remove_sub_layout($layout);
		}
		
		// Not realy USE, Just to test
		public final function printImportedFile(){
			if($this->view == null) $this->setView();
			$this->echoTest($this->view->get_imported_file());
		}

		// Not realy USE, Just to test
		public final function printSubLayout(){
			if($this->view == null) $this->setView();
			$this->echoTest($this->view->get_sub_layout());
		}			
			
		/**
		* Méthode qui retourne le contenu d'une vue passée en paramètres
		* 
		* @param string $file le nom de la vue
		* @param array $_APP le tableau contenant les paramètres de la vues
		*
		* @return string le contenu de la vue ou une exception selon le cas
		*/
		public final function getRender($file, $_APP = array()) 
		{
			$file = NANAPHP_VIEW_PATH . $file . NANAPHP_APP_FILE_TYPE;
			if (file_exists($file)) {
				ob_start();
				require_once NANAPHP_ZONE . $file;
				return ob_get_clean();
			}
			else {
				throw new \Exception('Désoler! La page "'.$file.'" est introuvable...',1004);
			}
		}
	
		/**
		* Exécute la methode à réaliser.
		* Appelle la méthode portant le même nom que la methode sur l'objet Controller courant
		* 
		* @throws Exception Si la methode n'existe pas dans la classe Controller courante
		*
		* @param string $methode Contrôleur
		* @param type $param Paramètre
		*/
		public final function execute()
		{
			if(method_exists($this, $this->methode)){
				// Gestion des inclusions de fichiers
				// Appel des classes requises par le contrôleur courant
				$this->include_model();
				return ($this->params != null || $this->methode == 'index') ? $this->{$this->methode}($this->params) : $this->{$this->methode}();
			}
			else 
			{
				$fonctionnalite = ($this->methode != 'index') ? ' "'.$this->methode.'" ' : ' ';
				throw new \Exception("Désoler! La fonctionnalité".$fonctionnalite."demandée est introuvable...",1002);
			}
		}

		public final function execute_service()
		{
			if(method_exists($this, $this->methode)){
				// Gestion des inclusions de fichiers				
				// Appel des classes requises par le contrôleur courant
				$this->include_model();
				return ($this->params != null || $this->methode == 'index') ? $this->{$this->methode}($this->params) : $this->{$this->methode}();
			}
			else{
				$fonctionnalite = ($this->methode != 'index') ? ' "'.$this->methode.'" ' : ' ';
				die('Désoler! La fonctionnalité'.$fonctionnalite.'demandée est introuvable...');
			}
		}

		private final function include_model(){
			$require_key = \app\Config::APP_REQUIRE_MODEL_BY_METHOD ? self::$me.'/'.strtolower($this->methode) : self::$me;
			$required_models = \app\Config::app_required_models($require_key);
			\app\Config::required('model',$required_models);			
		}
		
		public final function executeErr()
		{
			$this->methode = 'Err';
			if (method_exists($this, $this->methode)) 
				$this->{$this->methode}();
			else
				throw new \Exception("Désoler! Une erreur inattendue s'est produite...",1002);
		}
		
		/**
		* Méthode abstraite correspondant à la methode par défaut
		* Oblige les classes dérivées à implémenter cette methode par défaut
		*/
		public abstract function index($param = array());
		
		protected final function getBdConnection($DB_ID = 1)
		{
			if(\app\Config::APP_USE_SGBD){
				// Détermination du nom du fichier de la vue à partir du nom du contrôleur actuel
				if(isset($this->connexion[$DB_ID]))
					return $this->connexion[$DB_ID];
				else
				{
					$db = new \nanaPHP\DataConnector\Database(\app\Config::db_url(), $DB_ID);
					$this->connexion[$DB_ID] = $db->get(\app\Config::db_url(), $DB_ID);
					return $this->connexion[$DB_ID];
				}
			}
			else{
				$msg = \app\Config::app_throw();
				throw new \Exception($msg['SGBD_NOT_SET']['message'][\app\Config::APP_MODE],$msg['SGBD_NOT_SET']['code']);
			}
		}
						
		/**
		* Nettoie une valeur insérée dans une page HTML
		* Permet d'éviter les problèmes d'exécution de code indésirable (XSS) dans les vues générées
		* 
		* @param string $valeur Valeur à nettoyer
		* @return string Valeur nettoyée
		*/
		public function clean($valeur) {
			return htmlspecialchars($valeur, \ENT_QUOTES, 'UTF-8', false); 
		}

		/**
		* Permet d'afficher des messages d'alertes bien formaté dans la vue
		* 
		* @param string $texte Message
		*/
		protected function setAlertDanger($texte, $param = array())
		{
			$id = isset($param['id']) ? 'id="'.$param['id'].'"' : "";
			$class = isset($param['class']) ? $param['class']." " : "";
			$title = isset($param['title']) ? $param['title'] : "Oups";
			
			$fermer = isset($param['fermer']) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button>' : "";
			$fermerClass = isset($param['fermer']) ? ' alert-dismissable' : "";
			
			return '<div '.$id.' class="'.$class.'alert alert-danger'.$fermerClass.'">
					'.$fermer.' 
					<strong>'.$title.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertSuccess($texte, $param = array())
		{
			$id = isset($param['id']) ? 'id="'.$param['id'].'"' : "";
			$class = isset($param['class']) ? $param['class']." " : "";
			$title = isset($param['title']) ? $param['title'] : "Succès";
			
			$fermer = isset($param['fermer']) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button>' : "";
			$fermerClass = isset($param['fermer']) ? ' alert-dismissable' : "";
			
			return '<div '.$id.' class="'.$class.'alert alert-success'.$fermerClass.'">
					'.$fermer.' 
					<strong>'.$title.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertWarning($texte, $param = array())
		{
			$id = isset($param['id']) ? 'id="'.$param['id'].'"' : "";
			$class = isset($param['class']) ? $param['class']." " : "";
			$title = isset($param['title']) ? $param['title'] : "Attention";
			
			$fermer = isset($param['fermer']) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button>' : "";
			$fermerClass = isset($param['fermer']) ? ' alert-dismissable' : "";
			
			return '<div '.$id.' class="'.$class.'alert alert-warning'.$fermerClass.'">
					'.$fermer.'
					<strong>'.$title.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertInfo($texte, $param = array())
		{
			$id = isset($param['id']) ? 'id="'.$param['id'].'"' : "";
			$class = isset($param['class']) ? $param['class']." " : "";
			$title = isset($param['title']) ? $param['title'] : "Note";
			
			$fermer = isset($param['fermer']) ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button>' : "";
			$fermerClass = isset($param['fermer']) ? ' alert-dismissable' : "";

			return '<div '.$id.' class="'.$class.'alert alert-info'.$fermerClass.'">
					'.$fermer.' 
					<strong>'.$title.'! </strong>&nbsp;'.$texte.'
					</div>';
		}
		
		public function printTest($param) 
		{
			if(!is_array($param))
				$this->echoTest($param);
			else {
				echo '<pre>';
				print_r($param);
				echo '</pre>';
			}
		}

		public function echoTest($param) 
		{
			if(is_array($param)) 
				$this->printTest($param);
			else
				echo '<pre>'.$param.'</pre>';
		}
	}