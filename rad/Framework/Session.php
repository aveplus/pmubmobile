<?php
	/**
	* Classe modélisant la session.
	* Encapsule la superglobale PHP $_SESSION.
	* 
	* Inspirée du framework PHP de Baptiste Pesquet
	* (https://github.com/bpesquet/Framework-MVC)
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Session
	{
		/**
		* Constructeur.
		* Démarre ou restaure la session
		*/
		public function __construct()
		{
			session_start();
		}
		
		/**
		* Détruit la session actuelle
		*/
		public function destroy()
		{
			session_destroy();
		}
		
		/**
		* Détruit une variable de la session actuelle
		*/
		public function remove($nom)
		{
			if ($this->exist($nom)) {
				unset($_SESSION[$nom]);
			}
			else {
				throw new Exception(str_replace("[PARAM]",$nom,THROW_SESSION_ATTRIBUTE_NOT_FOUND_MSG));
			}
		}
		
		/**
		* Ajoute un attribut à la session
		* 
		* @param string $nom Nom de l'attribut
		* @param string $valeur Valeur de l'attribut
		*/
		public function set($nom, $valeur)
		{
			$_SESSION[$nom] = $valeur;
		}
		
		/**
		* Renvoie vrai si l'attribut existe dans la session
		* 
		* @param string $nom Nom de l'attribut
		* @return bool Vrai si l'attribut existe et sa valeur n'est pas vide 
		*/
		public function exist($nom)
		{
			return isset($_SESSION[$nom]);
		}
				
		/**
		* Renvoie la valeur de l'attribut demandé
		* 
		* @param string $nom Nom de l'attribut
		* @return string Valeur de l'attribut
		* @throws Exception Si l'attribut n'existe pas dans la session
		*/
		public function get($nom)
		{
			if ($this->exist($nom)) {
				return $_SESSION[$nom];
			}
			else {
				throw new Exception(str_replace("[PARAM]",$nom,THROW_SESSION_ATTRIBUTE_NOT_FOUND_MSG));
			}
		}

		/**
		* Renvoie la valeur de l'attribut demandé et supprime l'attribut de la session
		* 
		* @param string $nom Nom de l'attribut
		* @return string Valeur de l'attribut
		* @throws Exception Si l'attribut n'existe pas dans la session
		*/		
		public function getFlash($nom)
		{
			if ($this->exist($nom)) {
				$flash = $_SESSION[$nom];
				unset($_SESSION[$nom]);
				return $flash;
			}
			else {
				throw new Exception(str_replace("[PARAM]",$nom,THROW_SESSION_ATTRIBUTE_NOT_FOUND_MSG));
			}
		}
	}