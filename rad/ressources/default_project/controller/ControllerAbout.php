<?php
	/**
	* Exemple d'inclusion de class méthier:
	* require_once APP_MODEL_PATH . 'Pays.class'.APP_FILE_EXTENSION;
	*/
	
	/**
	 * Contrôleur des actions de la page About
	 *
	 * @author GUETIKILA Daouda
	 */
	class ControllerAbout extends Controller {
		
		// Action de la page principale
		public function index() 
		{
			$view = $this->getView();
			$view->setContent();
		}				
	}