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
	class Config {
		/*
		* Module de gestion d'envoi et reception de SMS
		* 
		* Paramètres de l'application du module SMS.
		*
		* @version 1.0
		* @create 27/01/2017
		* update 30/01/2017
		* @author Daouda GUETIKILA
		* @email daouda@aveplus.net
		*/
			
		/* Infos générales */
		const APP_MODE 				= 'dev';
		const SMS_API_BASE 			= 'http://ec2-54-246-170-67.eu-west-1.compute.amazonaws.com:13013/cgi-bin/sendsms';
		const SMS_API_USER 			= 'aveplus';
		const SMS_API_PW 			= 'AvePlus@001';
		const SMS_FROM 				= '339';
		const SMS_DLR_URL 			= '';
		const SMS_FROM_PREFIX 		= '226';
		const SMS_DLR_DEFAULT_STATE = 0;
		
		const SMS_LIMIT = 100;	/* Nombre de sms chaque n (SMS_PAUSE) secondes */
		const SMS_PAUSE = 5;	/* En seconde */
		
		/* Database Infos */
		const SMS_HOST 		= 'localhost';
		const SMS_DB_PORT 	= '3306';
		const SMS_DB_NAME 	= 'pmubmobile_db';
		const SMS_DB_USER		= 'adm_lonab';
		const SMS_DB_PW		= 'Lon@Usr*14';
		
		const SMS_TABLE_SENT 			= 'smslib_sent';
		const SMS_TABLE_SEND 			= 'smslib_send';
		const SMS_DEFAULT_DOMAINE 	= 'general';
		const SMS_DEFAULT_DOMAINE_ID	= 0;
		
		const SMS_FLAG_URGENT 	= 0; 		/* Ticket de jeu ou duplicatat de ticket */
		const SMS_FLAG_NORMAL 	= 1;		/* Réponse d'une transaction */
		const SMS_FLAG_MOYEN 		= 2;		/* Réponse à l'action de l'utilisateur # d'un ticket de jeu et d'une transaction */
		const SMS_FLAG_SYSTEME 	= 3;		/* Evènement déclenché par le système */
		const SMS_FLAG_PUSH 	= 4;		/* SMS Push prorammé pour être envoyer aux utilisateurs */
		const SMS_FLAG_STRING	= '0,1,2,3,4';

		const SMS_SEND_START_HOUR 	= '08:00:00';
		const SMS_SEND_STOP_HOUR 		= '17:50:00';
		const SMS_ALLOWED_TO_BE_SENT = '0,1,2';
						
		public static function send_repeat_value($flag){
			switch($flag)
			{
				case self::SMS_FLAG_URGENT: 	return 3;
				case self::SMS_FLAG_NORMAL: 	return 2;
				case self::SMS_FLAG_MOYEN: 		return 1;
				case self::SMS_FLAG_SYSTEME: 	return 1;
				default: 						return 0; 	break;
			}			
		}
		
		public static function url($sms){
			$sms['dlr_url'] = urlencode(str_replace('[DLR_ID]',$sms['dlr_id'],$sms['dlr_url']));
			$url = self::SMS_API_BASE.'?'.'username='.self::SMS_API_USER.'&password='.self::SMS_API_PW;
			return $url.'&to='.$sms['receiver'].'&text='.urlencode($sms['contenu']).'&from='.$sms['sender'].'&dlr-url='.$sms['dlr_url'].'&dlr-mask=31';
		}
		
		public static function special_chars($contenu){
			$chars = array('à'=>'a', 'á'=>'a', 'â'=>'a', 'ä'=>'a', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ñ'=>'N', 'ç'=>'c', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ÿ'=>'y', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'œ'=>'oe', 'À'=>'A', 'Á'=>'A', 'Â'=>'A','Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=> 'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Œ'=>'OE', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Ÿ'=>'Y');
			return strtr($contenu,$chars);
		}
		
		public static function send($sms){
			return @file(self::url($sms));
		}

		public static function get_flag(){
			$heure = @gmdate('H:i:s');
			if($heure >= self::SMS_SEND_START_HOUR && $heure <= self::SMS_SEND_STOP_HOUR)
				return self::SMS_FLAG_STRING;
			return self::SMS_ALLOWED_TO_BE_SENT;
		}
	}