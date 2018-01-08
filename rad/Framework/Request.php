<?php
	/**
	* Classe modélisant une requête HTTP entrante
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	
	class Request 
	{
		/** Tableau des paramètres de la requête */
		private $param;
		
		/** Objet session associé à la requête */
		private $session;
		
		/**
		* Constructeur
		* 
		* @param array $param Paramètres de la requête
		*/
		public function __construct($param) 
		{
			$this->param = $param;
			$this->session = new Session();
		}
		
		/**
		* Renvoie l'objet session associé à la requête
		* 
		* @return Session Objet session
		*/
		public function getSession()
		{
			return $this->session;
		}
		
		/**
		* Renvoie vrai si le paramètre existe dans la requête
		* 
		* @param string $nom Nom du paramètre
		* @return bool Vrai si le paramètre existe 
		*/
		public function existParam($nom) 
		{
			return (isset($this->param[$nom]));
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
			else {
				throw new Exception(str_replace("[PARAM]",$nom,THROW_REQUEST_PARAM_NOT_FOUND_MSG));
			}
		}
		
		/**
		* Renvoie vrai si le paramètre existe dans la requête et n'est pas vide
		* 
		* @param string $nom Nom du paramètre
		* @return bool Vrai si le paramètre existe et sa valeur n'est pas vide 
		*/
		public function notEmpty($nom) 
		{
			return (isset($this->param[$nom]) && $this->param[$nom] != "");
		}
	}