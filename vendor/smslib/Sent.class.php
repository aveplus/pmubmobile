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
	class Sent extends Model
	{   
		public function __construct($connection = null,$domaine='smslib_sent')
		{
			parent::__construct($connection,$domaine);
		}
		
		public function sent()
		{
			$sms_data = $this->select('*')
							 ->from(Config::SMS_TABLE_SEND)
							 ->where('flag IN ('.Config::get_flag().')')
							 ->order_by('flag DESC, date_heure_sms ASC')
							 ->limit(Config::SMS_LIMIT)
							 ->fetchAll();
			
			while($sms_data)
			{
				foreach($sms_data as $sms)
				{
					$sms['dlr_state'] 		= Config::SMS_DLR_DEFAULT_STATE;
					$sms['date_heure_envoi']= @gmdate('Y-m-d H:i:s');;
					$etat_add 				= $this->add($sms);
					$sms['dlr_id'] 			= $this->getLastId();
					Config::send($sms);
					if($etat_add)
						$this->delete($sms['id'],Config::SMS_TABLE_SEND);
				}
				sleep(Config::SMS_PAUSE);
				$sms_data = $this->select('*')
								 ->from(Config::SMS_TABLE_SEND)
								 ->where('flag IN ('.Config::get_flag().')')
								 ->order_by('flag DESC, date_heure_sms ASC')
								 ->limit(Config::SMS_LIMIT)
								 ->fetchAll();
			}
		}

		public function update_dlr_state($id,$etat)
		{
			return $this->update(array('dlr_state'=>$etat),$id);
		}		

		public function udpate_sent_repeat_count($id)
		{
			try{
				$requete = self::connection()->prepare("UPDATE ".$this->_domaine." SET send_repeat_count = send_repeat_count+'1' WHERE id = :id");
				$requete->bindValue(':id', $id); 
				return $requete->execute();
			}
			catch(\PDOException $e)
			{
				throw new \Exception($this->getPDOExceptionMsg($e));
			}
		}
	}
?>