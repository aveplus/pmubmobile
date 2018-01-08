<?php
	/**
	 * Classe gérant les connection aux bases de données.
	 * Centralise les services d'accès à une base de données.
	 * Utilise l'API PDO de PHP
	 *
	 * @version 1.0
	 * @author Daouda GUETIKILA
	 */
	class Database {
	
		/** Objet PDO d'accès à la BD 
			Statique donc partagé par toutes les instances des classes dérivées */
		private static $_connection;
		
		/**
		 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
		 * 
		 * @return array Tableau contenant les paramètres de connexion à la BD
		 *
		 * @param int $DB_ID L'identifiant de la base de données utilisée
		 */		
		private static function getDB_URL($DB_ID = 1)
		{
			global $_APP; 
			foreach($_APP['DB_URL'] as $DB_URL) 
			{
				if($DB_URL['DB_ID'] == $DB_ID) return $DB_URL;
			}
		}
		
		/**
		 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
		 * 
		 * @return PDO Objet PDO de connexion à la BDD
		 *
		 * @param int $DB_ID L'identifiant de la base de données utilisée
		 */
		public static function get($DB_ID = 1) 
		{
			if (self::$_connection === null) {
				// Récupération des paramètres de configuration BD
				$DB_URL = self::getDB_URL($DB_ID);
				
				// Création de la connexion
				try {
					self::$_connection = new PDO($DB_URL['DB_DSN'], 
												$DB_URL['DB_USER'], 
												$DB_URL['DB_PWD'],
												array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_AUTOCOMMIT => false, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",));	
				}
				catch(PDOException $e){
					if(strstr($e->getMessage(), 'SQLSTATE[')) 
					{
						$info = $e->errorInfo;
						$exceptionMsg = "";
						if(isset($info[0])) $exceptionMsg = "SQLSTATE[" . $info[0] . "]: Erreur de syntaxe ou violation de contrainte d'intégrité: ";
						if(isset($info[1])) $exceptionMsg .= $info[1] . " Vous avez une erreur dans votre syntaxe SQL; consultez le manuel qui correspond à votre version du serveur MySQL pour utiliser la bonne syntaxe!";
						if($exceptionMsg == "") $exceptionMsg = "SQLSTATE: Une erreur est survenue lors de l'exécution du script SQL, veuillez revoir le code de ce script!";
					}				
					else 
						$exceptionMsg = $e->getMessage();
					throw new Exception($exceptionMsg);
				}
        	}			
			return self::$_connection;
		}
	}