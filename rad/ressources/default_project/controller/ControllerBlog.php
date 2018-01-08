<?php
	/**
	 * Contrôleur des actions de la page Blog
	 *
	 * @author GUETIKILA Daouda
	 */
	class ControllerBlog extends Controller {
		
		// Action de la page principale
		public function index() 
		{
			$view = $this->getView();
			$view->setContent();
		}				
	}