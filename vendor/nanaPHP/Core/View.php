<?php
	namespace nanaPHP\Core;
	
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
		public $lang;
		public $title;
		public $url_base;
		public $view_base;
		public $media_base;
		public $params;
		public $session;
		public $content;
		private $imported_files = array();
		private $sub_layouts = array();
		/**
		* Constructeur
		* 
		* @param string $action Action à laquelle la vue est associée
		* @param string $controller Nom du contrôleur auquel la vue est associée
		*/
		public function __construct($file, \nanaPHP\HttpTools\Session $session) 
		{
			$this->view_base = NANAPHP_VIEW_PATH;
			$this->file = $this->view_base . $file . NANAPHP_APP_FILE_TYPE;
			$this->lang = \app\Config::APP_LANG;
			
			$title_param = explode('/',$file);
			$param['[CONTROLER]'] 	= isset($title_param[0]) ? $title_param[0] : 'NULL';
			$param['[APP_NAME]'] 	= isset($title_param[1]) ? $title_param[1] : 'NULL';
			$param['[METHODE]'] 	= \app\Config::APP_NAME;
			$this->title = strtr(\app\Config::APP_VIEW_TITLE_MODEL,$param);
			
			$this->url_base = \app\PATH;
			$this->media_base = NANAPHP_WEB_URL_BASE;
			$this->params = \app\Config::view_default_params();
			$this->session = $session;
			foreach(\app\Config::view_default_imported_files() as $imported_file)
				$this->imported_files[$imported_file] = $this->view_base . $imported_file . NANAPHP_APP_FILE_TYPE;
			foreach(\app\Config::view_default_sub_layouts() as $sub_layout)
				$this->sub_layouts[$sub_layout] = $this->view_base . $sub_layout . NANAPHP_APP_FILE_TYPE;
		
		}

		/**
		* Permet de modifier les valeurs par défaut des paramètres de la vue
		* 
		* @param string $label Ettiquête d'un tableau php utilisé dans la vue
		* @param string $valeur Valeur du paramètre d'un tableau php utilisé dans la vue
		*/
		
		public function set_file($view_file){
			$this->file = $this->view_base . $view_file . NANAPHP_APP_FILE_TYPE;
		}

		public function add_imported_file($file){
			$this->imported_files[$file] = $this->view_base . $file . NANAPHP_APP_FILE_TYPE;
		}

		public function add_sub_layout($layout){
			$this->sub_layouts[$layout] = $this->view_base . $layout . NANAPHP_APP_FILE_TYPE;
		}

		public function remove_imported_file($file){
			unset($this->imported_files[$file]);
		}

		public function remove_sub_layout($layout){
			unset($this->sub_layouts[$layout]);
		}

		// Not realy USE, Just to test
		public function get_imported_file(){ 
			return $this->imported_files;
		}
		
		// Not realy USE, Just to test
		public function get_sub_layout(){
			return $this->sub_layouts;
		}
		
		/**
		* Génère et affiche la vue
		* 
		* @param array $data Données nécessaires à la génération de la vue
		*/
		public function render($_APP = array()) 
		{
			/** Fusion des tableaux de paramétres destiné à la vue **/
			if ($_APP != null) $this->params = array_merge($this->params,$_APP);
			
			/** Copie de la session pour éventuel utilisation dans la vue **/
			$this->session = $_SESSION;
			
			/** Génération des vues importées dans la vue en cours **/
			foreach($this->imported_files as $key => $imported_file){
				$this->params['import::'.$key] = $this->get_file_content($imported_file);
			}
			
			/** Génération du contenu principal de la vue en cours **/
			$this->content = $this->get_file_content($this->file);
			
			/** Génération des vues sous gabaries dans la vue en cours **/
			foreach($this->sub_layouts as $key => $sub_layout){
				$this->params['subLayout::'.$key] = $this->get_file_content($sub_layout);
			}
			
			/** Génération du rendu final **/
			$view = $this->get_file_content($this->view_base . NANAPHP_APP_LAYOUT_FILE);
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
		public function get_file_content($file) 
		{
			if (file_exists($file)) {
				// Démarrage de la temporisation de sortie
				ob_start();
				// Inclut le fichier vue
				// Son résultat est placé dans le tampon de sortie
				require_once NANAPHP_ZONE . $file;
				// Arrêt de la temporisation et renvoi du tampon de sortie
				return ob_get_clean();
			}
			else {
				die('Désoler! La page <strong>"'.$file.'"</strong> est introuvable...');
			}
		}
		
		public function printFlashBag($by_clean = TRUE, $message_box_id = 'nana-flashbag-id')
		{
			$flash = "";
			foreach ($this->getFlashBag($by_clean) as $message)
			{
				$flash .= $message;
			}
			if($flash!="") echo '<div id="'.$message_box_id.'">'.$flash.'</div>';
		}
	
		public function getFlashBag($by_clean = TRUE)
		{
			$flasBag = isset($_SESSION['_FLASH_MESSAGE_']) ? $_SESSION['_FLASH_MESSAGE_'] : array();
			if($by_clean) unset($_SESSION['_FLASH_MESSAGE_']);
			return $flasBag;
		}
	}