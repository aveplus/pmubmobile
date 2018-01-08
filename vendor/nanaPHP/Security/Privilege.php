<?php
	namespace nanaPHP\Security;
	/**
	* Classe modélisant un Gestionnaire des privilèges
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	final class Privilege 
	{
 		private $access_route = array();
		private $access_level = 0;
		private $know_level = array(0,1,2);
		private $dmz = array();
		
		public function setDmz($route) 
		{
			if(is_array($route)){
				$this->dmz = array_merge($this->dmz,$route);
			}
			else{
				$this->dmz[] = $route;
			}
			return $this;
		}
		
		public function setAccess_level($level) 
		{
			if(in_array($level,$this->know_level)){
				$this->access_level = $level;
			}
			else{
				die('Niveau de controle de privilège inconnu...');
			}
			return $this;
		}

		public function getAccess_level(){
			return $this->access_level;
		}

		public function isInDmz($controller){
			return in_array($controller,$this->dmz);
		}

	}