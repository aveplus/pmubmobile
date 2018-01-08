<?php
	require_once APP_MODEL_PATH . 'Projet.class'.APP_FILE_EXTENSION;
	
	/**
	 * Contrôleur des actions liées à la manipulation des projets
	 *
	 * @author GUETIKILA Daouda
	 */
	class ControllerProjet extends Controller 
	{
		// Index
		public function index() 
		{
			if($this->request->getSession()->exist('projectName')) 
				$this->request->getSession()->remove('projectName');
			
			$APP = array();
			$APP['_LISTE_PROJET_'] = Projet::dirFolder2Array('projects/');
			$this->getView()->setContent($APP);
		}
	
		public function contenu($dir) 
		{
			$APP = array();
			$APP['_PROJECT_CONTENT_'] = Projet::dirList($dir,"href"); 
			$this->getView()->setContent($APP);
		}

		public function execute($projectName) 
		{
			$app_config_file = WWW_FOLDER . "/" . APP_ID . "/app/config".APP_FILE_EXTENSION;
			$project_config_file = WWW_FOLDER . "/" . APP_ID . "/projects/".$projectName."/config/config".APP_FILE_EXTENSION;
			if(file_exists($project_config_file))
			{
				$app_config_file_content = array('<?php','	require_once "projects/'.$projectName.'/config/config'.APP_FILE_EXTENSION.'";');
				file_put_contents($app_config_file, $app_config_file_content);
				header("Location:/".APP_ID."/");
			}
			else
			{
				throw new Exception('Le fichier de configuration "'.$project_config_file.'" n\'existe pas. Veuillez le créer avant l\'exécution du projet SVP!');
			}
		}
		
		public function nouveau()
		{
			$this->getView()->setContent();
		}
		
		public function creer()
		{
			$projectPath = $this->request->getParam('PROJECT_PATH');
			$projectId = $this->request->getParam('PROJECT_ID');
			$projectName = $this->request->getParam('PROJECT_NAME');
			
			$msg = "";
			if(Projet::prohibit_char_there($projectPath)) $msg .= "Le nom dossier du projet ''".$projectPath."''n'est pas valide.<br>";
			if(Projet::prohibit_char_there($projectId)) $msg .= "L'id du projet ''".$projectId."''n'est pas valide.<br>";
			if($projectName == "" || $projectName == " ") $msg .= "Le nom du projet ''".$projectName."''n'est pas valide.";
			
			if($msg == "")
			{
				$src = WWW_FOLDER . "/" . APP_ID . '/ide/ressources/default_project';
				$dest = WWW_FOLDER . "/" . APP_ID . '/projects/' . $projectPath;
				if(!file_exists($dest))
				{
					try 
					{
						Projet::copyDir($src, $dest);
						$config_file = file_get_contents($dest . '/config/dev' . APP_FILE_EXTENSION);
						
						$config_file = str_replace('project_path',$projectPath,$config_file);
						$config_file = str_replace('project_id',$projectId,$config_file);
						$config_file = str_replace('project_name',$projectName,$config_file);
						
						file_put_contents($dest . '/config/dev' . APP_FILE_EXTENSION,$config_file);
											
						$APP = array();
						$APP['_LISTE_PROJET_'] = Projet::dirFolder2Array('projects/');
						$APP['_FLASH_'] = '<div class="succes"> <strong>Succè! </strong>Le projet '.$projectPath.' a été créé avec succès. Veuillez retrouvez le contenu dans le dossier "' . $dest . '"	<a href="'.PATH.'Projet">Finir ici...</a></div>'; 
						
						$view = $this->getView();
						$view->setViewFile('Projet/index');
						$view->setContent($APP);
					}catch(Exception $e)
					{
						throw new Exception('Une erreur est survenue lors de la création du projet. <br><span style="color:#F00">'.$e->getMessage().'</span>');
					}
				}
				else
				{
					throw new Exception("La création du projet ''".$projectPath."'' n'a pas abouti. Un dossier portant le nom de clui de projet existe déjà, veuillez le supprimer manuellement avant de continuer svp...");
				}
			}
			else
			{
				throw new Exception($msg);
			}
		}
		
		public function deploiment($projectName) 
		{
			$request = $this->request;
			$view = $this->getView();
			
			if(!$request->getSession()->exist('projectName'))
			{
				$request->getSession()->set('projectName',$projectName);
				$APP = array('_PROJET_'=>$projectName);
				$view->setContent($APP);
			}
			elseif($request->existParam('PATH'))
			{
				$request->getSession()->remove('projectName');
				$path = $request->getParam('PATH');
				
				try {
					$deploiment_path = WWW_FOLDER . "/" . APP_ID . '/deploiments/' . $projectName;
					if(!file_exists($deploiment_path)) mkdir($deploiment_path);
					
					$src_to_copy[0]['src'] = WWW_FOLDER . '/' . APP_ID . '/app';
					$src_to_copy[0]['dest'] = $deploiment_path . '/app';
					
					$src_to_copy[1]['src'] = WWW_FOLDER . '/' . APP_ID . '/Framework';
					$src_to_copy[1]['dest'] = $deploiment_path . '/Framework';
					
					$src_to_copy[2]['src'] = WWW_FOLDER . '/' . APP_ID . '/ressources';
					$src_to_copy[2]['dest'] = $deploiment_path . '/ressources';
					
		
					$src_to_copy[3]['src'] = WWW_FOLDER . '/' . APP_ID . '/projects/' . $projectName;
					$src_to_copy[3]['dest'] =  $deploiment_path . '/src';
					foreach($src_to_copy as $copy)
					{
						Projet::copyDir($copy['src'], $copy['dest']);
					}
					
					$src_to_copy = array(array());
					$src_to_copy[0]['src'] = WWW_FOLDER . '/' . APP_ID . '/.htaccess';
					$src_to_copy[0]['dest'] = $deploiment_path . '/.htaccess';
					
					$src_to_copy[1]['src'] = WWW_FOLDER . '/' . APP_ID . '/index' . APP_FILE_EXTENSION;
					$src_to_copy[1]['dest'] = $deploiment_path . '/index' . APP_FILE_EXTENSION;
					foreach($src_to_copy as $copy)
					{
						copy($copy['src'], $copy['dest']);
					}
					
					if($path != "")
					{
						if(substr($path,0,1) != '/') $path = '/'.$path;
						if(substr($path,strlen($path)-2,1) != '/') $path .= '/';
					}
					
					file_put_contents($deploiment_path . '/app/config' . APP_FILE_EXTENSION,
									  '<?php require_once "src/config/config'.APP_FILE_EXTENSION.'";'
									  );
					file_put_contents($deploiment_path . '/src/config/conf.env' . APP_FILE_EXTENSION,
									  '<?php require_once "prod'.APP_FILE_EXTENSION.'";'
									  );
					$config_file = file_get_contents($deploiment_path . '/src/config/dev' . APP_FILE_EXTENSION);
					$config_file = str_replace('define("PATH","/nanaPHP/");','define("PATH","'.$path.'");',$config_file);
					$config_file = str_replace('define("PROJECTS_PATH","projects/");','define("PROJECTS_PATH","src/");',$config_file);
					$config_file = str_replace('define("APP_PROJECT_PATH","'.$projectName.'/");','define("APP_PROJECT_PATH","");',$config_file);
					file_put_contents($deploiment_path . '/src/config/prod' . APP_FILE_EXTENSION,$config_file);
										
					$APP = array();
					$APP['_LISTE_PROJET_'] = Projet::dirFolder2Array('projects/');
					$APP['_FLASH_'] = '<div class="succes"> <strong>Succè! </strong>Le déploiement du projet a réussi. Veuillez retrouvez le contenu dans le dossier "' . $deploiment_path . '"	<a href="'.PATH.'Projet">Finir ici...</a></div>'; 
					
					$view->setViewFile('Projet/index');
					$view->setContent($APP);
				}catch(Exception $e)
				{
					throw new Exception('Une erreur est survenue lors du déploiement du projet. <br><span style="color:#F00">'.$e->getMessage().'</span>');
				}
			}else
			{
				$request->getSession()->remove('projectName');
				header("Location:" . PATH . 'Projet');
			}
		}
	}