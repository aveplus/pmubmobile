<?php
	namespace nanaPHP\DataConnector;
	/**
	 * Classe gérant les connection aux bases de données.
	 * Centralise les services d'accès à une base de données.
	 * Utilise l'API PDO de PHP
	 *
	 * @version 1.0
	 * @author Daouda GUETIKILA
	 */
	final class Database extends \PDO{
	
		/** Objet PDO d'accès à la BD 
		 */
		private $_connection = null;
		
		public function __construct($DB_LIST, $DB_ID = 1)
		{
			// Récupération des paramètres de configuration BD
			$DB_URL = $this->getDB_URL($DB_LIST, $DB_ID);
			
			// Création de la connexion
			try {
				$this->_connection = new \PDO($DB_URL['DB_DSN'], 
											$DB_URL['DB_USER'], 
											$DB_URL['DB_PWD'],
											array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION, \PDO::ATTR_AUTOCOMMIT=>true, \PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'UTF8'",));	
			}
			catch(\PDOException $e)
			{
				if(\app\Config::APP_MODE=='prod'){
					$exceptionMsg = "Petit problème: La base de données est temporairement indisponible; cela pourrait être lié à votre connexion...";
				}
				else{
					$exceptionMsg = $e->getMessage();
				}
				throw new \Exception($exceptionMsg);
			}
		}	

		/**
		 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
		 * 
		 * @return array Tableau contenant les paramètres de connexion à la BD
		 *
		 * @param int $DB_ID L'identifiant de la base de données utilisée
		 */		
		private function getDB_URL($DB_LIST, $DB_ID = 1)
		{
			foreach($DB_LIST as $KEY => $DB_URL) 
			{
				if($KEY == $DB_ID) return $DB_URL;
			}
		}
		
		/**
		 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
		 * 
		 * @return PDO Objet PDO de connexion à la BDD
		 *
		 * @param int $DB_ID L'identifiant de la base de données utilisée
		 */
		public function get() 
		{
			return $this->_connection;
		}
	}