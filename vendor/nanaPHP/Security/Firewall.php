<?php
	namespace nanaPHP\Security;
	/**
	* Classe modélisant un Firewall
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	final class Firewall 
	{
 		private $controller = null;
		private $methode = null;
		private $status = FALSE;
		public $privilege = null;

		function __construct() 
		{
			$this->privilege = new \nanaPHP\Security\Privilege();
		}

		public final function start($controller = 'User', $methode = 'login')
		{
			//Activer la prise en charge du Firewall
			if(!$this->status){
				$this->status = TRUE;
				$this->setController($controller);
				$this->setMethode($methode);
			}
			return $this;
		}

		public final function restart($controller = 'User', $methode = 'login')
		{
			//Activer la prise en charge du Firewall
			if(!$this->status) 
				$this->start();
			else{
				$this->setController($controller);
				$this->setMethode($methode);
			}
			return $this;
		}
		
		public final function stop(){
			$this->status = FALSE;
			$this->setController(null);
			$this->setMethode(null);
		}

 		public final function getController(){
			return $this->controller ;
		}

 		public final function getMethode(){
			return $this->methode ;
		}
		
		public final function isRunning(){
			return $this->status ;
		}
		
 		private final function setController($_controller){
			$this->controller = $_controller ;
		}

 		private final function setMethode($_methode){
			$this->methode = $_methode ;
		}
	}