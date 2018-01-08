<?php
	/**
	 * Contrôleur des actions liées à l'accueil
	 *
	 * @author GUETIKILA Daouda
	 */
	class ControllerHome extends Controller {
		
		// Index
		public function index() 
		{
			$this->getView()->setContent();
		}
	}