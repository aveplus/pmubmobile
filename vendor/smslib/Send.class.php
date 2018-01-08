<?php
	namespace Smslib;
	/**
	 * Classe abstraite Modèle.
	 * Centralise les méthodes d'interactions avec les objets d'une base de données.
	 * Utilise l'API PDO de PHP
	 *
	 * @version 1.0
	 * @author Daouda GUETIKILA
	 */
	class Send extends Model
	{   
		public function __construct($connection = null, $domaine='smslib_send')
		{
			parent::__construct($connection,$domaine);
		}
		
		public function send($sms)
		{
			$sms['contenu'] 			= 		Config::special_chars($sms['contenu']);
			if(!isset($sms['flag'])) 			$sms['flag'] 			= Config::SMS_FLAG_PUSH;
			$sms['send_repeat']			= 		Config::send_repeat_value($sms['flag']);
			if(!isset($sms['sender'])) 			$sms['sender'] 			= Config::SMS_FROM;
			if(!isset($sms['dlr_url'])) 		$sms['dlr_url'] 		= Config::SMS_DLR_URL;
			if(strlen($sms['receiver'])==8) 	$sms['receiver'] 		= Config::SMS_FROM_PREFIX.$sms['receiver'];
			if(!isset($sms['date_heure_sms'])) 	$sms['date_heure_sms'] 	= @gmdate('Y-m-d H:i:s');
			if(!isset($sms['domaine'])) 		$sms['domaine'] 		= Config::SMS_DEFAULT_DOMAINE;
			if(!isset($sms['domaine_id'])) 		$sms['domaine_id'] 		= Config::SMS_DEFAULT_DOMAINE_ID;
			
			$this->add($sms);
		}
	}
?>