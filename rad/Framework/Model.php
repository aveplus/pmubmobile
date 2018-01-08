<?php
	/**
	 * Classe abstraite Modèle.
	 * Centralise les méthodes d'interactions avec les objets d'une base de données.
	 * Utilise l'API PDO de PHP
	 *
	 * @version 1.0
	 * @author Daouda GUETIKILA
	 */
	abstract class Model {
	
		/** Objet PDO d'accès à la BD 
			Statique donc partagé par toutes les instances des classes dérivées */
		private static $_connection;
		
		/** Le nom de la table du modèle dérivié */
		protected static $domaine;

		/**
		* Constructeur
		* 
		* @param PDO $connection Objet de connection à la base de donnée à la quelle la classe dérivée est s'y trouve
		* @param string $controller Nom du contrôleur auquel la vue est associée
		*/
		public function __construct($connection, $domaine) 
		{
			self::$_connection = $connection;
			self::$domaine = $domaine;
		}
					
		/**
		 * Renvoie un objet de connexion à la BDD en initialisant la connexion au besoin
		 * 
		 * @return PDO Objet PDO de connexion à la BDD
		 */
		public static function getConnection() 
		{
			return self::$_connection;
		}
		
		/**
		 * Renvoie un entier correspondant au type du paramètre à passer à PDO::bindParam ou PDO::bindValue
		 * 
		 * @return int PDO PARAM TYPE
		 */
		private function getPDOParamType($value)
		{
			 if(is_int($value)) return PDO::PARAM_INT;
			 if(is_bool($value)) return PDO::PARAM_BOOL;
			 if(is_null($value)) return PDO::PARAM_NULL;
			 return PDO::PARAM_STR;
		}

		/**
		 * Retrouve un tableau associatif contenant entre autres les colonnes, les valeurs, ... 
		 * d'une instance de la table du modèle dérivé
		 * Son objectif est de permettre de construire facilement des requêtes préparées PDO
		 *
		 * @param array $arrayObject instance d'élément de la table du modèle dérivé
		 * @return array Un tableau associatif
		 */		
		private function getPDOPrepareObject($arrayObject = array())
		{
			$Tobject = array('columns'=>array(), 'markers'=>array(), 'types'=>array(), 'values'=>array());
			foreach($arrayObject as $instanceName => $instanceValue)
			{
				$Tobject['columns'][] = $instanceName;
				$Tobject['markers'][] = '?';
				$Tobject['types'][] = $this->getPDOParamType($instanceValue);
				$Tobject['values'][] = $instanceValue;
				
			}
			$Tobject['markers'] = implode(',',$Tobject['markers']);
			return $Tobject; 
		}


		/**
		 * Retrouve un message d'exception provenant de PDOException. Ce message est reformaté pour masquer
		 * les noms des objets de la base de données
		 *
		 * @param PDOException $e une instance d'exception PDO
		 * @return string Le message d'exception reformaté selon le cas.
		 */		
		private function getPDOExceptionMsg(PDOException $e)
		{
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
			return $exceptionMsg;
		}
		
		/**
		 * Retrouve un/zéro élément de la table du modèle dérivé par son id
		 * 
		 * @param int $id Identifiant d'un élément de la table du modèle dérivé
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findOne($id, $selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." WHERE id = :id");
			$requete->bindValue(':id', $id);
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}

		/**
		 * Retrouve un/zéro élément de la table du modèle dérivé par la valeur d'une colonne quelconque à index UNIQUE
		 * 
		 * @param array $field le nom d'une colonne de la table du modèle dérivé et sa valeur
		 *
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findOneByField($field = array('name'=>'','value'=>''), $selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." WHERE ".$field['name']." =  :value LIMIT 1");
			$requete->bindValue(':value', $field['value']);
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}

		/**
		 * Retrouve un/zéro élément correspondant au premier élément de la table du modèle dérivé
		 * 
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findFirst($selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." ORDER BY id ASC LIMIT 1");
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}

		/**
		 * Retrouve un/zéro élément correspondant au dernier élément de la table du modèle dérivé
		 * 
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findLast($selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." ORDER BY id DESC LIMIT 1");
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}

		/**
		 * Retrouve un/zéro élément correspondant au premier élément de la selection sur la table du modèle dérivé
		 * 
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findFirstByField($field = array('name'=>'','value'=>''), $selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." WHERE ".$field['name']." =  :value ORDER BY id ASC LIMIT 1");
			$requete->bindValue(':value', $field['value']);
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}

		/**
		 * Retrouve un/zéro élément correspondant au dernier élément de la selection sur la table du modèle dérivé
		 * 
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findLastByField($field = array('name'=>'','value'=>''), $selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." WHERE ".$field['name']." =  :value ORDER BY id DESC LIMIT 1");
			$requete->bindValue(':value', $field['value']);
			$requete->execute();
			$donnees = $requete->fetch(PDO::FETCH_ASSOC);
			return $donnees;
		}
				
		/**
		 * Retrouve un/plusieurs élément de la table du modèle dérivé par la valeur d'une colonne quelconque
		 * 
		 * @param array $field le nom d'une colonne de la table du modèle dérivé et sa valeur
		 *
		 * @return array Un tableau associatif / FALSE selon le cas
		 */
		public function findByField($field = array('name'=>'','value'=>''), $selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine." WHERE ".$field['name']." =  :value");
			$requete->bindValue(':value', $field['value']);
			$requete->execute();
			$donnees = $requete->fetchAll(PDO::FETCH_ASSOC);
			return $donnees;
		}
		
		/**
		 * Retrouve zéro/un/des éléments de la table du modèle dérivé
		 * 
		 * @return array Un tableau associatif / FALSE selon le cas 
		 */
		public function findAll($selectedFields = '*')
		{
			$requete = self::getConnection()->prepare("SELECT ".$selectedFields." FROM ".self::$domaine);
			$requete->execute();
			$donnees = $requete->fetchAll(PDO::FETCH_ASSOC);
			return $donnees;
		}	

		/**
		 * Ajoute un élément dans la table du modèle dérivé
		 * 
		 * @param array $object Tableau contenant un élément à insérer dans la table du modèle dérivé
		 * @return le nombre de ligne ajouter ou un message d'erreur selon le cas
		 */		
		public function add($object = array())
		{
			try {
				$Tobject = $this->getPDOPrepareObject($object);
				$Tobject['columns'] = implode(',',$Tobject['columns']);
				$requete = self::getConnection()->prepare("INSERT INTO ".self::$domaine." 
																		(".$Tobject['columns'].") 
																		VALUES (".$Tobject['markers'].")");
				$nb = count($Tobject['values']);
				for($i = 0; $i < $nb; $i++){
					$requete->bindParam(($i+1), $Tobject['values'][$i], $Tobject['types'][$i]);	
				}
				$etat = $requete->execute();
				return $etat; 
			}
			catch(PDOException $e)
			{
				throw new Exception($this->getPDOExceptionMsg($e));
			}
		}
		
		/**
		 * Met à jour un élément dans la table du modèle dérivé
		 * 
		 * @param array $object Tableau contenant un élément à insérer dans la table du modèle dérivé
		 * @param int $id Identifiant d'un élément de la table du modèle dérivé
		 * @return le nombre de lignes mises à jour ou un message d'erreur selon le cas
		 */		
		public function update($object = array(),$id)
		{
			try {
				$Tobject = $this->getPDOPrepareObject($object);
				$Tobject['columns'] = implode(' = ?, ',$Tobject['columns']) . ' = ?';
				$Tobject['values'][] = $id;
				$Tobject['types'][] = $this->getPDOParamType($id);
				$requete = self::getConnection()->prepare( "UPDATE ".self::$domaine." SET ".$Tobject['columns']." WHERE id = ?");
				$nb = count($Tobject['values']);
				for($i = 0; $i < $nb; $i++){
					$requete->bindParam(($i+1), $Tobject['values'][$i], $Tobject['types'][$i]);	
				}
				$etat = $requete->execute();
				return $etat; 
			}
			catch(PDOException $e)
			{
				throw new Exception($this->getPDOExceptionMsg($e));
			}
		}

		/**
		 * Met à jour d'un/plusieurs élément(s) dans la table du modèle dérivé
		 * 
		 * @param array $object Tableau contenant un élément à insérer dans la table du modèle dérivé
		 * @param array $field le nom d'une colonne de la table du modèle dérivé et sa valeur
		 * @return le nombre de lignes mises à jour ou un message d'erreur selon le cas
		 */		
		public function updateByField($object = array(),$field = array('name'=>'','value'=>''))
		{
			try {
				$Tobject = $this->getPDOPrepareObject($object);
				$Tobject['columns'] = implode(' = ?, ',$Tobject['columns']) . ' = ?';
				$Tobject['values'][] = $field['value'];
				$Tobject['types'][] = $this->getPDOParamType($field['value']);
				$requete = self::getConnection()->prepare( "UPDATE ".self::$domaine." SET ".$Tobject['columns']." WHERE ".$field['name']." = ?");
				$nb = count($Tobject['values']);
				for($i = 0; $i < $nb; $i++){
					$requete->bindParam(($i+1), $Tobject['values'][$i], $Tobject['types'][$i]);	
				}
				$etat = $requete->execute();
				return $etat; 				
			}
			catch(PDOException $e)
			{
				throw new Exception($this->getPDOExceptionMsg($e));
			}
		}
		
		/**
		 * Supprime un élément dans la table du modèle dérivé par son id
		 * 
		 * @param int $id la valeur de l'id d'une ligne de la table du modèle dérivé
		 * @return le nombre de lignes suprimées ou un message d'erreur selon le cas
		 */		
		public function delete($id)
		{
			try {
				$requete = self::getConnection()->prepare("DELETE FROM ".self::$domaine." WHERE id = :id");
				$requete->bindValue(':id', $id);
				$etat = $requete->execute();
				return $etat;
			}
			catch(PDOException $e)
			{
				throw new Exception($this->getPDOExceptionMsg($e));
			}
		}	

		/**
		 * Supprime un/plusieurs élément(s) dans la table du modèle dérivé par la valeur d'une colonne quelconque
		 * 
		 * @param array $field le nom d'une colonne de la table du modèle dérivé et sa valeur
		 * @return le nombre de lignes suprimées ou un message d'erreur selon le cas
		 */		
		public function deleteByField($field = array('name'=>'','value'=>''))
		{
			try {
				$requete = self::getConnection()->prepare("DELETE FROM ".self::$domaine." WHERE ".$field['name']." =  :value");
				$requete->bindValue(':value', $field['value']);
				$etat = $requete->execute();
				return $etat;
			}
			catch(PDOException $e)
			{
				throw new Exception($this->getPDOExceptionMsg($e));
			}
		}	
	}