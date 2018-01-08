<?php
	/**
	* Exemple d'inclusion de class méthier:
	* require_once APP_MODEL_PATH . 'Pays.class'.APP_FILE_EXTENSION;
	*/
	
	/**
	 * Contrôleur des actions liées à l'accuil
	 *
	 * @author GUETIKILA Daouda
	 */
	class ControllerHome extends Controller {
		
		// Action de l'accuil
		public function index() 
		{
			$view = $this->getView();
			$view->setContent();
		}
		
		// Méthode d'ajout d'un pays
		/*public function add() 
		{
			$pays = array();
			$pays['nom'] = $this->request->getParam("nom");
			$pays['superficie'] = $this->request->getParam("superficie");
			
			$_pays = new Pays();
			$_pays->add($pays);
			
			$view = $this->getView();
			$view->setContent();
		}	

		// Méthode de mise à jour d'un pays
		public function update() 
		{
			$pays = array();
			$pays['nom'] = $this->request->getParam("nom");
			$pays['superficie'] = $this->request->getParam("superficie");
			$pays['id'] = $this->request->getParam("id");
			
			$_pays = new Pays();
			$_pays->update($pays);
			
			$view = $this->getView();
			$view->setContent();
		}*/				
	}