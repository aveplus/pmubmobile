<?php	
	namespace nanaPHP\Service;
	/*
	* Classe de définition des services diverses qu'utilisaeront le framework.
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Service 
	{
		public static function getIniManager()
		{
			if(\app\Config::USE_INI_FILE)  
                            return new \nanaPHP\FileFormatter\IniFile();
			else{
                            $iniParam = \app\Config::app_throw();
			    $iniParam = $iniParam['INI_FILE_SYS_NOT_SET'];
			    throw new \Exception($iniParam['message'][\app\Config::APP_MODE],$iniParam['code']);
			}
		}

		public static function getJsonManager()
		{
			if(\app\Config::USE_JSON_FILE)
				return new \nanaPHP\FileFormatter\JsonFile();
			else{
				$jsonParam = \app\Config::app_throw();
				$jsonParam = $jsonParam['JSON_FILE_SYS_NOT_SET'];
				throw new \Exception($jsonParam['message'][\app\Config::APP_MODE],$jsonParam['code']);
			}
		}
		
		public static function copyFile($sourceFile , $destinationFile)
		{
			return copy ( $sourceFile , $destinationFile );
		}
		
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
							self::copyFile ( $sourceDir.$file , $destinationDir.$file );
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
			/*
			if(IDE != null) 
				$absoluteFolder = WWW_FOLDER . "/nanaPHP/";
			else 
				$absoluteFolder = WWW_FOLDER . PATH;
				*/
			
			//if(substr($dir,0,1)!="/" && substr($dir,1,1)!=":") $dir = $absoluteFolder.$dir;
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
			//$file = APP_VIEW_PATH . $file . APP_FILE_TYPE;
			if (file_exists($file)) {
				ob_start();
				require_once $file;
				return ob_get_clean();
			}
			else {
				throw new \Exception("Désoler! La page demandée est introuvable...",1004);
			}
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
		public static function dataToFile($fileData, $fileAbsoluteName, $other = array("spliteChar"=>";","header_data"=>"","footer_data"=>""))
		{
		   //On ouvre le fichier
		   $file = fopen($fileAbsoluteName, "wb");
		   //On charge le contenu du fichier
		   $fileContent = $other["header_data"];
		   foreach($fileData as $ligne)
		   {
				$fileContent .= implode($other["spliteChar"],$ligne) . NANAPHP_APP_EOL;
		   }
		   $fileContent .= $other["footer_data"];
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
		public static function downloadTextFile($dumpFileAbsoluteName,$delete = FALSE)
		{
		   //Chemin du fichier sur le serveur
		   //$dumpFileAbsoluteName = APP_DOWNLOAD_PATH . $dumpFileName;
		   //Téléchargement du fichier
		   $tab = explode('/',$dumpFileAbsoluteName);
		   $fileName = $tab[(count($tab)-1)];
		   header('Content-Transfer-Encoding: binary'); //Transfert en binaire (fichier).
		   //header('Content-Disposition: attachment; filename="'.$dumpFileName.'"'); //Nom du fichier.
		   header('Content-Disposition: attachment; filename="'.$fileName.'"'); //Nom du fichier.
		   header('Content-Length: '.filesize($dumpFileAbsoluteName)); //Taille du fichier.
		   readfile($dumpFileAbsoluteName);
		   if($delete) self::removeFile($dumpFileAbsoluteName);
		}
		
		public static function removeFile($fileName,$withWarning = FALSE)
		{
			if($withWarning) unlink($fileName);
			else @unlink($fileName);
		}
		
		public static function sendMail($from, $to, $object, $content)
		{
			return TRUE;
		}

		public static function sendSms($from, $to, $sms)
		{
			return TRUE;
		}

		public static function crypter($text,$cle_privee="nserpo") 
		{
			$cle_privee = md5($cle_privee);
			$letter = -1;
			$crypter = '';
			$longueur = strlen($text);
		
			for ($i = 0; $i < $longueur; $i++) {
				$letter++;
				if ($letter > 31) {
					$letter = 0;
				}
				$new_text = ord($text{$i}) + ord($cle_privee{$letter});
				if ($new_text > 255) {
					$new_text -= 256;
				}
				$crypter .= chr($new_text);
			}
			return base64_encode($crypter);
		}
			
		public static function decrypter($text, $cle_privee="nserpo") 
		{
			$cle_privee = md5($cle_privee);
			$letter = -1;
			$decrypter = '';
			$text = base64_decode($text);
			$longueur = strlen($text);
			for ($i = 0; $i < $longueur; $i++) {
				$letter++;
				if ($letter > 31) {
					$letter = 0;
				}
				$new_text = ord($text{$i}) - ord($cle_privee{$letter});
				if ($new_text < 1) {
					$new_text += 256;
				}
				$decrypter .= chr($new_text);
			}
			return $decrypter;
		}
		
		public static function dateEnToFr($date)
		{
			$tabDH=explode(" ",$date);
			$tabD=explode("-",$tabDH[0]);
			if(isset($tabDH[1]) && $tabDH[1]!="00:00:00" && $tabDH[1]!="00:00" && $tabDH[0]!='0000-00-00') return $tabD[2].'/'.$tabD[1].'/'.$tabD[0].' '.$tabDH[1];
			if(isset($tabDH[1]) && $tabDH[1]!="00:00:00" && $tabDH[1]!="00:00") return $tabDH[1];
			if($tabDH[0]!='0000-00-00') return $tabD[2].'/'.$tabD[1].'/'.$tabD[0];
			else return '-';
		}
		
		public static function dateEnToFr_2($date)
		{
			$tabDH=explode(" ",$date);
			$tabD=explode("-",$tabDH[0]);
			if(isset($tabDH[0]) && $tabDH[0]!='0000-00-00') return $tabD[2].'/'.$tabD[1].'/'.$tabD[0];
			if($tabDH[0]!='0000-00-00') return $tabD[2].'/'.$tabD[1].'/'.$tabD[0];
			else return '-';
		}
		
		public static function Date($param = 'd/m/Y H:i:s')
		{
			return @gmdate($param);
		}
	}