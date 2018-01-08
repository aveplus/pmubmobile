<?php
	namespace nanaPHP\HttpTools;
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
	
	final class Session
	{
		/**
		* Constructeur.
		* Démarre ou restaure la session
		*/
		public function __construct()
		{
			if(!isset($_SESSION)) session_start();
		}
		
		/**
		* Détruit la session actuelle
		*/
		public function destroy()
		{
			session_unset();
			session_destroy();
		}
		
		/**
		* Détruit une variable de la session actuelle
		*/
		public function remove($nom)
		{
			unset($_SESSION[$nom]);
		}
		
		/** To be use by developper to say to nanaPHP that his App is disconnect */
		public function nana_disconnect()
		{
			$this->remove('nana_secure_connected');
			$this->remove('nana_user_access_routes');
		}

		public function nana_connect()
		{
			$this->set('nana_secure_connected',TRUE);
		}

		public function nana_is_connected()
		{
			return $this->exist('nana_secure_connected');
		}

		public function nana_set_privileges($route)
		{
			if (!$this->exist('nana_user_access_routes'))
				$this->set('nana_user_access_routes',array());
				
			if(is_array($route))
				$this->set('nana_user_access_routes',array_merge($this->get('nana_user_access_routes'),$route));
			else
				$this->set('nana_user_access_routes',array_merge($this->get('nana_user_access_routes'),array($route)));
		}
		
		public function nana_is_access_route($route)
		{
			if (!$this->exist('nana_user_access_routes'))
				return FALSE;
			return in_array($route,$_SESSION['nana_user_access_routes']);
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

		public function setFlash($valeur)
		{
			if(!isset($_SESSION['_FLASH_MESSAGE_']))
				$_SESSION['_FLASH_MESSAGE_'] = array();
			$_SESSION['_FLASH_MESSAGE_'][] = $valeur;
		}
			
		/**
		* Renvoie vrai si l'attribut existe dans la session
		* 
		* @param string $nom Nom de l'attribut
		* @return bool Vrai si l'attribut existe et sa valeur n'est pas vide 
		*/
		public function exist($nom)
		{
			if(!is_array($nom)) return isset($_SESSION[$nom]);
			foreach($nom as $_nom)
			{
				if(!isset($_SESSION[$_nom])) return FALSE;
			}
			return TRUE;
		}
				
		/**
		* Renvoie la valeur de l'attribut demandé
		* 
		* @param string $nom Nom de l'attribut
		* @return string Valeur de l'attribut
		*/
		public function get($nom)
		{
			if ($this->exist($nom)) {
				return $_SESSION[$nom];
			}
			return null;
		}

		/**
		* Renvoie la valeur de l'attribut demandé et supprime l'attribut de la session
		* 
		* @param string $nom Nom de l'attribut
		* @return string Valeur de l'attribut
		*/		
		public function getFlash($nom)
		{
			if ($this->exist($nom)) {
				$flash = $_SESSION[$nom];
				unset($_SESSION[$nom]);
				return $flash;
			}
			return null;
		}
		
		public function setErr($valeur = array())
		{
			if(!$this->exist('Err')) $_SESSION['Err'] = array();
			$_SESSION['Err'][] = $valeur;
		}

		public function getErr()
		{
			try {
					return $this->getFlash('Err');
				}
			catch (Exception $e) {
				return array();
			}
		}
	}