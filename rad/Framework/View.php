<?php
	/**
	* Classe modélisant une vue
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class View 
	{
		/** Nom du fichier associé à la vue */
		private $file;
		
		/** Les paramètres par défaut de la vue définis dans le ficiher de configuration */
		private $_VIEW_DEFAULT_PARAM;	
		
		/**
		* Constructeur
		* 
		* @param string $action Action à laquelle la vue est associée
		* @param string $controller Nom du contrôleur auquel la vue est associée
		*/
		public function __construct($action, $controller = "") 
		{
			// Détermination du nom du fichier vue à partir de l'action et du constructeur
			// La convention de nommage des fichiers vues est : view/<$controller>/<$action>.php
			global $_APP;			
			$this->_VIEW_DEFAULT_PARAM = $_APP['VIEW_DEFAULT_PARAM'] ;
			
			$file = APP_VIEW_PATH;
			if ($controller != "") $file = $file . $controller . "/";
			$this->file = $file . $action . APP_FILE_EXTENSION;
		}

		/**
		* Permet de modifier les valeurs par défaut des paramètres de la vue
		* 
		* @param string $label Ettiquête d'un tableau php utilisé dans la vue
		* @param string $valeur Valeur du paramètre d'un tableau php utilisé dans la vue
		*/
		public function setViewDefaultParam($label,$value)
		{
			$this->_VIEW_DEFAULT_PARAM[$label] = $value;
		}

		/**
		* Permet de modifier les valeurs par défaut des paramètres de la vue
		* 
		* @param string $label Ettiquête d'un tableau php utilisé dans la vue
		* @param string $valeur Valeur du paramètre d'un tableau php utilisé dans la vue
		*/
		public function setViewFile($view_file)
		{
			$this->file = APP_VIEW_PATH . $view_file . APP_FILE_EXTENSION;
		}
				
		/**
		* Génère et affiche la vue
		* 
		* @param array $data Données nécessaires à la génération de la vue
		*/
		public function setContent($_APP = array()) 
		{
			$_APP = array_merge($_APP,$this->_VIEW_DEFAULT_PARAM);
			
			// Génération de la partie spécifique de la vue
			$_APP["_CONTENT_"] = $this->getFileContent($this->file, $_APP);
			
			// Génération du gabarit commun utilisant la partie spécifiques
			$view = $this->getFileContent(APP_VIEW_PATH . APP_LAYOUT_FILE , $_APP);
			
			// Renvoi de la vue générée au navigateur
			echo $view;
		}
		
		/**
		* Génère un fichier vue et renvoie le résultat produit
		* 
		* @param string $file Chemin du fichier vue à générer
		* @param array $data Données nécessaires à la génération de la vue
		* @return string Résultat de la génération de la vue
		* @throws Exception Si le fichier vue est introuvable
		*/
		public function getFileContent($file, $_APP) 
		{
			if (file_exists($file)) {
				// Démarrage de la temporisation de sortie
				ob_start();
				// Inclut le fichier vue
				// Son résultat est placé dans le tampon de sortie
				require_once $file;
				// Arrêt de la temporisation et renvoi du tampon de sortie
				return ob_get_clean();
			}
			else {
				throw new Exception(str_replace("[PARAM]",$file,THROW_VIEW_FILE_NOT_FOUND_MSG));
			}
		}
		
		/**
		* Nettoie une valeur insérée dans une page HTML
		* Permet d'éviter les problèmes d'exécution de code indésirable (XSS) dans les vues générées
		* 
		* @param string $valeur Valeur à nettoyer
		* @return string Valeur nettoyée
		*/
		public function cln($valeur) {
			return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8', false);
		}
	}