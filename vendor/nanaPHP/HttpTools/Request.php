<?php
	namespace nanaPHP\HttpTools;	
	/**
	* Classe modélisant une requête HTTP entrante
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	
	final class Request 
	{
		/** Tableau des paramètres de la requête */
		private $param;
		
		private $get;
		
		private $post;
				
		/**
		* Constructeur
		* 
		* @param array $param Paramètres de la requête
		*/
		public function __construct()
		{
			if(\app\Config::APP_REQUEST_SECURITY_BASIC_ON){
				$_GET = \nanaPHP\Security\Filter::basic($_GET);
				$_POST = \nanaPHP\Security\Filter::basic($_POST);
			}
			
			if($_GET['nanaPHPParam']!='//'){
				$_GET['nanaPHPParam'] = explode('/',$_GET['nanaPHPParam']);
				if($_GET['nanaPHPParam'][0]=='') 	unset($_GET['nanaPHPParam'][0]);
				if($_GET['nanaPHPParam'][1]=='') 	unset($_GET['nanaPHPParam'][1]);
				if($_GET['nanaPHPParam'][2]=='') 	unset($_GET['nanaPHPParam'][2]);
				if(count($_GET['nanaPHPParam'])==1)	$_GET['nanaPHPParam'] = $_GET['nanaPHPParam'][0];
			}
			else $_GET['nanaPHPParam'] = null;				
			if(empty($_GET['nanaPHPController']))
				$_GET['nanaPHPController'] = \app\Config::APP_DEFAULT_CONTROLLER;
			$_GET['nanaPHPController'] = ucfirst(strtolower($_GET['nanaPHPController']));
			if(empty($_GET['nanaPHPMethode']))
				$_GET['nanaPHPMethode'] = 'index';
			$this->param = array_merge($_GET, $_POST);
			/**$_GET['nanaPHP_controleur_copie'] = $_GET['nanaPHPController'];*/
			unset($_GET['nanaPHPMethode'], $_GET['nanaPHPParam'], $_GET['nanaPHPController']);
			$this->get = $_GET;
			$this->post = $_POST;
		}

		/**
		* Renvoie l'objet session associé à la requête
		* 
		* @return Session Objet session
		*/
		public function get()
		{
			return $this->get;
		}

		/**
		* Renvoie l'objet session associé à la requête
		* 
		* @return Session Objet session
		*/
		public function post()
		{
			return $this->post;
		}
		
		/**
		* Renvoie vrai si le paramètre existe dans la requête
		* 
		* @param string $nom Nom du paramètre
		* @return bool Vrai si le paramètre existe 
		*/
		public function existParam($nom) 
		{
			if(!is_array($nom)) return (isset($this->param[$nom]));
			return $this->existParams($nom);
		}

		public function existParams($noms = array()) 
		{
			foreach($noms as $nom) if(!isset($this->param[$nom])) return false;
			return true;
		}

		/**
		* Renvoie vrai si le paramètre existe dans la requête et n'est pas vide
		* 
		* @param string $nom Nom du paramètre
		* @return bool Vrai si le paramètre existe et sa valeur n'est pas vide 
		*/
		public function notEmpty($nom) 
		{
			if(!is_array($nom)) return (isset($this->param[$nom]) && $this->param[$nom] != "");
			return $this->notEmptys($nom);
		}		
		
		public function notEmptys($noms) 
		{
			foreach($noms as $nom) if(!isset($this->param[$nom]) || $this->param[$nom]=="") return false;
			return true;
		}		
		
		/**
		* Renvoie la valeur du paramètre demandé
		* 
		* @param string $nom Nom d paramètre
		* @return string Valeur du paramètre
		* @throws Exception Si le paramètre n'existe pas dans la requête
		*/
		public function getParam($nom) 
		{
			if ($this->existParam($nom)) {
				return $this->param[$nom];
			}
			return null;
		}

		public function getParams($noms) 
		{
			$tab = array();
			foreach($noms as $nom){
				$tab[$nom] = $this->getParam($nom);
			}
			return $tab;
		}
		
		/**
		* Effectue une redirection vers un contrôleur et une methode spécifiques avec ou non des paramètres
		* 
		* @param string $controleur Contrôleur
		* @param type $methode Methode Methode
		* @param type $param Paramètres
		*/
		public function redirect($controleur = "", $methode = null, $param = null)
		{
			// Redirection vers l'URL /racine_site/controleur/methode/paramètres
			$url = \app\APP_URL_BASE; /*  /racine_site/  */
			if($controleur != null) $url .=  $controleur;
			if ($methode != null) 	$url .= "/" . $methode;
			if ($param != null) 	$url .= "/" . $param;
			header("Location:" . $url);
			exit();
		}
	}