<?php
	class Pays extends Model
	{   
		public function __construct($connection)
		{
			parent::__construct($connection,'pays');
		}	
		
		/**
		 * Lesméthodes sont directement utilisable, oubien, vous pouvez les redifinir de la sorte:
		 * Ici, j'atoute la gestion des transaction à la méthode add($object = array()).
		 */
		public function add($object = array())
		{
			self::getConnection()->beginTransaction();
			try{
				parent::add($object);
				self::getConnection()->commit();
			}
			catch(Exception $e){
				self::getConnection()->rollBack();
				echo $e->getMessage();
			}			
		}
	}