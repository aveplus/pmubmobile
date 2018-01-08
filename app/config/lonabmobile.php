<?php

        use \lonabmobile\service\Service;

namespace app{	
	if(TRUE){ 
		error_reporting(E_ALL);
		ini_set('display_errors','On');
	}
        
	class Config extends \nanaPHP\Core\Config {
		/** 
		* Constantes définies par défaut. 
		* Vous serez amenez à modifier ce fichier.
		* Dans le cas contraire le Framework utilisera des valeurs par défaut ou affichera un message d'erreur!
		*
		* @version 1.0
		* @author Daouda GUETIKILA
		*/
               private static $app_var;

               /** 
		* Constantes par défaut de nanaPHP 
		* Modifiez les valeurs de ces constantes pour adapter à votre projet
		*/	
		const APP_SRC_PATH 				= 'src/lonabmobile/';
		const APP_MODE 					= 'dev';
		const APP_NAME 					= 'LONAB Mobile';
		const APP_DEFAULT_CONTROLLER 	= 'Groupe';
		const APP_VIEW_TITLE_MODEL 		= '[CONTROLER] | [APP_NAME]';  /* [CONTROLER], [APP_NAME], [METHODE]*/
		const APP_LANG 					= 'fr';
		
		const NUMERO_COURT = 1153;
		
		const APP_REQUEST_SECURITY_BASIC_ON = TRUE;
		const APP_REQUIRE_MODEL_BY_METHOD 	= FALSE;
		
		const APP_USE_SGBD 		= TRUE;
		const APP_USE_INI_FILE 	= TRUE;
		const APP_USE_JSON_FILE = TRUE;
		//les OPERATIONS
		const TRANSFERT 	= 1;
		const CONSOMMATION 	= 2; 
		const ACHAT 		= 3;
		const VENTE_JETON 	= 4;
		const EMISSION 		= 5;
		const REMBOURSEMENT = 6;
		const FACTURATION 	= 7;
		
		//COMPTE VIRTUELLE
		const COMPTE_VIRT_LONAB 	= -1;
		const COMPTE_VIRT_AGENCE 	= -2;
		const COMPTE_VIRT_PDV 		= -3;
		
		//DEBIT-CREDIT
		const MOT_CLE_DEBIT 	= 'debit';
		const MOT_CLE_CREDIT 	= 'credit';
		

		/** 
		* Constantes par défaut de nanaPHP 
		* Modifiez les valeurs de ces constantes pour adapter à votre projet
		* 
		* @author Daouda GUETIKILA
		*/	
		const ETAT_MAINTENANCE 		= FALSE;
		const MESSAGE_MAINTENANCE 	= 'Votre plateforme est actuellement sous maintenance. Toute nos excuses pour les désagréments! AvePLUS AFRICA';
		
		const TEL_LONAB = '25-30-61-22/22';
		const NBRE_TRANSACTION = 5;
		const NBRE_GAIN = 5;
		const FRAIS_CONVERSION_GAIN = 1;
		const NB_DERNIERES_TRANSACTIONS = 10;
		const NB_JOURS_VALIDITE_TICKET = 7;
		const MONTANT_DUPLICATA_TICKET = 1;
		const MONTANT_CONSULTATION_TRANSACTIONS = 1;
		const MONTANT_CONVERSION_GAINS = 1;
		const NB_JOURS_A_AJOUTER_DATE_VALIDITE_DES_CREATION_COMPTE = 100;
		const NB_JOURS_A_AJOUTER_DATE_VALIDITE_TRANSACTION = 60;

                
                
                public static function get_var($key){
                        $key = strtolower($key);
			if(!self::$app_var){
                            $fichierIni = \app\DOCUMENT_ROOT.\app\PATH.'app/config/config.ini';
                            require_once \app\DOCUMENT_ROOT.\app\PATH.\app\Config::APP_SRC_PATH.'service/Service.php';
                            $iniFile = \lonabmobile\service\Service::fileIni($fichierIni);
                            self::$app_var = $iniFile;
                        }
                        return isset(self::$app_var[$key]) ? self::$app_var[$key] : null;
                }
                
        /**
		* Fonction permettant de recupérer les informations de la course afin d'alimenter le boc Infos Clés
		* 
		* @return
		*/        
                   
         public static function getSessionCourse(){

			$DB_URL = self::db_url();
			$connexion = new \PDO($DB_URL[1]['DB_DSN'], 
									$DB_URL[1]['DB_USER'], 
									$DB_URL[1]['DB_PWD'],
									array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION, \PDO::ATTR_AUTOCOMMIT=>true, \PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'UTF8'",));	
			

            $session = new \lonabmobile\model\course\Session($connexion);
            $course = new \lonabmobile\model\course\Course($connexion);
            $gain_course = new \lonabmobile\model\gain\Gain_course($connexion);
            
            $sessionData = $session->findOneByFields(array("filtres"=>"etatOuvert=? AND etatCloture=?","values"=>array('1', '0')), "etatMisEnPayeNvGain, etatMisEnPayeAncGain");
            
            //$courseVeille = $course->findLastByField(array('name'=>'etatParisOuvert=?' , "value"=>"0"), "typeCourse, noCourse, nonPartantDMn, arriveeCourse, totalMise");
            
           $courseVeille = $course->select("C.id as idC, C.typeCourse, C.noCourse, C.dateCourse, C.nonPartantDMn, C.arriveeCourse, C.totalMise, G.id, G.typeGain, G.idCourse, G.montantGain")->from("course C, gain_course G")->where("C.etatParisOuvert = ? and C.id = G.idCourse", array(0))->order_by('C.id DESC LIMIT 1')->fetch();
           
           
           $gainCourseVeille = $gain_course->select("id, typeGain, idCourse,nbreCombinaison, montantGain")->from("gain_course")->where("idCourse=? AND (typeGain=? OR typeGain=?)", array($courseVeille['idC'], 'OR', 'DS'))->fetchAll();
           
           $courseActuelle = $course->select("C.id as idC, C.typeCourse, C.noCourse, C.dateCourse, C.nonPartantDMn, C.arriveeCourse, C.totalMise,C.etatVentile, G.id")->from("course C, gain_course G")->where("C.etatParisOuvert = ? and C.id = G.idCourse", array(2))->order_by('C.id DESC LIMIT 1')->fetch();
           
           $gainCourseActuelle = $gain_course->select("id, typeGain, idCourse,nbreCombinaison, montantGain")->from("gain_course")->where("idCourse=? AND (typeGain=? OR typeGain=?)", array($courseActuelle['idC'], 'OR', 'DS'))->fetchAll();
            
            $sessionEnCours['session'] = $sessionData;
            $sessionEnCours['courseVeille']['course'] = $courseVeille;
            $sessionEnCours['courseVeille']['gain'] = $gainCourseVeille;
            $sessionEnCours['courseActuelle']['course'] = $courseActuelle;
            $sessionEnCours['courseActuelle']['gain'] = $gainCourseActuelle;
            
            
            return $sessionEnCours;
           }    
                
		public static function getSMSMessage($SMSName, $valeur = null){
			$SMS = null;

			$DB_URL = self::db_url();
			$connexion = new \PDO($DB_URL[1]['DB_DSN'], 
									$DB_URL[1]['DB_USER'], 
									$DB_URL[1]['DB_PWD'],
									array(\PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION, \PDO::ATTR_AUTOCOMMIT=>true, \PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES 'UTF8'",));	
			

            $configrepo = new \lonabmobile\model\parametre\Config($connexion);
            $laconfig = $configrepo->findOneByField(array("name"=>"etat","value"=>"1"));
            
            $course = new \lonabmobile\model\course\Course($connexion);
            $courseActuelle = $course->select("C.id as idC, C.typeCourse, C.noCourse, C.dateCourse, C.nonPartantDMn, C.arriveeCourse, C.totalMise, G.id")->from("course C, gain_course G")->where("C.etatParisOuvert = ? and C.id = G.idCourse", array(1))->order_by('C.id DESC LIMIT 1')->fetch();
                                
                        switch ($SMSName) {

				// 1. Jouer au PMU'B
				// --------------------------------------
				case 'JEU_PMUB_OK':
					$SMS = 'Modele de Ticket!!!';
					break;

				case 'JEU_PMUB_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors [CAS]. Il vous reste [NBRE_ESSAIS] essais! ';
					break;

				case 'JEU_PMUB_PIN_BLOQUE': 
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors [CAS]. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;
                                
                                case 'NOTIFIE_DEPOUILLEMENT':
                                        $SMS = 'Depouillement de la course '.$courseActuelle['typeCourse'].' du '.$courseActuelle['dateCourse'].' effectue avec succes. ';
                                        break;
                                        
                                case 'JEU_TICKET_PMUB_MOBILE':
                                        $SMS = '*LOTERIE NATIONALE BURKINABE*'.PHP_EOL.
                                                '-----------------------------'.PHP_EOL.
                                                '[LIBELLE_JOUR]: [DATE_COURSE_TEXT]'.PHP_EOL.
                                                'Recu N0: [NUMEROTICKET]'.PHP_EOL.
                                                '[REUNION_CODE]: [NUMERO_COURSE]'.PHP_EOL.
                                                'Partants: [NB_PARTANT] Non-Partants: [NB_NON_PARTANT]'.PHP_EOL.
                                                'Client: [CEL_PARIEUR]'.PHP_EOL.
                                                'Pari: [TYPE_COURSE]'.PHP_EOL.
                                                '-----------------------------'.PHP_EOL.
                                                '[COMBINAISONS]'.PHP_EOL.
                                                'Cout: [PRIX] Jetons'.PHP_EOL.
                                                'Solde restant: [SOLDE_RESTANT] Jetons'.PHP_EOL.
                                                'Date limite de paiement: [DATE_VALIDITE]'.PHP_EOL.
                                                'LONAB Mobile'.PHP_EOL;
                                        break;

				// Crédit insuffisant
				case 'CREDIT_INSUFFISANT': 
					$SMS = 'Désolé! Votre crédit jetons est insuffisant pour effectuer cette action. Prière recharger votre compte puis réessayer.';
					break;
			
				// 4. Mon compte
				// -----------------------------
				case 'CREER_COMPTE_OK_POUR_USSD':
					$SMS = 'Félicitation, votre compte jeton LONAB a été créé avec succès. Votre code PIN par defaut est [CODE_PIN]. Vous recevrez confirmation par SMS.!';
					break;
				case 'CREER_COMPTE_OK':
					$SMS = 'Felicitations! Compte jetons LONAB cree avec succes. Votre code PIN est [CODE_PIN], pour plus de securite, nous vous invitons a le changer. BIENVENUE DANS LA FAMILLE LONAB!';
					break;

				case 'CREER_COMPTE_BIENVENUE':
					$SMS = 'Vous venez de creer un compte jetons LONAB, priere vous rendre dans une agence ou un point de vente pour recharger votre compte et commencer a jouer. Pensez a vous munir de votre CNIB. Merci';
					break;

				case 'CREER_COMPTE_NOM_PRENOM_INVALIDE':
					$SMS = 'Désolé. Votre entrée n\'est pas valide.';
					break;

				case 'CREER_COMPTE_NOM_PRENOM':
					$SMS = 'Veuillez entrer votre nom et prénoms séparés d\'un espace. Merci';
					break;

				// 4.1. Convertir gains
				// -----------------------------
				case 'CONVERSION_GAIN_OK':
					$SMS = 'CONVERSION GAINS:'.PHP_EOL.
						'Date: [DATE] a [HEURE]'.PHP_EOL.
						'Ref trans: [ID_TRANSACTION]'.PHP_EOL.
						'Mt Convertis: [MONTANT]F'.PHP_EOL.
						'Credit jetons: [JETON]'.PHP_EOL.
						'Frais conversion: [FRAIS_CONVERSION_GAIN]F'.PHP_EOL.
						'Nouveau solde:[SOLDE_JETON] Jetons valide jusqu’au [DATE_VALIDITE]';
					break;

				case 'CONVERSION_GAIN_NOK':
					$SMS = 'Desole! Le total de vos gains est insuffisant pour effectuer cette action. Vous avez [SOLDE_GAIN]F. Pensez a recharger votre compte jetons. Merci';
					break;

				case 'CONVERSION_GAIN_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de votre tentative de conversion de vos gains.';
					break;

				case 'CONVERSION_GAIN_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre Code PIN lors de la conversion de vos gains. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'CONVERSION_PAIEMENTS_ARRETES':
					$SMS = 'Désolé, les paiements ne sont pas encore ouverts.';
					break;

				// 4.2. Transferer jetons
				// -----------------------------
				case 'TRANSFERT_JETON_OK_USSD':
					$SMS = 'Félicitations ! Transfert effectué avec succès. Vous recevrez les détails de votre transaction par SMS.';
					break;
					
				case 'TRANSFERT_JETON_OK':
					$SMS = 'TRANSFERT JETONS :'.PHP_EOL.
						'Date : [DATE] a [HEURE]'.PHP_EOL.
						'Ref trans : [ID_TRANSACTION]'.PHP_EOL.
						'Num dest : [CEL_BENEFICIAIRE]'.PHP_EOL.
						'Mt transfere: [MONTANT]F'.PHP_EOL.
						'Credit jetons: [JETON] '.PHP_EOL.
						'Nouveau solde: [SOLDE_JETON] Jetons valide jusqu’au [DATE_VALIDITE]';
					break;
                                
				case 'TRANSFERT_JETON_BENEF':
					$SMS = 'Cher client, vous avez reçu [JETON] jetons de [CIVILITE] [NOM] [PRENOM_INITIALE] / [CEL_EXPEDITEUR]. Le solde de votre compte est de [SOLDE_JETON] Jetons valide jusqu’au [DATE_VALIDITE]. Ref trans: [ID_TRANSACTION]. LONAB MOBILE';
					break;

				case 'TRANSFERT_JETON_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de votre tentative de transfert de jetons au [CEL_BENEFICIAIRE].';
					break;

				case 'TRANSFERT_JETON_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors du transfert de jetons au [CEL_BENEFICIAIRE]. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'TRANSFERT_BENEFICIAIRE_INEXISTANT':
					$SMS = 'Désolé! Le destinataire ne possede pas de compte jetons LONAB pour effectuer cette transaction. Inviter-le a en créer puis réessayer.';
					break;


				// 4.3.1. Mon solde
				// --------------------------------------
				case 'MON_SOLDE_PARIEUR_OK':
					$SMS = 'Votre crédit est de [SOLDE_JETON] jetons ([MONTANT_CONVERTI]F), valide jusqu au [DATE_VALIDITE].';
					break;

				case 'MON_SOLDE_PARIEUR_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de votre tentative de consultation de votre solde.';
					break;

				case 'MON_SOLDE_PARIEUR_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors de la consultation de votre solde. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				// 4.3.2.1. Mes transactions par sms
				// --------------------------------------
				case 'MES_TRANSACTIONS_SMS_PARIEUR_OK':
					$SMS = 'Votre requete a ete enregistree, vous recevrez les details de vos ([NBRE_TRANSACTION]) dernieres transactions par SMS.';
					break;

				case 'MES_TRANSACTIONS_SMS_PARIEUR_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de la demande de reception de vos [NBRE_TRANSACTION] dernieres transactions par SMS.';
					break;

				case 'MES_TRANSACTIONS_SMS_PARIEUR_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre Code PIN lors de votre demande de vos [NBRE_TRANSACTION] dernieres transactions par SMS. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'MES_TRANSACTIONS_SMS_AUCUNE_TRANSACTION':
					$SMS = 'Désolé! Aucune transaction enregistree sous le numero [CEL]. Merci de verifier que vous avez deja utilise les services LONAB mobiles.';
					break;


				// 4.3.2.2. Mes transactions par e-mail
				// --------------------------------------
				case 'MES_TRANSACTIONS_EMAIL_PARIEUR_OK':
					$SMS = 'Votre requete a ete enregistree, vous recevrez les details de vos transactions par mail.';
					break;

				case 'MES_TRANSACTIONS_EMAIL_PARIEUR_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de la demande de reception de vos transactions par mail.';
					break;

				case 'MES_TRANSACTIONS_EMAIL_PARIEUR_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors de votre demande de l\'historique de vos transactions par mail. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'MES_TRANSACTIONS_EMAIL_AUCUNE_TRANSACTION':
					$SMS = 'Désolé! Aucune transaction enregistree sous le numero [CEL]. Merci de verifier que vous avez deja utilise les services LONAB mobiles.';
					break;

				// 4.3.3.1. Mes gains par sms
				// --------------------------------------
				case 'MES_GAINS_SMS_OK':
					$SMS = 'Votre requete a ete enregistree, vous recevrez les details de vos ([NBRE_GAIN]) derniers gains par SMS.';
					break;

				case 'MES_GAINS_SMS_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un mauvais code PIN le [DATE] a [HEURE] lors de la demande de reception de vos [NBRE_GAIN] derniers gains par SMS.';
					break;

				case 'MES_GAINS_SMS_PARIEUR_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors de votre demande de l\'historique de vos transactions par mail. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'MES_GAINS_SMS_AUCUNE_TRANSACTION':
					$SMS = 'Désolé! Aucune transaction enregistree sous le numero [CEL]. Merci de verifier que vous avez deja utilise les services LONAB mobiles.';
					break;

				// 4.3.3.1. Mes gains par email
				// --------------------------------------
				case 'MES_GAINS_EMAIL_OK':
					$SMS = 'Votre requete a ete enregistree, vous recevrez les details de vos ([NBRE_GAIN]) derniers gains par mail.';
					break;

				case 'MES_GAINS_EMAIL_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un mauvais code PIN le [DATE] a [HEURE] lors de la demande de reception de vos [NBRE_GAIN] derniers gains par mail.';
					break;

				case 'MES_GAINS_EMAIL_AUCUNE_TRANSACTION':
					$SMS = 'Désolé! Aucune transaction enregistree sous le numero [CEL]. Merci de verifier que vous avez deja utilise les services LONAB mobiles.';
					break;

				// 4.4.1. Modifier code PIN
				// --------------------------------------
				case 'MODIFIER_CODEPIN_PARIEUR_OK':
					$SMS = 'ALERTE MODIFICATION CODE PIN : Code PIN modifie avec succes le [DATE] a [HEURE]. Votre nouveau code PIN est [CODE_PIN], priere le garder secretement.';
					break;

				case 'MODIFIER_CODEPIN_PARIEUR_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect lors tentative de modification le [DATE] a [HEURE].';
					break;

				case 'MODIFIER_CODEPIN_PARIEUR_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors de la tentative de modification. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				// 4.4.2. Duplicata Ticket
				// --------------------------------------
				case 'DUPLICATA_TICKET_OK':
					$SMS = 'Vous recevrez un duplicata de votre ticket par SMS.';
					break;

				case 'DUPLICATA_TICKET_AUCUN_TICKET':
					$SMS = 'Désolé! Vous n’avez pas de ticket en cours de validité. Pour une assistance, priere appeler le [TEL_LONAB]. Merci';
					break;

				case 'DUPLICATA_TICKET_CHOIX_TICKET':
					$SMS = 'Veuillez Choisir dans la liste proposee le ticket a dupliquer SVP!'.PHP_EOL;
					break;

				case 'DUPLICATA_TICKET_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entre un code PIN incorrect le [DATE] a [HEURE] lors de votre demande de DUPLICATA de ticket de jeux PMU\'B.';
					break;

				case 'DUPLICATA_TICKET_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre code PIN lors de votre demande de DUPLICATA de ticket. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				// Jeu
				// --------------------------------------
				case 'JEU_CEL_PAYEUR_PAYFORME':
					$SMS = 'Veuillez saisir le numéro du destinaire SVP!';
					break;

				case 'JEU_CEL_PAYEUR_PAYFORME_INEXISTANT':
					$SMS = 'Désolé! Le destinataire ne possede pas de compte jetons LONAB pour effectuer cette action. Inviter-le a en créer puis réessayer.';
					break;

				case 'JEU_AVERTISSEMENT_FACTURATION_COMB_PAYFORME_PARIEUR':
					$SMS = 'Vous serez facturé [JETON] jetons ([MONTANT_CONVERTI] F) pour votre PayForMe. Entrez votre code PIN pour confirmer.';
					break;

				case 'JEU_AVERTISSEMENT_DEMANDE_PAYFORME_EN_COURS':
					$SMS = 'Vous avez deja envoye un PayForMe pour cette course a [CIVILITE] [NOM] [PRENOM_INITIALE]/[CEL_EXPEDITEUR]. Souhaitez-vous annuler ?'.PHP_EOL.
					'1. Oui 2. Non';
					break;

				case 'JEU_DEMANDE_PAYFORME_SUCCESS':
					$SMS = 'Votre demande a ete envoye avec succes! vous recevrez votre reçu par SMS dès acceptation par le destinataire.';
					break;

				case 'JEU_DEMANDE_PAYFORME_SUCCESS_DESTINATAIRE':
					$SMS = '[CIVILITE] [NOM] [PRENOM_INITIALE] / [CEL_BENEFICIAIRE] vous a envoye un PayForMe. Pour repondre, tapez *339*4*4*3#';
					break;

				case 'JEU_AVERTISSEMENT_FACTURATION_COMB':
					$SMS = 'Vous serez facturé à [JETON] jetons ([MONTANT_CONVERTI] F) pour ce jeu. Entrer votre Code PIN pour confirmer SVP!';
					break;

				case 'JEU_ENREGISTRE':
					$SMS = 'Votre Pari a été enregistré, vous recevrez votre reçu par SMS.';
					break;

				case 'JEU_AUCUNE_COURSE_ACTIVE':
					$SMS = 'Désolé ! Pas de course programmé pour le moment. Merci réessayer ultérieurement.';
					break;

				// Accepter PAYFORME	
				case 'JEU_AVERTISSEMENT_FACTURATION_COMB_PAYFORME_PAYEUR':
					$SMS = 'Vous serez facturé à [JETON] jetons ([MONTANT_CONVERTI] F) pour cette operation. Entrer votre code PIN pour accepter de payer le ticket de [CIVILITE] [NOM] [PRENOM_INITIALE]/[CEL_BENEFICIAIRE]: ';
					break;	

				case 'JEU_ACCEPTATION_PAYFORME_SUCCESS':
					$SMS = 'Paiement effectue avec succes! [CIVILITE] [NOM] [PRENOM_INITIALE]/[CEL_BENEFICIAIRE] vous remercie !!';
					break;

				case 'JEU_ACCEPTATION_PAYFORME_SUCCESS_PARIEUR':
					$SMS = 'Bonjour! Votre PayForMe a ete accepte par [CIVILITE] [NOM] [PRENOM_INITIALE]/[CEL_EXPEDITEUR].';
					break;

				case 'JEU_ACCEPTATION_PAYFORME_SUCCESS_PAYEUR':
					$SMS = 'Felicitation! Vous avez paye avec succes le jeu de [CIVILITE] [NOM] [PRENOM_INITIALE]/[CEL_BENEFICIAIRE].Cout de l\'operation: [JETON] ([MONTANT_CONVERTI] F).';
					break;

				case 'JEU_AUCUNE_DEMANDE_PAYFORME_EN_COURS':
					$SMS = 'Désolé ! Pas de PayForMe enregistre sous ce numero. Merci de verifier et reesayer si necessaire.';
					break;

				case 'JEU_REJET_PAYFORME_SUCCESS':
					$SMS = 'Rejet PayForMe enregistre!';
					break;

				case 'JEU_REJET_PAYFORME_SUCCESS_SMS':
					$SMS = 'Bonjour ! PayForMe non accepte par le [CEL_BENEFICIAIRE].';
					break;



				// GAIN ET REMBOURSEMENT
				// --------------------------------------

				case 'ALERTE_REMBOURSEMENT':
					$SMS = 'Cher client, votre mise de [MONTANT_REMBOURSE] vous a ete rembourse suite a des non partants de derniere minute dans votre combinaison [COMBINAISON].'.PHP_EOL.
							'LONAB MOBILE';
					break;

				case 'ALERTE_GAIN':
					$SMS = 'Bonsoir [CIVILITE] [NOM] [PRENOM_INITIALE], vous venez de gagner [MONTANT_GAIN] F sur [TYPE_GAIN] du [TYPE_PARI] du [DATE_COURSE]. Felicitations!'.PHP_EOL.
							'LONAB Mobile';
					break;


				// MENU POINTS DE VENTES
				// ===========================================

				// 1. Recharger un compte
				// --------------------------------------

				case 'RECHARGE_COMPTE_OK':
					$SMS = 'RECHARGEMENT COMPTE JETONS :'.PHP_EOL.
						'Date : [DATE] a [HEURE]'.PHP_EOL.
						'Réf trans : [ID_TRANSACTION]'.PHP_EOL.
						'Num Client : [CEL_BENEFICIAIRE]'.PHP_EOL.
						'Mt rechargé: [MONTANT]F'.PHP_EOL.
						'Eq en jetons: [JETON]'.PHP_EOL.
						'Mt Comm : [MONTANT_COMMISSION]F'.PHP_EOL.
						'Nouveau solde : [SOLDE_JETON] Jetons';
					break;

				case 'RECHARGE_COMPTE_OK_USSD':
					$SMS = 'Félicitations ! Le rechargement a été effectué avec succès. Vous recevrez les détails de votre transaction par SMS.';
					break;

				case 'RECHARGE_COMPTE_BENEF':
					$SMS = 'ACHAT JETONS:'.PHP_EOL.
						'Date: [DATE] a [HEURE]'.PHP_EOL.
						'Réf trans : [ID_TRANSACTION]'.PHP_EOL.
						'Num exp : [CEL_EXPEDITEUR]'.PHP_EOL.
						'Mt rechargé: [MONTANT]F'.PHP_EOL.
						'Eq en jetons: [JETON]'.PHP_EOL.
						'Nouveau solde : [SOLDE_JETON] jetons valide jusqu’au [DATE_VALIDITE]';
					break;

				case 'RECHARGE_COMPTE_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre tentative de rechargement du compte [CEL_PDV].';
					break;

				case 'RECHARGE_COMPTE_PIN_BLOQUE':
					$SMS = 'Votre compte a été bloqué suite a 3 entrées incorrectes de votre code PIN lors du rechargement du compte [CEL_EXPEDITEUR]. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				// 2. Mon solde
				// --------------------------------------
				case 'MON_SOLDE_PDV_OK':
					$SMS = 'Votre crédit est de [SOLDE_JETON] jetons.';
					break;

				case 'MON_SOLDE_PDV_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un Code PIN incorrect le [DATE] a [HEURE] lors de la tentative de Consultation de votre solde.';
					break;

				case 'MON_SOLDE_PDV_PIN_BLOQUE':
					$SMS = 'Votre compte a été bloqué suite a 3 entrées incorrectes de votre Code PIN lors de la consultation de votre solde. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				// Bloquer compte
				// -----------------------------
				case 'BLOQUER_COMPTE_OK':
					$SMS = 'BLOCAGE COMPTE JETONS :'.PHP_EOL.
						'Date : [DATE] a [HEURE]'.PHP_EOL.
						'Nom client : [CIVILITE] [PRENOM]'.PHP_EOL.
						'Num client : [CEL_CLIENT]'.PHP_EOL.
						'No CNIB: [NO_CNIB_CLIENT]'.PHP_EOL.
						'Statut compte : Bloqué';
					break;

				case 'BLOQUER_COMPTE_BENEF':
					$SMS = 'BLOCAGE COMPTE JETONS : Bonjour [CIVILITE] [PRENOM], votre compte a été bloqué par le [CEL_PDV] le [DATE] a [HEURE]. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'BLOQUER_COMPTE_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre  tentative de Blocage du compte jetons [CEL_CLIENT].';
					break;

				case 'BLOQUER_COMPTE_PIN_BLOQUE':
					$SMS = 'Votre compte a été bloqué suite a 3 entrées incorrectes de votre Code PIN lors du Blocage du compte jetons [CEL_CLIENT]. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'BLOQUER_COMPTE_DEJA_BLOQUE':
					$SMS = 'Désolé! Ce compte est déjà bloqué.';
					break;

				// Débloquer un compte
				// ------------------------------
				case 'DEBLOQUER_COMPTE_OK':
					$SMS = 'DEBLOCAGE COMPTE JETONS :'.PHP_EOL.
						'Date : [DATE] a [HEURE]'.PHP_EOL.
						'Nom client : [CIVILITE] [PRENOM]'.PHP_EOL.
						'Num client : [CEL_CLIENT]'.PHP_EOL.
						'No CNIB: [NO_CNIB_CLIENT]'.PHP_EOL.
						'Statut compte : Débloqué';
					break;

				case 'DEBLOQUER_COMPTE_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre  tentative de déblocage du compte jetons [CEL_CLIENT].';
					break;

				case 'DEBLOQUE_COMPTE_BENEF':
					$SMS = 'DEBLOCAGE COMPTE JETONS : Bonjour [CIVILITE] [PRENOM], votre compte a été débloqué par le [CEL_PDV] le [DATE] a [HEURE]. Le code PIN par défaut est [CODE_PIN]. Rechargez vite votre compte pour jouer.';
					break;

				case 'DEBLOQUER_COMPTE_PIN_BLOQUE':
					$SMS = 'Votre compte a été bloqué suite a 3 entrées incorrectes de votre Code PIN lors du déblocage du compte jetons [CEL_CLIENT]. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				case 'DEBLOQUER_COMPTE_DEJA_DEBLOQUE':
					$SMS = 'Désolé! Ce compte n\'est pas bloqué.';
					break;

				// Modifier code PIN
				// ------------------------------
				case 'MODIFIER_CODEPIN_PDV_OK':
					$SMS = 'ALERTE MODIFICATION CODE PIN : Code PIN modifié avec succès le [DATE] a [HEURE]. Votre nouveau code PIN est [CODE_PIN], prière le garder secrètement.';
					break;

				case 'MODIFIER_CODEPIN_PDV_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre  tentative de modification de votre code PIN.';
					break;

				case 'MODIFIER_CODEPIN_PDV_PIN_BLOQUE':
					$SMS = 'Votre compte a été bloqué suite a 3 entrées incorrectes de votre code PIN lors de la tentative de modification du code. Prière contactez le service client LONAB au [TEL_LONAB].';
					break;

				// Mes transactions par sms
				// ------------------------------
				case 'MES_TRANSACTIONS_SMS_PDV_OK':
					$SMS = 'Votre requête a été enregistrée, vous recevrez les détails de vos [NBRE_TRANSACTION] derniers transactions par SMS.';
					break;

				case 'MES_TRANSACTIONS_SMS_PDV_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre demande de vos [NBRE_TRANSACTION] dernieres transactions par SMS.';
					break;

				case 'MES_TRANSACTIONS_SMS_PDV_PIN_BLOQUE':
					$SMS = 'Votre compte a ete bloque suite a 3 entrees incorrectes de votre Code PIN lors de votre demande de vos [NBRE_TRANSACTION] dernieres transactions par SMS. Priere contactez le service client LONAB au [TEL_LONAB].';
					break;

				// Mes transactions par e-mail
				// ------------------------------
				case 'MES_TRANSACTIONS_EMAIL_PDV_OK':
					$SMS = 'Votre requête a été enregistrée, vous recevrez les détails de vos transactions par e-mail.';
					break;

				case 'MES_TRANSACTIONS_EMAIL_PIN_NOK':
					$SMS = 'ERREUR CODE PIN : Vous avez entré un code PIN incorrect le [DATE] a [HEURE] lors de votre demande de vos [NBRE_TRANSACTION] dernieres transactions par e-mail.';
					break;

				
				default:
					# code...
					break;
			}
			$params = array(
				'[DATE]' => @gmdate("y-m-d"),
				'[HEURE]' => @gmdate("H:i:s"),
				'[TEL_LONAB]' => self::TEL_LONAB,
				'[NBRE_TRANSACTION]' => self::NBRE_TRANSACTION,
				'[NBRE_GAIN]' => self::NBRE_GAIN,
				'[FRAIS_CONVERSION_GAIN]' => self::FRAIS_CONVERSION_GAIN
				);
				
			if($valeur){
				foreach($valeur as $key => $value){
					if($key=='nom' && $value==null) $valeur[$key] = '[Nom';
					if($key=='prenom' && $value==null) $valeur[$key] = 'Inconnu]';
					elseif($value==null) $valeur[$key] = '';
				}
				
				/*
				$params = array();
				foreach($valeur as $key => $value){
					$params[self::SMS_KEY($key)] = $value;
				}
				unset($valeur);
				*/
				if(isset($valeur["solde"]))
					$params['[SOLDE_JETON]'] = $valeur["solde"];

				if(isset($valeur["solde_gain"]))
					$params['[SOLDE_GAIN]'] = $valeur["solde_gain"];

				if(isset($valeur["codePin"]))
					$params['[CODE_PIN]'] = $valeur["codePin"];

				if(isset($valeur["nbreEssais"]))
					$params['[NBRE_ESSAIS]'] = $valeur["nbreEssais"];

				if(isset($valeur["date_validite"]))
					$params['[DATE_VALIDITE]'] = $valeur["date_validite"];

				if(isset($valeur["cel_benef"])){
					$params['[CEL_BENEFICIAIRE]'] = $valeur["cel_benef"];
					$params['[CEL_CLIENT]'] = $valeur["cel_benef"];
				}

				if(isset($valeur["cel"])){
					$params['[CEL]'] = $valeur["cel"];
				}

				if(isset($valeur["cel_exp"]))
					$params['[CEL_EXPEDITEUR]'] = $valeur["cel_exp"];

				if(isset($valeur["cel_pdv"]))
					$params['[CEL_PDV]'] = $valeur["cel_pdv"];
				
				if(isset($valeur["noCnib"]))
					$params['[NO_CNIB_CLIENT]'] = $valeur["noCnib"];

				if(isset($valeur["genre"]))
					$params['[CIVILITE]'] = $valeur["genre"];

				if(isset($valeur["nom"]))
					$params['[NOM]'] = $valeur["nom"];

				if(isset($valeur["prenom"])){
					$params['[PRENOM]'] = $valeur["prenom"];
					$params['[PRENOM_INITIALE]'] = substr($valeur["prenom"], 0).'.';
				}

				if(isset($valeur["montant"]))
					$params['[MONTANT]'] = $valeur["montant"];

				if(isset($valeur["nbJetons"])){
					$params['[JETON]'] = $valeur["nbJetons"];
					$params['[MONTANT_CONVERTI]'] = $valeur["nbJetons"] * $laconfig["tauxConversion"];
				}

				if(isset($valeur["idTransaction"]))
					$params['[ID_TRANSACTION]'] = $valeur["idTransaction"];

				if(isset($valeur["montantCommission"]))
					$params['[MONTANT_COMMISSION]'] = $valeur["montantCommission"];

				if(isset($valeur["montant_rembourse"]))
					$params['[MONTANT_REMBOURSE]'] = $valeur["montant_rembourse"];

				if(isset($valeur["combinaison"]))
					$params['[COMBINAISON]'] = $valeur["combinaison"];

				if(isset($valeur["montantGain"]))
					$params['[MONTANT_GAIN]'] = $valeur["montantGain"];

				if(isset($valeur["typeGain"]))
					$params['[TYPE_GAIN]'] = $valeur["typeGain"];

				if(isset($valeur["typePari"]))
					$params['[TYPE_PARI]'] = $valeur["typePari"];

				if(isset($valeur["dateCourse"]))
					$params['[DATE_COURSE]'] = $valeur["dateCourse"];

				if(isset($valeur["[NUMEROTICKET]"]))
					$params = $valeur;
			}
			
			$SMS = strtr($SMS, $params);
			return $SMS;
		}

/*
		public static function SMS_KEY($key)
		{
			switch ($key) {
				case "prenom": 	return "[PRENOM]";
				case "nom":		return "[NOM]";
				default:		return "";
			}
		}
*/
		
		public static function view_default_params(){
			$_NAVBAR = array('path'=>\app\PATH);
			return $_NAVBAR;	
		}

		public static function view_default_sub_layouts(){
			return array('sous_gabarit');
		}

		public static function view_default_imported_files(){
			return array();
		}

		public static function app_required_models($action){
			
			//die($action);
			/** Classes requises par tous les contrôleurs **/
			//self::required('model',array('utilisateur/Region'));
			self::required('service','Service'); /* Classe des services s'elle existe */
			self::required('service','ServiceTarification'); /* Classe des services de tarification */
			self::required('service','ServiceEnvoiSMS'); /* Classe des services d'envoi de SMS */
			self::required('service','ServiceEnvoiMail'); /* Classe des services d'envoi de mail */
			self::required('service','ServiceSyncBus'); /* Classe des services d'envoi de mail */
		
			// self::required('model/syncbus',array('Syncbus')); /* Classe du model Syncbus */
			/* Lbrairie de gestion des SMS */
			self::required('service','Smslib');
			self::required('smslib',array('autoload.inc'));

			
			/** Classes espécifiques à chaque contrôleur **/
			switch($action ){
				case 'Ussdparieur':
					return array('gain/Gain_parieur','gain/Paiement_gain','parametre/TypeCourse','course/Course','course/Reunion','pari/Combinaison','pari/Temp_no_recu','utilisateur/Parieur','compte/Compte','compte/Transaction','statistique/Stattransaction','compte/Operation', 'pari/Jeu','pari/Ticket','course/Session','parametre/Config','course/Session','parametre/Exercice','parametre/NumeroCourse', 'pari/Combinaison_PayForMe','pari/Demande_PayForMe','pari/Accepter_PayForMe','droit/Groupe', 'commission/Commission', 'gain/Gain_course');
					break;				
				case 'Ussdpdv':
					return array('compte/Compte','utilisateur/Parieur','gain/Gain_parieur','gain/Paiement_gain','parametre/Config','compte/Transaction','statistique/Stattransaction','compte/Operation', 'commission/Commission', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Compte':
					return array('compte/Compte','utilisateur/Parieur','compte/Transaction',
								 'pari/Jeu','gain/Gain_parieur','compte/Operation', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Utilisateur':
					return array('utilisateur/Utilisateur','utilisateur/Agence','droit/Groupe',
								 'droit/G_privilege','droit/U_privilege', 'course/Session', 'course/Course', 'gain/Gain_course');					
					break;
					
				case 'Paiement':
					return array('course/Session','utilisateur/Utilisateur','utilisateur/Agence','utilisateur/Parieur',
								 'gain/Gain_parieur','gain/Paiement_gain','compte/Compte','parametre/Config', 'course/Course', 'gain/Gain_course');
					break;

				case 'Pointvente':
					return array('utilisateur/VenteCreditJeton','utilisateur/Pointvente','droit/Groupe','utilisateur/Agence','parametre/Config','compte/Compte','compte/Transaction','statistique/Stattransaction','utilisateur/EmissionAgence', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
				
				case 'Groupe':
					return array('utilisateur/Utilisateur','droit/Groupe','droit/G_privilege',
								 'droit/Privilege', 'course/Session', 'course/Course', 'gain/Gain_course');

					break;				
				case 'Privilege':
					return array('droit/Privilege', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;		
				case 'Droits':
					return array('droit/Groupe','utilisateur/Utilisateur','droit/Privilege',
								 'droit/G_privilege','droit/U_privilege', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
	
				case 'Agence':
					return array('utilisateur/VenteCreditJeton','compte/Compte','utilisateur/Agence','parametre/Region','compte/Transaction','utilisateur/EmissionAgence', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	

                case 'Region':
					return array('parametre/Region', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	

				case 'Exercice':
					return array('parametre/Exercice', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
					
				case 'Session':
					return array('course/Session','course/Course', 'gain/Gain_course');
					break;
					
				case 'Gain':
					return array('course/Session','course/Course',
						'gain/Gain_course','gain/Gain_parieur','gain/Paiement_gain','pari/Combinaison',
                                                'pari/Jeu','pari/SolveurCouple','pari/Solveur345','pari/Remboursement',
                                                'compte/Compte','compte/Transaction','statistique/Stattransaction','parametre/Config','utilisateur/Parieur', 'kyc/Diffusiongains');
					break;	
					
				case 'Reunion':
					return array('course/Reunion', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
					
				case 'Numerocourse':
					return array('parametre/NumeroCourse', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
					
				case 'Typecourse':
					return array('parametre/TypeCourse', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Typecombinaison':
					return array('parametre/TypeCombinaison', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
					
				case 'Typegain':
					return array('parametre/TypeGain', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
					
				case 'Pari':
					return array('compte/Compte','pari/Combinaison','pari/Combinaisontemp','pari/Jeu','pari/Ticket','compte/Compte',
								 'pari/Demande_PayForMe','pari/Combinaison_PayForMe','pari/Accepter_PayForMe', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;	
				
				case 'Payforme':
					return array('pari/Demande_PayForMe','pari/Accepter_PayForMe','pari/Demande_PayForMe',
								 'pari/Combinaison_PayForMe', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
	
				case 'Jeu':
					return array('pari/Jeu','pari/Ticket', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Course':
					return array('course/Course','course/Session','parametre/TypeCourse','pari/Combinaison',
								'pari/Remboursement', 'pari/Jeu','parametre/NumeroCourse', 'gain/Gain_course');
					break;
								
				case 'Notification':
					return array('kyc/Notification','parametre/TypeNotification', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;

				case 'Evenement':
					return array('utilisateur/Utilisateur','kyc/Notification','parametre/TypeNotification','kyc/Evenement', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Statistique':
					return array('statistique/Statistique', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
                                
				case 'Feedback':
					return array('kyc/Feedback', 'course/Session' , 'course/Course', 'gain/Gain_course');
					break;
                                
				case 'Diffusiongains':
					return array('kyc/Diffusiongains', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
                                
				case 'Comission':
				return array('commission/Commission', 'compte/Compte','commission/Compte_comission', 'utilisateur/Pointvente','parametre/Config','course/Session', 'course/Course', 'gain/Gain_course');
				break;
					
				case 'Api':
				return array('utilisateur/Utilisateur','utilisateur/Agence','parametre/Config',
							'utilisateur/Parieur','utilisateur/Pointvente',
							'compte/Compte','compte/Transaction','compte/Operation',
							'statistique/Stattransaction','course/Session','gain/Gain_parieur',
							'gain/Paiement_gain','commission/Compte_comission',
							'utilisateur/EmissionAgence', 'course/Course', 'gain/Gain_course');
				break;
					
				case 'Graph':
					return array('statistique/Graph', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
				
				case 'Push':
					return array('push/Push', 'push/ContenuPush', 'push/MessagePush', 'push/EnvoiPush', 'compte/Compte', 'kyc/Feedback', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
				
				case 'Messagepush':
					return array('push/Push', 'push/ContenuPush', 'push/MessagePush', 'push/EnvoiPush', 'compte/Compte', 'kyc/Feedback', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
				
                                case 'Config':
					return array('parametre/Config', 'parametre/Exercice', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
					
				case 'Stattransaction':
					return array('statistique/Stattransaction', 'parametre/Config', 'course/Session', 'course/Course', 'gain/Gain_course');
					break;
                                
				default:
					return array();
					break;
			}			
		}
		
		/**
		* 
		* Définission des menus, des sous-menus et des options de la plateforme
		* 
		* 
		*/
		public static function menu_lateral($action){
			switch($action){
				case 'Statistique/index': return 'Tableau de bord';
				case 'Notification/index': return 'Services client';
				case 'Session/index': return 'Paris et jeux';
				case 'Utilisateur/index': return 'Administration';
				case 'Log/index': return 'Journaux (LOGS)';
				default: return 'Journaux (LOGS)';
			}
		}
		
		public static function menu_horizontal($action){
			switch($action){
				//Sous-menu du module Tableau de bord
				case 'Statistique/index': return 'Chiffres d\'affaire';
				case 'Stattransaction/indexcommission': return 'Commissions';
				case 'Statistique/stat_gains_semaine': return 'Statistiques gains';
				case 'Agence/details': return 'Agences';
				case 'Stattransaction/index': return 'Commissions';
				
				//Sous-menu du module Service client
				case 'Push/index': return 'Push';
				case 'Notification/index': return 'Notifications Clients';
				case 'Feedback/index': return 'Feedbacks';
				
				//Sous-menu du module Administration                                        
				case 'Utilisateur/index': return 'Utilisateurs';
				case 'Compte/index': return 'Comptes';
				case 'Exercice/index': return 'Paramètres';
				case 'Region/index': return 'LONAB';
				
				//Sous-menu du module Paris et Jeux
				case 'Session/index': return 'Pilotage Courses';
				case 'Jeu/index': return 'Historique de jeu';
				
				default: return '';
			}
		}
		
		public static function menu_option($action){
			switch($action){
                //Options du menu horizontal paramètres
                case 'Reunion/index': return 'Réunion';
				case 'Numerocourse/index': return 'Numéro de course';
				case 'Exercice/index': return 'Exercice';
                case 'Config/index':return 'Paramètres globaux';
                                
				case 'Push/index': return 'BD push';
				case 'Messagepush/index': return 'Messages push';
				
				//Options du sous-menu Chiffres d'affaire
				case 'Statistique/index': return 'Paris';
				case 'Statistique/stat_payforme_semaine': return 'Pay For Me';
				case 'Stattransaction/index': return 'Vente jetons';
				
				//Options du sous-menu Statistiques commissions
				case 'Stattransaction/indexcommission': return 'Commissions PDV';
				case 'Statistique/index': return 'Partenaire';
				
				//Option du sous-menu Piloter courses
				case 'Session/index': return 'Session';
				case 'Course/index': return 'Paramétrer Courses';
				case 'Gain/index': return 'Gains';
				
				//Option du sous-menu LONAB
				case 'Region/index': return 'Régions';
				case 'Agence/index': return 'Agences';
				case 'PointVente/index': return 'Points De Vente';
				
				//Option du sous-menu Utilisateur
				case 'Groupe/index': return 'Groupes';
				case 'Privilege/index': return 'Privilèges';
				case 'Droits/index': return 'Droits'; 
				
				default: return '';
			}
		} 
		
		public static function set_active($menu1,$menu2){
			if($menu1==$menu2) return ' class="active"';
			return '';
		}

		public static function set_current($menu1,$menu2){
			if($menu1==$menu2) return ' class="current"';
			return '';
		}

		public static function db_url()
		{
			return array(
                            1=>array('DB_DSN' => 'mysql:host=192.168.1.20;port=3306;dbname=lonabmobile_db',
                                                 'DB_USER' => 'adm_lonab',
                                                 'DB_PWD' => 'Lon@Usr*14'
							   ),
							/*
							0=>array('DB_DSN' => 'mysql:host=localhost;port=3306;dbname=pmubmobile_db',
									 'DB_USER' => 'root',
									 'DB_PWD' => ''
							   ),
							*/
						 );	
		}
		
		public static function entete($packageP, $controllerP)
		{
			$packageP = ucfirst(strtolower($packageP));
			$PACKAGE = array();
			$PACKAGE['Administration']['Utilisateur'] = array('Groupe' => 'Groupes',
				'Utilisateur' => 'Utilisateurs',
				'Droits' => 'Droits',
				'Agence'=> "Agences", 
				'PointVente'=> "Points de vente",
				'Privilege' => 'Privilèges',
				//'Api' => ' API'
			 );
			$PACKAGE['Administration']['Parametre'] = array( 'Region' => 'Region',  );
            
			$PACKAGE['Pari']['Jeux'] = array( 'Jeu' => 'Jeux',  );
                        
                        $PACKAGE['Client']['Notification'] = array( 'Notification' => 'Notification', 'Feedback' => 'FeedBacks', 'Diffusiongains' => 'Diffusion Gains');
                        
                        $PACKAGE['Client']['Push'] = array( 'Push' => 'BD Push', 'Messagepush' => 'Messages Pushs');
                        
                       $PACKAGE['Tableau']['Statistique'] = array('Statistique' => ' Statistique des Paris',
                                                        'Statistique/stat_gains_semaine' => 'Statistique des Gains',
                                                        'Statistique/stat_payforme_semaine' => 'Statistique des PayForMe');
                       // $PACKAGE['Tableau']['Chiffres d\'affaire'] = array('Statistique' =>'Paris', 'Vente de jetons');
                        //$PACKAGE['Tableau']['Statistiques Commissions'] = array('Statistique' => 'Commissions PDV' , 'Partenaire');
                       // $PACKAGE['Tableau']['Statistiques Gains'] = array('Statistique/stat_gains_semaine' => '');
                        
			$PACKAGE['Administration']['Compte'] = array( 'Compte' => 'Comptes', 'Gain' => 'Gains', 'Gain/paiement_gain'=>'paiement de gain(s)', 'Comission/detailsCommission' => 'details de Commission(s)','Comission' => 'paiement de commission(s)' ,'Pointvente/recharge' => 'Recharge en jetons','Agence/details' => 'Jetons emis par agence' );
                      
			$PACKAGE['Administration']['Course'] = array(
				'Course' => 'Courses', 
				'Session' => 'Session',
				'Reunion' => 'Réunion',
				'Numerocourse' => 'Numéro de course',
				// 'Typecourse' => 'Type course',
				// 'Typecombinaison' => 'Type combinaison',
				// 'Typegain' => 'Type gain',
				'Exercice' => 'Exercice'
			);
			 
			$PACKAGE['Jeu']['Pari'] = array('Jeu' => 'Gestion des jeux',
				'PayForMe' => 'Gestion des payforme'
			 );
			$listeController = $listePackage = '';
			$modulesConcernes = array();
			$menuDuPackage = '';
			foreach ($PACKAGE as $menu => $module) {
				foreach ($module as $package => $tabController) {
					if($package == $packageP){
						$menuDuPackage = $menu;
						foreach ($tabController as $controller => $titre) {
							$active = $controller==$controllerP?' class="active"':'';
						  	$listeController .= '<a href="'.\app\PATH.$controller.'/index">'.$titre.'</a> |'.PHP_EOL;
						}
					}

				}
			}

			foreach ($PACKAGE as $menu => $module) {
				if($menuDuPackage == $menu){
					foreach ($module as $package => $tabController) {
						$controllers = array_keys($tabController);
						$active = $package==$packageP?' class="active"':'';
					  	$listePackage .= '<li'.$active.'><a href="'.\app\PATH.$controllers[0].'/index">'.$package.'</a></li>'.PHP_EOL;
					}
				}
			}

			$entete_module = '<nav class="navbar navbar-inverse  navbar-static-top">
				<div class="container-fluid">
				  <div id="navbar" class="navbar-collapse">
					<ul class="nav navbar-nav">'.$listePackage.'
					</ul>
				  </div><!--/.nav-collapse -->
				</div><!--/.container-fluid -->
			  </nav>';

			$entete_controller = '
			<!-- Breadcrumbs line -->

                        <div class="col-lg-8 controller_div">
                        '.$listeController.'
                        </div>
			<!-- /Breadcrumbs line -->';
			return array('entete_module' => $entete_module, 'entete_controller' => $entete_controller, 'titre' => $menuDuPackage);
		}

                
		public static function menu($packageP = null)
		{
			// $packageP = strtoupper($packageP);

			$PACKAGE = array('Tableau de bord' => array('defaultController' => 'Statistique', 'icon' => 'dashboard'),
				'Service clients' => array('defaultController' => 'Notification', 'icon' => 'bar-chart'),
				'Paris et jeux' => array('defaultController' => 'Jeu', 'icon' => 'edit'),
				'Administration' => array('defaultController' => 'Utilisateur', 'icon' => 'desktop'),
				'Gestion des logs' => array('defaultController' => 'Construction', 'icon' => 'bar-chart')
				);
			$listePackage = '';
			foreach ($PACKAGE as $key => $elements) {
				$active = '';
				if($key == $packageP){
					$active = ' class="current"';

				}
			  	$listePackage .= '<li'.$active.'><a href="'.\app\PATH.$elements['defaultController'].'/index">
			  	<i class="icon-'.$elements['icon'].'"></i>'.$key.'</a></li>'.PHP_EOL;

			}
			return $listePackage;
		}
	}
        
}
