<?php
	namespace nanaPHP\Security;
	/**
	* Classe modélisant un Firewall
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	final class Filter 
	{
 		public $Validate = null;
		public $Clean = null;

		function __construct() 
		{
			$this->Validate = new Validate();
			$this->Clean 	= new Clean();
		}
		
		public static function basic($data) 
		{
			if(is_array($data)){
				$keys = array_keys($data);
				foreach($keys as $key){
					$data[$key] = self::basic($data[$key]);
				}
			}
			else{
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
			}
			return $data;
		}
	}
	
	/**
	* Classe modélisant un Firewall
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	class Validate 
	{
		public static function int($value,$option = array())
		{
			//if (!filter_var($value, FILTER_VALIDATE_INT,$option) === false) {
			if (filter_var($value, FILTER_VALIDATE_INT,$option) === 0 || !filter_var($value, FILTER_VALIDATE_INT,$option) === false){
				return $value;
			} else {
				return null;
			}
		}

		public static function bool($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_BOOLEAN,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}

		public static function float($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_FLOAT,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}

		public static function email($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_EMAIL,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}

		public static function ip($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_IP,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}

		public static function regexp($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_REGEXP,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}

		public static function url($value,$option = array())
		{
			if (!filter_var($value, FILTER_VALIDATE_REGEXP,$option) === false) {
				return $value;
			} else {
				return null;
			}
		}
	}
	
	/**
	* Classe modélisant un Firewall
	* 
	* @version 1.0
	* @author Daouda GUETIKILA
	*/
	final class Clean 
	{

	}
	