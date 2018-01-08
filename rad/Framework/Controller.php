<?php
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
		/** Methode à réaliser par le controleur */
		private $methode;
		
		/** Requête entrante récupérer par le controleur */
		protected $request;
		
		/**
		* Définit la requête entrante
		* 
		* @param Request $request Requête entrante
		*/
		public function setRequest(Request $request)
		{
			$this->request = $request;
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
		public function executeMethode($methode,$param = null)
		{
			if (method_exists($this, $methode)) {
				$this->methode = $methode;
				if($param != null) $this->{$this->methode}($param);
				else				  $this->{$this->methode}();
			}
			else {
				$classeController = get_class($this);
				throw new Exception(str_replace("[PARAM]",$methode,str_replace("[PARAM_1]",$classeController,THROW_CLASS_NOT_FOUND_MSG)));
			}
		}
		
		/**
		* Méthode abstraite correspondant à la methode par défaut
		* Oblige les classes dérivées à implémenter cette methode par défaut
		*/
		public abstract function index();
		
		/**
		* Génère la vue associée au contrôleur courant
		*/
		protected function getView()
		{
			// Détermination du nom du fichier de la vue à partir du nom du contrôleur actuel
			$classeController = get_class($this);
			$controleur = str_replace("Controller", "", $classeController);
			// Instanciation et d'un objet vue
			return new View($this->methode, $controleur);
		}

		protected static function getBdConnection($DB_ID = 1)
		{
			// Détermination du nom du fichier de la vue à partir du nom du contrôleur actuel
			return Database::get($DB_ID);
		}
						
		/**
		* Effectue une redirection vers un contrôleur et une methode spécifiques avec ou non des paramètres
		* 
		* @param string $controleur Contrôleur
		* @param type $methode Methode Methode
		* @param type $param Paramètres
		*/
		protected function redirect($controleur, $methode = null, $param = null)
		{
			// Redirection vers l'URL /racine_site/controleur/methode/paramètres
			if ($param != null) 
				header("Location:" . PATH . $controleur . "/" . $methode . "/" . $param);
			else 
				header("Location:" . PATH . $controleur . "/" . $methode);
		}

		/**
		* Permet d'afficher des messages d'alertes bien formaté dans la vue
		* 
		* @param string $texte Message
		*/
		protected function setAlertDanger($texte,$intonation='Oups')
		{
			return '<div class="alert alert-danger alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button> 
					<strong>'.$intonation.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertSuccess($texte,$intonation='Succès')
		{
			return '<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button> 
					<strong>'.$intonation.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertWarning($texte,$intonation='Attention')
		{
			return '<div class="alert alert-warning alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button> 
					<strong>'.$intonation.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}

		protected function setAlertInfo($texte,$intonation='Note')
		{
			return '<div class="alert alert-info  alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times; </button> 
					<strong>'.$intonation.'! </strong>&nbsp;'.$texte.'
					</div>';	
		}
	}