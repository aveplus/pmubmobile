<?php 
	namespace nanaPHP\FileFormatter;
	class IniFile
	{  
		public static function getElement($file_name,$item) 
		{
			if (@file_exists($file_name))
			{
				$var = @parse_ini_file($file_name,true);
				if(isset($var[$item])) return $var[$item];
			}
			return null;
		}
		
		public static function getListe($file_name) 
		{
			if (@file_exists($file_name))
			{
				$var = @parse_ini_file($file_name,true);
				if(count($var)>0) return $var;
			}
			return null;
		}
		
		public static function add(&$data_table,$data,$key) {
			if(isset($data_table[$key])) return false;
			if($data_table == null) $data_table = array();
			$data_table[$key] = $data;
			return true;
		}
		
		public static function update(&$data_table,$data,$key,$updated_key) 
		{
			if(!isset($data_table[$updated_key])) return 0;
			if($updated_key != $key && isset($data_table[$key])) return -1;

			$new_content = array();
			foreach($data_table as $item => $ligne) 
			{
				if($item != $updated_key)
					$new_content[$item] = $ligne;
				else {
					$new_content[$key] = $data;
				}
			}
			$data_table = $new_content;
			return 1;
		}
		
		public static function delete(&$data_table,$key) 
		{
			if(!isset($data_table[$key])) return 0;
			unset($data_table[$key]);
			return 1;
		}
		
		public static function save($file_name, $data_table, $type='table') 
		{
			if($data_table != null && @count($data_table) != 0) 
			{
				$new_content = "; Date de la dernière mise à jour: ".@gmdate('d/m/Y')." à ".@gmdate('H:i:s').NANAPHP_APP_EOL . NANAPHP_APP_EOL;
				if($type == 'table'){
					foreach($data_table as $item => $ligne) {
						
						$new_content .= '['. $item .']' .NANAPHP_APP_EOL. self::array_to_ini($ligne) . NANAPHP_APP_EOL;
					}
				}
				else{
					$new_content .= self::array_to_ini($data_table) . NANAPHP_APP_EOL;
				}
				@unlink($file_name);
				file_put_contents($file_name, $new_content);
			}
			return true;
		}
		
		private static function array_to_ini($array_data) 
		{
			$ini_data = "";
			foreach($array_data as $item => $value) 
			{
				$ini_data .= $item . ' = ' . $value . NANAPHP_APP_EOL;
			}
			return $ini_data;
		}
		
		public static function echoTest($data)
		{		
			echo "<pre>";	
			print_r($data);
			echo '</pre><br><hr><br>';	
		}			
	}	
?>