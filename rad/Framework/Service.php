<?php	
	/*
	* Classe de définition des services diverses qu'utilisaeront le framework.
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Service 
	{
		/**
		* Méthode statique recursive permetant de copier le contenu d'un repertoire vers un autre
		*
		* @param string $sourceDir le repertoire source de la copie
		* @param string $destinationDir le repertoire de destination
		*/
		public static function copyDir($sourceDir,$destinationDir) 
		{    
			$sourceDir = self::getValideDir($sourceDir);
			$destinationDir = self::getValideDir($destinationDir);
			if (is_dir($sourceDir)) 
			{   
				if ($dirInstance = opendir($sourceDir)) 
				{                     
					while(($file = readdir($dirInstance)) !== false) 
					{                          
						if (!is_dir($destinationDir)) mkdir($destinationDir,777);                           
						if(is_dir($sourceDir.$file) && $file != '..'  && $file != '.')
						{
							self::copyDir ( $sourceDir.$file.'/' , $destinationDir.$file.'/'); 
						}
						 elseif($file != '..'  && $file != '.') 
						{
							copy ( $sourceDir.$file , $destinationDir.$file );
						}
					 }
					closedir($dirInstance);
				}               
			}    
		}
		

		/**
		* Méthode statique recursive permetant de lister les élément d'un repertoire dans dans un tableau
		* 
		* @param string $dir le repertoire à lister dans le tableau
		* @param array $tab le tableau résultat
		*
		* @return array tab tableau contenant le contenu du repertoire
		*/
		public static function dir2Array($dir,$tab = array()) 
		{    
			$dir = self::getValideDir($dir);
			if (is_dir($dir)) 
			{   

				if ($dirInstance = opendir($dir)) 
				{                     
					while(($file = readdir($dirInstance)) !== false) 
					{                          
						if(is_dir($dir.$file) && $file != '..'  && $file != '.')
						{
							$tab = self::dir2Array ( $dir.$file.'/', $tab); 
						}
						 elseif($file != '..'  && $file != '.') 
						{
							$tab[$dir][] = $file;
						}
					 }
					closedir($dirInstance);
				}               
			} 
			return $tab;
		}

		/**
		* Méthode statique retournant vrai ou faux selon que qu'un mot se trouve ou non dans une phrase
		* 
		* @param string $mot le mot à rechercher
		* @param string $phrase la phrase de recherche
		*
		* @return boolean
		*/
		public static function match_cs($mot,$phrase) 
		{    
			return preg_match("#".$mot."#", $phrase);
		}

		public static function match_ci($mot,$phrase) 
		{    
			return preg_match("#".$mot."#i", $phrase);
		}
						
		/**
		* Méthode statique qui vérifie et rend un chemin de repertoire ou de fichier valide
		* 
		* @param string $dir le repertoire à vérifier
		*
		* @ return string $dir le repertoire valide
		*/
		protected static function getValideDir($dir)
		{
			if(IDE != null) 
				$absoluteFolder = WWW_FOLDER . "/nanaPHP/";
			else 
				$absoluteFolder = WWW_FOLDER . PATH;
			
			if(substr($dir,0,1)!="/" && substr($dir,1,1)!=":") $dir = $absoluteFolder.$dir;
			if(substr($dir,strlen($dir)-1,1)!="/") $dir = $dir . "/";
			return $dir;
		}
		
		/**
		* Méthode qui retourne le contenu d'une vue passée en paramètres
		* 
		* @param string $file le nom de la vue
		* @param array $_APP le tableau contenant les paramètres de la vues
		*
		* @return string le contenu de la vue ou une exception selon le cas
		*/
		public static function getRender($file, $_APP = array()) 
		{
			$file = APP_VIEW_PATH . $file . APP_FILE_EXTENSION;
			if (file_exists($file)) {
				ob_start();
				require_once $file;
				return ob_get_clean();
			}
			else {
				throw new Exception(str_replace("[PARAM]",$file,THROW_VIEW_FILE_NOT_FOUND_MSG));
			}
		}
		
		/**
		* Retourne le contrôleur courant en fonction de la requête reçue
		* 
		* @return le nom du contrôleur courant
		*/
		public static function getControllerName() 
		{
			// Grâce à la redirection, toutes les URL entrantes sont du type :
			// index.php?controller=XXX&methode=YYY&id=ZZZ
			
			$controller = APP_DEFAULT_CONTROLLER;  // Contrôleur par défaut
			if (isset($_GET['controller']) && $_GET['controller'] != "") {
				$controller = $_GET['controller'];
				// Première lettre en majuscules
				$controller = ucfirst(strtolower($controller));
			}
			return $controller;
		}
		
	
		/**
		* Méthode qui génère un code en fonction de la longueur du code et de la liste des caractères
		* 
		* @param string $longueur Longueur du code
		* @param array $characters Liste des caractères autorisés dans le code
		*
		* @return string Le code généré
		*/
		public static function generateCode($longueur = 7, $characters = array('&','W','X','Y','0','Z','e','~','f','g','l','P','Q','R','@','2','S','q','r','I','J','1','K','#','L','s','3','t','u','v','A','B','£','C','D','w','5','x','y','z','E','6','F','$','G','H','7','h','i','j','k','8','M','N','O','T','U','V','a','b','9','c','d','m','n','o','4','p'))
		{
			//On initialise le code à vide
			$code = "";
			for($i = 0; $i < $longueur; $i++)
			{
				$code .= ($i%2) ? ($characters[array_rand($characters)]) : $characters[array_rand($characters)];
			}
			return $keyword . $code;
		}

		/**
		* Méthode qui génère un fichier texte quelconque à partir de données d'un tableau
		* 
		* @param array $fileData Tableau contenant les données du fichier
		* @param string $fileName Nom du fichier
		* @param string $spliteChar Séparateur des colonnes du fichier
		*
		* @return boolean Si oui ou non la génération a marché
		*/
		public static function dataToFile($fileData, $fileName, $spliteChar = ";")
		{
		   //Chemin du fichier sur le serveur
		   $fileAbsoluteName = APP_DOWNLOAD_PATH . $fileName;
		   //On ouvre le fichier
		   $file = fopen($fileAbsoluteName, "wb");
		   //On charge le contenu du fichier
		   $fileContent = "";
		   foreach($fileData as $ligne)
		   {
				$fileContent .= implode($spliteChar,$ligne) . "\n";
		   }
		   //Ecriture du contenu dans le fichier
		   $retour = fwrite($file, $fileContent);
		   //Fermeture du fichier
		   fclose($file);
		   return $retour;
		}
	
		/**
		* Méthode qui télécharge un fichier à partir de son nom
		* 
		* @param string $dumpFileName Nom du fichier
		*
		*/
		public static function downloadTextFile($dumpFileName)
		{
		   //Chemin du fichier sur le serveur
		   $dumpFileAbsoluteName = APP_DOWNLOAD_PATH . $dumpFileName;
		   
		   //Téléchargement du fichier
		   header('Content-Transfer-Encoding: binary'); //Transfert en binaire (fichier).
		   header('Content-Disposition: attachment; filename="'.$dumpFileName.'"'); //Nom du fichier.
		   header('Content-Length: '.filesize($dumpFileAbsoluteName)); //Taille du fichier.
		   readfile($dumpFileAbsoluteName);
		}
	}