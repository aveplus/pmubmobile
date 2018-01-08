<?php
	//namespace nanaPHP\Core;
	abstract class Sql {
		protected $_sql = null;

		public function select($champs){
			$this->_sql['SELECT'] = $champs;
			return $this;
		}

		public function from($tables){
			$this->_sql['FROM'] = $tables;
			return $this;
		}

		public function where($criteres,$parametres = null){
			$this->_sql['WHERE'] = $criteres;
			if($parametres) 
				$this->_sql['PARAMS']['WHERE'] = $parametres;
			return $this;
		}

		public function group_by($champs){
			$this->_sql['GROUP_BY'] = $champs;
			return $this;
		}

		public function rollup(){
			$this->_sql['ROLLUP'] = TRUE;
			return $this;
		}
		
		public function having($criteres,$parametres = null){
			$this->_sql['HAVING'] = $criteres;
			if($parametres) 
				$this->_sql['PARAMS']['HAVING'] = $parametres;
			return $this;
		}
						
		public function order_by($tri){
			$this->_sql['ORDER_BY'] = $tri;
			return $this;
		}
		
		public function limit($interval,$parametres = null){
			$this->_sql['LIMIT'] = $interval;
			if($parametres) 
				$this->_sql['PARAMS']['LIMIT'] = $parametres;
			return $this;
		}

		/***********************************/

		public function l_join($table,$champ){
			$this->_sql['L_JOIN'] = array($table, $champ);
			return $this;
		}
		
		public function r_join($table,$champ){
			$this->_sql['R_JOIN'] = array($table, $champ);
			return $this;
		}

		public function n_join($champ1, $champ2){
			$this->_sql['N_JOIN'][] = array($champ1, $champ2);
			return $this;
		}
		
		public function join($table,$champ){
			$this->_sql['JOIN'] = array($table, $champ);
		}

		public function pivot($champ1,$champ2){
			$this->_sql['PIVOT'] = array($champ1, $champ2);
			return $this;
		}	

		public function remove($clause = null){
			if($clause==null) 	$this->_sql = null;
			else				unset(strtoupper($this->_sql[$clause]));
			return $this;
		}
		
		public function fetch()
		{
			$requete = $this->getConnection()->prepare($this->get_sql_string());
			$params = $this->get_params();
			if(count($params['values'])>0)
				$this->PDOBindParam($requete,$params['values'],$params['types']);
			$requete->execute();
			$donnees = $requete->fetch(\PDO::FETCH_ASSOC);
			return $donnees;
		}

		public function fetchAll()
		{
			$requete = $this->getConnection()->prepare($this->get_sql_string());
			$params = $this->get_params();
			if(count($params['values'])>0)
				$this->PDOBindParam($requete,$params['values'],$params['types']);
			$requete->execute();
			$donnees = $requete->fetchAll(\PDO::FETCH_ASSOC);
			return $this->transform($donnees);
		}

		public function compile(){
			if(!isset($this->_sql['SELECT']))
				trigger_error(sprintf('Requête SQL invalide; votre requête SQL ne comporte pas de clause SELECT.<br>
									  Comment ajouter une clause "SELECT" ?<br>
									  "$YourObject->SELECT(\'e.idEleve,nom,prenom,AVG(note) AS Moyenne\');"'), E_USER_WARNING);
			elseif(!isset($this->_sql['FROM']))
				trigger_error(sprintf('Requête SQL invalide; votre requête SQL ne comporte pas de clause FROM.<br>
									  Comment ajouter une clause "FROM" ?<br>
									  "$YourObject->from(\'eleve e,devoir\');"'), E_USER_WARNING);
			else
				$sql = 'SELECT ' . $this->_sql['SELECT'] . ' FROM ' . $this->_sql['FROM'];

			if(isset($this->_sql['PIVOT'])){
				$select = explode(',', $this->_sql['SELECT']);
				for($i=0; $i<count($select); $i++){
					$select[$i] = trim($select[$i]);
				}
				if(!in_array($this->_sql['PIVOT'][0],$select) || !in_array($this->_sql['PIVOT'][1],$select))
					trigger_error(sprintf('Requête SQL invalide; Vous avez activer la transformation des données en tableau croisé dynamique.<br>
										   Il est obligatoire dans ce cas definir le champ "COLONNE" et le champ "VALEUR" dans la clause SELECT'), E_USER_WARNING);
			}
			
			if(isset($this->_sql['L_JOIN']) || isset($this->_sql['R_JOIN']) || isset($this->_sql['JOIN'])){
				$countFrom = count(explode(',',$this->_sql['FROM']));
				if($countFrom>1)
					trigger_error(sprintf('Requête SQL invalide; votre requête SQL ne peut pas utliser "JOIN" ou "LEFT JOIN" ou encore "RIGHT JOIN".<br>
										   Vous citez déjà plusieurs tables dans la clause FROM. Comment remédier à ce problème?.<br>
										   "$YourObject->remove(\'JOIN\');
										    $YourObject->remove(\'L_JOIN\');
											$YourObject->remove(\'R_JOIN\');
										    $YourObject->n_join(\'e.idEleve\',\'devoir.idEleve\');"'), E_USER_WARNING);
				else{					  
					$sql = isset($this->_sql['L_JOIN']) ? $sql . ' LEFT JOIN '.$this->_sql['L_JOIN'][0].' USING('.$this->_sql['L_JOIN'][1].')'  : $sql;
					$sql = isset($this->_sql['R_JOIN']) ? $sql . ' RIGHT JOIN '.$this->_sql['R_JOIN'][0].' USING('.$this->_sql['R_JOIN'][1].')' : $sql;
					$sql = isset($this->_sql['JOIN']) 	? $sql . ' JOIN '.$this->_sql['JOIN'][0].' USING('.$this->_sql['JOIN'][1].')' 			: $sql;
				}
			}
			
			if(isset($this->_sql['WHERE'])){
				$countWhere = count(explode('?',$this->_sql['WHERE'])) - 1;
				$nbreMarkers = isset($this->_sql['PARAMS']['WHERE']) ? count($this->_sql['PARAMS']['WHERE']) : 0;
				if($countWhere != $nbreMarkers)
					trigger_error(sprintf('Requête SQL invalide; votre requête SQL utilise '.$countWhere.' marker(s) "?" de requête préparé dans la clause "WHERE".<br>
										   Vous devriez utiliser autant de paramètres. Comment remédier à ce problème?<br>
										   "$YourObject->where(\'(e.classe=? OR e.classe="1èreD") AND devoir.note>=?\',array("TleD","10"));"'), E_USER_WARNING);
				else
					$sql = $sql . ' WHERE('.$this->_sql['WHERE'].')';
			}
						
			if(isset($this->_sql['N_JOIN'])){
				foreach($this->_sql['N_JOIN'] as $join){
					$sql = isset($this->_sql['WHERE'])	? $sql . ' AND ('.$join[0].'='.$join[1].')'	: $sql . ' WHERE('.$join[0].'='.$join[1].')';
				}
			}
	
			if(isset($this->_sql['GROUP_BY'])){
				$sql = $sql . ' GROUP BY '.$this->_sql['GROUP_BY'];
				$sql = isset($this->_sql['ROLLUP']) ? $sql . ' WITH ROLLUP' : $sql;
					
					if(isset($this->_sql['HAVING'])){
						if(isset($this->_sql['PARAMS']['HAVING'])){
							$countWhere = count(explode('?',$this->_sql['HAVING'])) - 1;
							$nbreMarkers = count($this->_sql['PARAMS']['HAVING']);
							if($countWhere != $nbreMarkers)
								trigger_error(sprintf('Requête SQL invalide; votre requête SQL utilise '.$countWhere.' marker(s) "?" de requête préparé dans la clause "HAVING".<br>
													   Vous devriez utiliser autant de paramètres. Comment remédier à ce problème?<br>
													   "$YourObject->having(\'(Moyenne >= ?\',array(10));"'), E_USER_WARNING);						
						}
						$sql = $sql . ' HAVING '.$this->_sql['HAVING'];
					}
			}
			elseif(isset($this->_sql['ROLLUP']) || isset($this->_sql['HAVING'])){
					trigger_error(sprintf('Requête SQL invalide; votre requête SQL n\'utilise pas de clause "GROUP BY".<br>
										  Vous ne devrier pas utiliser de classe "HAVING" ou "WITH ROLLUP" dans votre requête. Comment remédier à ce problème?
										   "$YourObject->remove(\'HAVING\');" // Ou encore<br>
										   "$YourObject->remove(\'ROLLUP\'); // Ou encore<br>
										   "$YourObject->group_by(\'e.classe\');'), E_USER_WARNING);
			}
			
			$sql = isset($this->_sql['ORDER_BY']) 	? $sql . ' ORDER BY '.$this->_sql['ORDER_BY'] : $sql;
			
			if(isset($this->_sql['LIMIT'])){
				if(isset($this->_sql['PARAMS']['LIMIT'])){
					$countWhere = count(explode('?',$this->_sql['LIMIT'])) - 1;
					$nbreMarkers = count($this->_sql['PARAMS']['LIMIT']);
					if($countWhere != $nbreMarkers)
						trigger_error(sprintf('Requête SQL invalide; votre requête SQL utilise '.$countWhere.' marker(s) "?" de requête préparé dans la clause "LIMIT".<br>
											   Vous devriez utiliser autant de paramètres. Comment remédier à ce problème?<br>
											   "$YourObject->limit(\'(?,?\',array(0,20));"'), E_USER_WARNING);						
				}
				$sql = $sql . ' LIMIT '.$this->_sql['LIMIT'];
			}
			die('Code source:<br>' . $sql.'<br><br>');
		}
			
		private function get_sql_string(){
			$sql = 'SELECT ' . $this->_sql['SELECT'] . ' FROM ' . $this->_sql['FROM'];
			
			$sql = isset($this->_sql['L_JOIN']) 		? $sql . ' LEFT JOIN '.$this->_sql['L_JOIN'][0].' USING('.$this->_sql['L_JOIN'][1].')'  : $sql;
			$sql = isset($this->_sql['R_JOIN']) 		? $sql . ' RIGHT JOIN '.$this->_sql['R_JOIN'][0].' USING('.$this->_sql['R_JOIN'][1].')' : $sql;
			$sql = isset($this->_sql['JOIN']) 			? $sql . ' JOIN '.$this->_sql['JOIN'][0].' USING('.$this->_sql['JOIN'][1].')' 			: $sql;
			
			$sql = isset($this->_sql['WHERE']) 			? $sql . ' WHERE('.$this->_sql['WHERE'].')' : $sql;
			
			if(isset($this->_sql['N_JOIN'])){
				foreach($this->_sql['N_JOIN'] as $join){
					$sql = isset($this->_sql['WHERE'])	? $sql . ' AND ('.$join[0].'='.$join[1].')'		: ' WHERE('.$join[0].'='.$join[1].')';
				}
			}
			$sql = isset($this->_sql['GROUP_BY']) 	? $sql . ' GROUP BY '.$this->_sql['GROUP_BY'] : $sql;
			$sql = isset($this->_sql['ROLLUP']) 	? $sql . ' WITH ROLLUP' 						: $sql;
			$sql = isset($this->_sql['HAVING']) 	? $sql . ' HAVING '.$this->_sql['HAVING'] 	: $sql;
			$sql = isset($this->_sql['ORDER_BY']) 	? $sql . ' ORDER BY '.$this->_sql['ORDER_BY'] : $sql;
			$sql = isset($this->_sql['LIMIT']) 		? $sql . ' LIMIT '.$this->_sql['LIMIT'] 		: $sql;
			return $sql;
		}

		private function get_params(){
			$param_values = array();
			$param_values['values'] = array();
			$param_values['types'] = array();
			if(isset($this->_sql['PARAMS'])){
				foreach($this->_sql['PARAMS'] as $params){
					foreach($params as $param){
						$param_values['values'][] = $param;
						$param_values['types'][] = $this->getPDOParamType($param);
					}
				}
			}
			return $param_values;
		}

		private function transform($donnees)
		{
			/* Si le tableau des données a transformées est vide */
			if(!$donnees) 						return $donnees;
			/* La transformation en tableau croisé n'est pas activé? */
			if(!isset($this->_sql['PIVOT'])) 	return $donnees;

			$data = array();
			$data_pivot = array();
			$pivot = $this->_sql['PIVOT'];
			/* Si les colonnes pivot ne sont pas dans la liste des champs déclencher une erreur et mettre fin au script */
			if(!isset($donnees[0][$pivot[0]],$donnees[0][$pivot[1]])){
				die('Requête SQL invalide; Vous avez activer la transformation des données en tableau croisé dynamique.<br>
							   Il est obligatoire dans ce cas de definir le champ "COLONNE" et le champ "VALEUR" dans la clause SELECT');	
			}
			/* $id_list = array(); ==> Permet de gérer les regroupement, les changement de ligne */
			$id_list = array();	
			/* $list_col_pivot = array(); ==> Enregiste les nouvelles colonnes créés à partir du pivot "colonne" */			
			$list_col_pivot = array();
			/* $list_col_group_by = array(); ==> Liste des colonnes de regroupement */
			$list_col_group_by = array();
			foreach($donnees[0] as $champ => $valeur){
				if($champ!=$pivot[0] && $champ!=$pivot[1]){
					$list_col_group_by[] = $champ;
				}
			}
			/* On parcours les données à transformées en tableau croisé dynamique */
			foreach($donnees as $ligne){
				/* Si l'on vient de croiser la première occurence de la valeur courante de la colonne pivot l'enregistrer */
				if(!in_array($ligne[$pivot[0]],$list_col_pivot)){
					$list_col_pivot[] = $ligne[$pivot[0]];
				}
				
				/* Calcul de l'id de la ligne courante: L'id est constitué de la concaténation des valeurs des colonnes de récroupement */
				$id_ligne = $this->get_id_ligne($ligne,$pivot);
				/* Initialisation des colonnes de regroupement */
				foreach($list_col_group_by as $col){
					$data[$id_ligne][$col] = $ligne[$col];
				}
				/* Initialisation de la colonne pivot avec la colonne valeur pivot */
				$data_pivot[$id_ligne][$ligne[$pivot[0]]] = $ligne[$pivot[1]];
			}
			/* Ajout des colonnes manquantes aux enregistrement */
			foreach($data_pivot as $id_ligne => $ligne){
				foreach($list_col_pivot as $col){
					if(!isset($data_pivot[$id_ligne][$col]))
						$data_pivot[$id_ligne][$col] = '';
				}
				ksort($data_pivot[$id_ligne]);
				$data[$id_ligne] = array_merge($data[$id_ligne],$data_pivot[$id_ligne]);
			}					
			return $data;
		}

		private function get_id_ligne($ligne,$pivot){
			unset($ligne[$pivot[0]]);
			unset($ligne[$pivot[1]]);
			return implode('-',$ligne);
		}
		
		private function transform_copie($donnees)
		{
			/* La transformation en tableau croisé est activé? */
			if($this->_sql['PIVOT'])
			{
				$data = null;
				$pivot = $this->_sql['PIVOT'];
				/* $id_list = array(); ==> Permet de gérer les regroupement, les changement de ligne */
				$id_list = array();	
				/* $list_col_pivot = array(); ==> Enregiste les nouvelles colonnes créés à partir du pivot "colonne" */			
				$list_col_pivot = array();
				/* $list_col_group_by = array(); ==> Liste des colonnes de regroupement */
				$list_col_group_by = array();
				
				/* On parcours les données à transformées en tableau croisé dynamique */
				foreach($donnees as $ligne){
					
					/* Si nous sommes à la première itération */
					if(!$data){
						/* Si les colonnes pivot ne sont pas dans la liste des champs déclencher une erreur et mettre fin au script */
						if(!isset($ligne[$pivot[0]],$ligne[$pivot[1]])){
							die('Requête SQL invalide; Vous avez activer la transformation des données en tableau croisé dynamique.<br>
										   Il est obligatoire dans ce cas definir le champ "COLONNE" et le champ "VALEUR" dans la clause SELECT');	
						}
						/* Initialisation des colonnes de regroupements */
						foreach($ligne as $key => $value){
							if($key!=$pivot[0] && $key!=$pivot[1]){
								$data[$key] = array();
								$list_col_group_by[] = $key;
							}
						}
					}
					
					/* Si la valeur du champ colonne pivot n'est pas encore initialisée comme colonne, l'initialisée */
					if(!isset($data[$ligne[$pivot[0]]])){
						$data[$ligne[$pivot[0]]] = array();
						$list_col_pivot[] = $ligne[$pivot[0]];
						/* Remplaçage des ligne de la nouvelle colonne vide */
						for($i=0;$i<count($id_list);$i++){
							if(count($data[$col])<$nbLigne)
								$data[$ligne[$pivot[0]]][] = '';
						}
					}
					
					/* Mettre la valeur de la colonne valeur pivot dans colonne pivotée */
					$data[$ligne[$pivot[0]]][] = $ligne[$pivot[1]];
					
					/* Création de l'identifiant de la ligne du tableau croisé dynamique */
					unset($ligne[$pivot[0]]);
					unset($ligne[$pivot[1]]);
					$id = implode('-',$ligne);
					
					if(!in_array($id,$id_list)){
						/* Sauvegarde de l'identifiant de la ligne courante du tableau croisé dynamique */
						$id_list[] = $id;
						/* Initialisé les colonnes pivotées qui n'ont pas eu de valeur pour la ligne récédente */
						$nbLigne = count($id_list);
						foreach($list_col_pivot as $col){
							if(count($data[$col])<$nbLigne)
								$data[$col][] = '';
						}
						/* Mettre la valeur des colonnes de regroupement dans le nouvelle ligne du tableau */
						foreach($ligne as $key => $value){
							$data[$key][] = $value;
						}
					}
				}
				
				/** Construction du tableau croisé dynamique **/
				/* Fusion de toutes les colonnes */
				$list_col_group_by = array_merge($list_col_group_by,$list_col_pivot);
				/* Construction du tableau */
				$donneesRetour = array();
				for($i=0;$i<$nbLigne;$i++){
					foreach($list_col_group_by as $col){
						$donneesRetour[$i][$col] = $data[$col][$i];
					}
				}
				return $donneesRetour;
			}
			return $donnees;
		}
	}
