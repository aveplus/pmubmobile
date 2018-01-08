<?php
	namespace nanaPHP\FileFormatter;
	class JsonFile
	{  
		private static function utf8_encode_all(&$input) 
		{
			if (is_string($input)) 
				$input = utf8_encode($input); 
			elseif (is_array($input)) 
			{
				foreach ($input as &$value) 
				{
					self::utf8_encode_all($value);
				}
				unset($value);
			} 
			elseif (is_object($input)) 
			{
				$vars = array_keys(get_object_vars($input));
				foreach ($vars as $var) 
				{
					self::utf8_encode_all($input->$var);
				}
			}
		}

		public static function json_encode($contents, $option = 0)
		{
			self::utf8_encode_all($contents);
			return json_encode($contents,$option);
		}
		
		public static function json_decode($contents, $assoc = false, $depth = 512)
		{
			return json_decode($contents,$assoc,$depth);
		}
				
		public static function json_file_get_contents($filename)
		{
			if (!file_exists($filename)) return null;
			$contents = file_get_contents($filename);
			if($contents=="") return null;
			return self::json_decode($contents,true);
		}
		
		public static function json_file_put_contents($filename,$contents)
		{
			$last_contents = self::json_file_get_contents($filename);
			if ($last_contents != null) unlink($filename);
			else $last_contents = array();
			self::utf8_encode_all($contents);
			$last_contents[] = $contents;
			file_put_contents($filename,json_encode($last_contents));
			return TRUE;
		}

		public static function json_file_save_contents($filename,$contents)
		{
			@unlink($filename);
			self::utf8_encode_all($contents);
			file_put_contents($filename,json_encode($contents));
			return TRUE;
		}		
		
		public static function echoTest($data)
		{		
			echo "<pre>";	
			print_r($data);
			echo '</pre><br><hr><br>';	
		}			
	}	
?>