<?php
	class Projet extends Service
	{  
		/**
		* Détermine la methode à exécuter en fonction de la requête reçue
		* 
		* @param Request $request Requête reçue
		* @return string Methode à exécuter
		*/
		public static function dirFolder2Array($dir) 
		{    
			$dir = self::getValideDir($dir);
			$tab = array();
			if (is_dir($dir)) 
			{   
				if ($dirInstance = opendir($dir)) 
				{                     
					while(($file = readdir($dirInstance)) !== false) 
					{                          
						if(is_dir($dir.$file) && $file != '..'  && $file != '.')
						{
							$tab[] = array('dir' => $dir . $file, 'name' => $file);
						}
					 }
					closedir($dirInstance);
				}               
			} 
			return $tab;
		}
		
		/**
		* Instancie le contrôleur approprié en fonction de la requête reçue
		* 
		* @param Request $request Requête reçue
		* @return Instance d'un contrôleur
		* @throws Exception Si la création du contrôleur échoue
		*/
		public static function dirList($dir, $href = "", $bgColor = 'FFF', $list = "") 
		{    
			$dir = self::getValideDir($dir);
			if (is_dir($dir)) 
			{   
				$list .= "<br>".$dir."<br>";
				$tab = self::getLine(strlen($dir),$bgColor);
				if ($dirInstance = opendir($dir)) 
				{                     
					while(($file = readdir($dirInstance)) !== false) 
					{                          
						if(is_dir($dir.$file) && $file != '..'  && $file != '.')
						{
							$list = self::dirList ( $dir.$file.'/',$href,$bgColor,$list); 
						}
						 elseif($file != '..'  && $file != '.') 
						{
							if($href!="" && !self::match_ci('.php',$file)) {
								$list .= $tab . "<a href='".str_replace(WWW_FOLDER,"",$dir).$file."' title='Cliquez pour telecharger...'>".$file."</a><br>";
							}
							else $list .= $tab . $file. "<br>";
						}
					 }
					closedir($dirInstance);
				}               
			} 
			return $list;
		}

		/**
		* Gère une erreur d'exécution (exception)
		* 
		* @param Exception $exception Exception qui s'est produite
		*/		
		private static function getLine($len,$color = 'FFF')
		{
			$len += 20;
			$ligne = "<span style='color:#".$color."'>";
			for($i = 1; $i <= $len; $i++) $ligne .= '-';
			return $ligne . '</span>';
		}
		
		public static function prohibit_char_there($projet) 
		{    
			return preg_match('#[^A-Za-z0-9_]#', $projet);
		}
	}	
?>