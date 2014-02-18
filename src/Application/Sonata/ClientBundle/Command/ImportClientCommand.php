<?php
namespace Application\Sonata\ClientBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;


class ImportClientCommand extends ContainerAwareCommand {

	protected $_debug = false;
	protected $_mailFrom = 'eurotax@hypernaut.com';
	protected $_client_id = 0;
	protected $_client_import_set = array();
	
	protected $_import_counts = array();
	protected $_import_reports = array();
	/** @var null|\DateTime */
	protected $_import_date = null;
	protected $_current_admin = null;
	
	
	protected $clientImportSkipToLine = 7;
	protected $translator;
	protected $targetProcess;
	
	protected $clientFieldsToImport = array(
			
		'Client' => array(
			// Fields must be identical w/ ClientAdmin form.
			// skip_N fields are empty columns and are must be skipped.
			'fields' => array(
				'code_client',
				'user',
				'nom',
				'nature_du_client',
				'raison_sociale',
				array('location_postal'=> 'adresse_1_postal'), //'adresse_1_postal',
				array('location_postal'=> 'adresse_2_postal'),//'adresse_2_postal',
				array('location_postal'=> 'code_postal_postal'),//'code_postal_postal',
				array('location_postal'=> 'ville_postal'),//'ville_postal',
				array('location_postal'=> 'pays_postal'),//'pays_postal',
				'N_TVA_CEE',
				'activite',
				'date_debut_mission',
				'mode_denregistrement',
				'siret',
				'periodicite_facturation',
				'num_dossier_fiscal',
				'taxe_additionnelle',
				'center_des_impots',
				'periodicite_CA3',
				'niveau_dobligation_id',
				'language',
				'skip_1',
				'autre_destinataire_de_facturation',
				'contact',
				'reference_client',
				'raison_sociale_2',
				array('location_facturation'=> 'adresse_1_facturation'),//'adresse_1_facturation',
				array('location_facturation'=> 'adresse_2_facturation'),//'adresse_2_facturation',
				array('location_facturation'=> 'code_postal_facturation'),//'code_postal_facturation',
				array('location_facturation'=> 'ville_facturation'),//'ville_facturation',
				array('location_facturation'=> 'pays_facturation'),//'pays_facturation',
				'N_TVA_CEE_facture',
				'skip_2',
				'date_fin_mission',
				'N_TVA_FR',
				'date_de_depot_id',
				'teledeclaration',
				'niveau_dobligation_exped_id'
			)
				
				
		),
			
			
		'Contact' => array(
			'fields'=> array(
	
				//Civilité	Nom*	Prénom*	Téléphone 1*	Téléphone 2	Fax	Email*	Raison sociale*	N° Ordre facturation
				'civilite',
				'nom',
				'prenom',
				'telephone_1',
				'telephone_2',
				'fax',
				'email',
				'raison_sociale_societe',
				'affichage_facture_id',
				//'client'
			),
				
			'slices' => array(40, 49),
			'sliceLength' => 9
		),
	
		'Document_1' => array( // Document w/ mandat type
			'fields' => array(
				// (lien ?)	Type	"Mandat Date*"	"Mandat Préavis"	"Mandat Particularité"
				'local_file_path',
				'skip_2',
				'date_document',
				'preavis',
				'particularite',
				'type_document',
				//'client'
			),
			'slices' => array(59),
			'sliceLength' => 5
	
		),
			
		'Document_5' => array( // Document w/ attestation type
			'fields' => array(
				// (lien ?)	Type	"Attestation de TVA Date*"	"Attestation de TVA Particularité"
				'local_file_path',
				'skip_2',
				'date_document',
				'particularite',
				'type_document',
				//'client'
			),
			'slices' => array(65),
			'sliceLength' => 4
				
		),
			
			
		'Document_6' => array( // Document w/ Mandat Spécifique type
			'fields' => array(
				//  (lien ?)	Type	"Mandat spécifique Date*"	"Mandat spécifique Particularité"	"Mandat spécifique Statut Notaire"	"Mandat spécifique Notaire"	"Mandat spécifique Statut Apostille"	"Mandat spécifique Apostille"
				'local_file_path',
				'skip_2',
				'date_document',
				'particularite',
				'statut_document_notaire',
				'date_notaire',
				'statut_document_apostille',
				'date_apostille',
				'type_document',
				//'client'
				
			),
			'slices' => array(70),
			'sliceLength' => 8
	
		),
	
		'Document_2' => array( // Document w/ "Pouvoir" type
			'fields' => array(
				//   (lien ?)	Type 	"Pouvoir Date*"	 "Pouvoir Particularité"	"Pouvoir Statut Notaire"	"Pouvoir Notaire"	"Pouvoir Statut Apostille"	"Pouvoir Apostille"
				'local_file_path',
				'skip_2',
				'date_document',
				'particularite',
				'statut_document_notaire',
				'date_notaire',
				'statut_document_apostille',
				'date_apostille',
				'type_document',
				//'client'
	
			),
			'slices' => array(79),
			'sliceLength' => 8
		),
	
			
		'Document_3' => array( // Document w/ "Accord" type
			'fields' => array(
				//    (lien ?)	Type	"Accord Date*"	"Accord Préavis"	"Accord Particularité"
				'local_file_path',
				'skip_2',
				'date_document',
				'preavis',
				'particularite',
				'type_document',
				//'client'
			),
			'slices' => array(88),
			'sliceLength' => 5
		),
			
			
			
		'Document_4' => array( // Document w/ "Lettre désignation" type
			'fields' => array(
				//  (lien ?)	Type	"Lettre Désignation Date*"	"Lettre Désignation Particularité"
				'local_file_path',
				'skip_2',
				'date_document',
				'particularite',
				'type_document',
				//'client'
			),
			'slices' => array(94),
			'sliceLength' => 4
		),
			
			
			
		'Garantie_1' => array( // Garantie w/ "Garantie Bancaire" type
			'fields' => array(
				// "Garantie Bancaire Montant*"	"Garantie Bancaire Devise*"	"Garantie Bancaire Emetteur*"	"Garantie Bancaire Statut*"	"Garantie Bancaire Num de garantie*"
				// "Garantie Bancaire Date d'émission*"	"Garantie Bancaire Date d'échéance*"	"Garantie BancaireExpiré"	"Garantie Bancaire Note"
				array('montant' => 'montant'),
				array('montant' => 'devise'),
				'nom_de_lemeteur',
				'nom_de_la_banques_id',
				'num_de_ganrantie',
				'date_demission',
				'date_decheance',
				'expire',
				'note',
				'type_garantie',
				//'client'
			),
				
			'slices' => array(101),
			'sliceLength' => 9
		),
	
		'Garantie_2' => array( // Garantie w/ "Dépôt de Garantie" type
			'fields' => array(
				// "Dépôt de Garantie Montant*"	"Dépôt de Garantie Devise*"	"Dépôt de Garantie Statut*"	"Dépôt de Garantie Date d'émission*"	"Dépôt de Garantie Note"
				array('montant' => 'montant'),
				array('montant' => 'devise'),
				'nom_de_la_banques_id',
				'date_demission',
				'note',
				'type_garantie',
				//'client'
			),
	
			'slices' => array(111),
			'sliceLength' => 5
		),
	
		'Garantie_3' => array( // Garantie w/ "Garantie Parentale" type
			'fields' => array(
				// "Garantie Parentale Montant*"	"Garantie Parentale Devise*"	"Garantie Parentale Emetteur*"	"Garantie Parentale Statut*"	"Garantie Parentale Num de garantie*"
				// "Garantie Parentale Date d'émission*"	"Garantie Parentale Date d'échéance*"	"Garantie Parentale Expiré"	"Garantie Parentale Note"
				array('montant' => 'montant'),
				array('montant' => 'devise'),
				'nom_de_lemeteur',
				'nom_de_la_banques_id',
				'num_de_ganrantie',
				'date_demission',
				'date_decheance',
				'expire',
				'note',
				'type_garantie',
				//'client'
			),
			'slices' => array(117),
			'sliceLength' => 9
		),
			
		'Coordonnees' => array(
			'fields'=> array(
				// Nom de la banque*	Adresse 1*	Adresse 2	CP*	Ville*	Pays*	N° de compte	Code Swift*	IBAN*
				'nom',
				array('location'=> 'adresse_1'),//'adresse_1',
				array('location'=> 'adresse_2'),//'adresse_2',
				array('location'=> 'code_postal'),//'code_postal',
				array('location'=> 'ville'),//'ville',
				array('location'=> 'pays'),//'pays',
				'no_de_compte',
				'code_swift',
				'IBAN',
				//'client'
			),
			'slices' => array(127),
			'sliceLength' => 9
		),
	);
	
	
	protected function configure()
	{
		$this
		->setName('clientbundle:import:initial')
		->setDescription('Import initial data')
		->addArgument('target', InputArgument::REQUIRED)
		->addArgument('user_id', InputArgument::REQUIRED)
		->addArgument('file', InputArgument::REQUIRED)
		->addArgument('pid', InputArgument::REQUIRED);
	
	}
	
	
	
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->container = $this->getContainer();
		$this->em = $this->getContainer()->get('doctrine')->getManager();
		//$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
		$this->formCSRF = $this->container->get('form.csrf_provider');
		$this->adminPool = $this->container->get('sonata.admin.pool');
		$this->user = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('id' => $input->getArgument('user_id')));
	
		$this->translator = \AppKernel::getStaticContainer()->get('translator');
		
		
		$token = new UsernamePasswordToken($this->user, $this->user->getPassword(), "public", $this->user->getRoles());
		$this->getContainer()->get("security.context")->setToken($token);
	
		// Fire the login event
		$event = new InteractiveLoginEvent($this->getContainer()->get('request'), $token);
		$this->getContainer()->get("event_dispatcher")->dispatch("security.interactive_login", $event);
	
		
		$this->targetProcess = $input->getArgument('target');
		
		$this->user_id = $input->getArgument('user_id');
		$this->pid = $input->getArgument('pid');
	
		
		$file = $input->getArgument('file');
	
		
		$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
		
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($file);
		$sheet = $objPHPExcel->getSheet(0);
		$rows = $sheet->toArray();
		

		$this->processClient($rows);
		
		
		$this->messages = $this->getCountMessageImports();
		
		$this->sendNotification();
		
		echo serialize(array('messages' => $this->messages, 'import_counts' => $this->_import_counts, 'pid' => $this->pid));
		
	}
	
	
	protected function processClient($rows) {

		for($i = 1; $i < $this->clientImportSkipToLine; $i++) {
			array_shift($rows);
		}
		$rows = array_filter($rows);
		$clientFieldsToImport = $this->clientFieldsToImport['Client']['fields'];

				
		$this->_saveImport('Client', $clientFieldsToImport, $rows);
		$this->_saveImportClientEntities();

		
	}
	
	protected function sendNotification() {
		$inotification = $this->em->getRepository('ApplicationSonataClientOperationsBundle:ImportNotification')->findOneBy(array('pid' => $this->pid));
		if($inotification) {
			$content = implode("<br />", $this->messages);
				
				
			$subject = 'Eurotax ' . ucfirst($this->targetProcess) . ' Import Status: @' . date('m d, Y h:i:s');
			$message = \Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom($this->_mailFrom)
			->setTo($this->user->getEmail())
			->setBody($content, 'text/html');
				
			if ($this->_debug) {
				echo '<pre>';
				echo $content;
				exit;
			}
				
			/** @var $mailer \Swift_Mailer */
			$mailer = $this->container->get('mailer');
				
			$mailer->send($message);
				
				
			$inotification->setNotificationSent(true);
				
			$this->em->persist($inotification);
			$this->em->flush();
				
		}
	
	
	
	}
	
	
	
	private function _truncateClientRelatedTables() {
		$this->em->createQuery("DELETE FROM ApplicationSonataClientBundle:Contact t WHERE t.client = :clientId") ->setParameter('clientId', $this->_client_id)->execute();
		$this->em->createQuery("DELETE FROM ApplicationSonataClientBundle:Document t WHERE t.client = :clientId") ->setParameter('clientId', $this->_client_id)->execute();
		$this->em->createQuery("DELETE FROM ApplicationSonataClientBundle:Garantie t WHERE t.client = :clientId") ->setParameter('clientId', $this->_client_id)->execute();
		$this->em->createQuery("DELETE FROM ApplicationSonataClientBundle:Coordonnees t WHERE t.client = :clientId") ->setParameter('clientId', $this->_client_id)->execute();
	
	}
	
	
	private function _saveImport($class, $fieldsToImport, $rows, $numRowStart = null) {
		
		//var_dump($class);
		if(empty($class)) return;
		
		
		$skipToLine = $this->clientImportSkipToLine;
		$formCSRF = $this->formCSRF;
	
		$adminCode = 'application.sonata.admin.' . strtolower($class);
		$this->_current_admin = $admin = $this->adminPool->getAdminByAdminCode($adminCode);
		
	
		if(!($admin)) return;
		
		
		foreach($rows as $key => $row) {
			if(!is_null($numRowStart)) {
				$key = $numRowStart;
			}
				
			if($class == 'Client') {
				$clientId = 0;
				if(empty($row[0])) {
					continue;
				}
				if(($clientId = $this->_clientIdByCode($row[0])) !== false) {
					$object = $admin->getObject($clientId);
				} else {
					$object = $admin->getNewInstance();
				}
				
				if(!$object) {
					$object = $admin->getNewInstance();
				}
				
				$object->getFilesAbsoluteDir($object);
	
			} else {
	
				$object = $admin->getNewInstance();
			}
	
			$admin->setSubject($object);
			$admin->setValidateImport(true);
	
			$form_builder = $admin->getFormBuilder();
			$form = $form_builder->getForm();
			$form->setData($object);
			$formData = array('_token' => $formCSRF->generateCsrfToken('unknown'));
				
			$fields = array();
	
			$i = current(array_keys($row));
			foreach($fieldsToImport as $field) {
				if(is_array($field)) {
					foreach($field as $k => $v) {
						$value = $this->getFormData($v, $row[$i]);
						$formData[$k][$v] = $value;
						$field = $v;
					}
				} else {
					if(strstr($field, 'skip_')) {
						$i++;
						continue;
					}
					if(isset($row[$i])) {
						$value = $this->getFormData($field, $row[$i]);
						$formData[$field] = $value;
					}
				}
				$fields[$field] = $i;
	
				$i++;
			}
	
				
			//var_dump($formData); exit;
				
				
				
			$form->bind($formData);
			if ($form->isValid()) {
				try {
					if(isset($clientId) && $clientId) {
						$admin->update($object);
						$this->setCountImports($class, 'updated');
					} else {
						$admin->create($object);
						$clientId = $object->getId();
						$this->setCountImports($class, 'inserted');
					}
						
					if($class == 'Client') {
						$this->_client_id = $clientId;
						$this->_client_import_set['rows'][$clientId] = $row;
						$this->_client_import_set['numRowStart'][$clientId] = $key;
	
	
						$this->_truncateClientRelatedTables();
						/* if($this->_client_id && $class != 'Client') {
						 $this->_current_admin->setClient($this->_client_id);
						} */
					}
				} catch (\Exception $e) {
	
					$message = $this->getErrorsAsString($fields, $class, $admin, $form, $key + ($skipToLine), 0, 0, $e->getMessage());
					$this->setCountImports($class, 'errors', $message);
	
				}
			} else {
	
				$message = $this->getErrorsAsString($fields, $class, $admin, $form, $key + ($skipToLine));
				$this->setCountImports($class, 'errors', $message);
					
			}
			unset($formData, $form, $form_builder, $object);
		}
	
	}
	
	
	
	
	private function _saveImportClientEntities() {
	
		$tabs = $this->clientFieldsToImport;
		unset($tabs['Client']);
			
		foreach($this->_client_import_set['rows'] as $clientId => $row) {
			foreach($tabs as $class => $tab) {
				$newclass = array();
				if(strstr($class, '_') !== false) {
					$newclass = explode('_', $class);
					$class = $newclass[0];
				}
				$newRows = array();
				foreach($tab['slices'] as $offset) {
					$newRow = array_slice($row, $offset, $tab['sliceLength'], true);
	
					$indexes = array_keys($newRow);
					$index = end($indexes);
	
	
					$checkNewRow = array_filter($newRow);
					if(!empty($checkNewRow)) {
						if(isset($newclass[1])) {
							$newRow[++$index] = (int) $newclass[1];
						}
							
						$newRow[++$index] = $clientId;
						$newRows[] = $newRow;
					}
				}

			
				
				$this->_saveImport($class, $tab['fields'], $newRows, $this->_client_import_set['numRowStart'][$clientId]);
			}
	
		}
	}	
	
	



	protected function getFormData($field, $value) {
		$field = ucfirst(\Doctrine\Common\Util\Inflector::camelize($field));
		$method = '_get' . $field;
		if(method_exists($this, $method)) {
			return $this->$method($value);
		}
		return $value;
	}
	
	
	protected function _clientIdByCode($code) {
		$client = $this->em->getRepository('ApplicationSonataClientBundle:Client')->findOneBy(array('code_client' => $code));	
		if ($client) {
			return $client->getId();
		}
			
		return false;
	}
	
	/**
	 * @param $value
	 * @return array
	 */
	protected function _dateFormValue($value) {
		if ($value) {
			$t = strtotime($value);
	
			if (!$t) {
				$t = \PHPExcel_Shared_Date::ExcelToPHP($value);
			}
	
			$value = date('d/m/Y', $t);
	
			return $value;
		}
		return null;
	}
	
	/**
	 *
	 *
	 * @param string $value
	 * @return boolean|NULL
	 */
	protected function _checkedFormValue($value) {
		$value = strtoupper($value);
		if($value == 'OUI') {
			return true;
		}
	
		return null;
	}
	
	
	
	/** Client Entity **/
	
	protected function _getUser($value) {
		$newvalue = explode(' ', $value);
	
		$firstname = $newvalue[0];
		$lastname = isset($newvalue[1]) ? $newvalue[1] : '';
		$user = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('firstname' => $firstname, 'lastname' => $lastname));
	
		if($user) {
			return $user->getId();
		}
		return $value;
	}
	
	/**
	 * nature_du_client_id
	 */
	protected function _getNatureDuClient($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListNatureDuClients')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	
	/**
	 * pays_id_postal
	 */
	protected function _getPaysPostal($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListCountries')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getCode();
		}
		return $value;
	
	}
	
	/**
	 * mode_denregistrement_id
	 */
	protected function _getModeDenregistrement($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListModeDenregistrements')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	/**
	 * periodicite_facturation_id
	 */
	protected function _getPeriodiciteFacturation($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListPeriodiciteFacturations')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	/**
	 * center_des_impots_id
	 */
	protected function _getCenterDesImpots($value) {
		$obj = $this->em->getRepository('ApplicationSonataImpotsBundle:Impots')->findOneBy(array('nom' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	/**
	 * periodicite_CA3_id
	 */
	protected function _getPeriodiciteCA3($value) {
		return $this->_getPeriodiciteFacturation($value);
	}
	
	
	/**
	 * language_id
	 */
	protected function _getLanguage($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListLanguages')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	
	
	/**
	 * pays_id_facturation
	 */
	protected function _getPaysFacturation($value) {
		return $this->_getPaysPostal($value);
	}
	
	
	/**
	 * date_debut_mission
	 */
	protected function _getDateDebutMission($value) {
		return $this->_dateFormValue($value);
	}
	
	
	/**
	 * date_fin_mission
	 */
	protected function _getDateFinMission($value) {
		return $this->_dateFormValue($value);
	}
	
	/**
	 * taxe_additionnelle
	 */
	protected function _getTaxeAdditionnelle($value) {
		return $this->_checkedFormValue($value);
	}
	
	/**
	 * autre_destinataire_de_facturation
	 */
	protected function _getAutreDestinataireDeFacturation($value) {
		return $this->_checkedFormValue($value);
	}
	
	/**
	 * teledeclaration
	 */
	protected function _getTeledeclaration($value) {
		return $this->_checkedFormValue($value);
	}
	
	/** End Client Entity **/
	
	
	/** Contact Entity **/
	
	protected function _getCivilite($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListCivilites')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	/** End Contact Entity **/
	
	
	
	/** Coordonnees Entity **/
	
	protected function _getPays($value) {
		return $this->_getPaysPostal($value);
	}
	/** End Coordonnees Entity **/
	
	
	/** Document Entity **/
	
	/**
	 * date_document
	 */
	protected function _getDateDocument($value) {
		return $this->_dateFormValue($value);
	}
	
	/**
	 * date_notaire
	 */
	protected function _getDateNotaire($value) {
		return $this->_dateFormValue($value);
	}
	
	/**
	 * date_apostille
	 */
	protected function _getDateApostille($value) {
		return $this->_dateFormValue($value);
	}
	/** End Document Entity **/
	
	
	
	
	protected function _getStatutDocument($value) {
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListStatutDocuments')->findOneBy(array('name' => $value));
	
		if($obj) {
			return $obj->getId();
		}
	
		if('obtenu' == strtolower($value)) {
			return '';
		}
	
		return $value;
	}
	
	
	/**
	 * statut_document_notaire
	 */
	protected function _getStatutDocumentNotaire($value) {
		return $this->_getStatutDocument($value);
	}
	
	/**
	 * statut_document_apostille
	 */
	protected function _getStatutDocumentApostille($value) {
		return $this->_getStatutDocument($value);
	}
	
	
	/** End Document Entity **/
	
	
	
	/** Garantie Entity **/
	
	
	
	protected function _getMontant($value) {
		//$value =  str_replace(array(' ', ',', '€'), array('', '.', ''), $value);
		//$value = str_replace(array("\n","\t","\r"), " ", $formRequestData[$field]);
		$value =  str_replace(array(' ', ','), array('', '.'), $value);
		$value = preg_replace('/[^(\x20-\x7F)]*/','', $value);
		return $value;
	}
	
	/**
	 * devise
	 */
	protected function _getDevise($value) {
		$value = strtoupper($value);
		//var_dump($value);
	
		
		$obj = $this->em->getRepository('ApplicationSonataClientBundle:ListDevises')->findOneBy(array('alias' => $value));
	
		if($obj) {
			return $obj->getId();
		}
		return $value;
	}
	
	/**
	 *statut | nom_de_la_banques_id
	 */
	protected function _getNomDeLaBanquesId($value) {
		//return $this->_getStatutDocument($value);
		$choices = array_flip(\Application\Sonata\ClientBundle\Entity\Garantie::getNomDeLaBanques());
		if(isset($choices[$value])) {
			return $choices[$value];
		}
	
		if('obtenu' == strtolower($value)) {
			return 0;
		}
	
		return $value;
	}
	
	
	/**
	 * date_demission
	 */
	protected function _getDateDemission($value) {
		return $this->_dateFormValue($value);
	}
	
	/**
	 * date_decheance
	 */
	protected function _getDateDecheance($value) {
		return $this->_dateFormValue($value);
	}
	
	
	/**
	 * expire
	 */
	protected function _getExpire($value) {
		return $this->_checkedFormValue($value);
	}
	
	
	
	/** End Garantie Entity **/
	
	
	
	
	protected function getErrorsAsString($fields, $class, $admin, \Symfony\Component\Form\FormInterface $form, $line, $level = 0, $field = '', $message = null) {
		$errors = array();
		if ($form->getErrors()) {
			$one_view = array();
			foreach ($form->getErrors() as $keys => $error) {
				if (!isset($one_view[$field][$line])) {
					$one_view[$field][$line] = true;
					$repeat = str_repeat(' ', $level);
					$label = isset($fields[$field]) ? '(' . ((\PHPExcel_Cell::stringFromColumnIndex($fields[$field])) . ':' . $line) . ') ' : '';
					$data = $form->getViewData();
					if (is_array($data)) {
						$data = implode('-', $data);
					}
	
					if ($error->getMessageParameters()) {
						$data = implode($error->getMessageParameters());
					}
					$errors[] = $repeat . 'VALUE : ' . $label . ($data ? : 'empty') . "\n";
					$errors[] = $repeat . 'ERROR : ' . $error->getMessage() . "\n\n";
				}
			}
		}
	
		foreach ($form->getChildren() as $field => $child) {
			if ($err = $this->getErrorsAsString($fields, $class, $admin, $child, $line, ($level + 4), $field, null)) {
				$errors[] = $admin->trans('form.' . $class . '.' . $field) . "\n";
				$errors[] = $err;
			}
		}
	
		if (!empty($message)) {
			$errors[] = $message;
		}
	
		return implode($errors);
	}
	
	
	/**
	 * @return array
	 */
	protected function getCountMessageImports()
	{
		$translator = $this->translator;
		$messages = array();
		$hasErrors = false;
		$errorCount = 0;
	
		foreach ($this->_import_counts as $table => $values) {
	
				
			if($table == 'rows') {
				continue;
			}
				
			$str_repeat = str_repeat('&nbsp;', 4);
			$message = array();
				
			$table_trans = $translator->trans('form.' . $table . '.title');
			if (isset($values['success'])) {
				$message[] = $str_repeat . $translator->trans('Imported %table% : %count%', array(
					'%table%' => $table,
					'%count%' => $values['success'],
				));
			}
			if (isset($values['updated'])) {
				$message[] = $str_repeat . $translator->trans('Updated %table% : %count%', array(
					'%table%' => $table,
					'%count%' => $values['updated'],
				));
			}
			if (isset($values['inserted'])) {
				$message[] = $str_repeat . $translator->trans('Inserted %table% : %count%', array(
					'%table%' => $table,
					'%count%' => $values['inserted'],
				));
			}
				
			if (isset($values['success'])) {
				$message[] = $str_repeat . $translator->trans('Imported : %count%', array('%count%' => $values['success']));
			}
			if (isset($values['errors'])) {
				$message[] = $str_repeat . '<span class="error">' . $translator->trans('Not valid : %count%', array('%count%' => $values['errors'])) . '</span>';
			}
				
	
			$messages[] = '<strong>' . $table_trans . '</strong><br />' . implode('; ', $message);
				
				
			if (isset($values['errors'])) {
				$hasErrors = true;
				$errorCount++;
			}
				
		}
	
	
	
		if ($hasErrors) {
			$error_log_filename = '/data/imports/import-error-log-' . md5(time() . rand(1, 99999999)) . '.txt';
	
			$render_view_popup = $this->container->get('templating')->render('ApplicationSonataClientBundle:popup:popup_message.html.twig', array(
				'error_reports' => $this->_import_reports,
				'active_tab' => '',
				'import_id' =>  null,
			));
	
			preg_match('#<div class="modal-body">(.*)#is', $render_view_popup, $matches);
			file_put_contents(DOCUMENT_ROOT.$error_log_filename, strip_tags($matches[1]));
	
			$errorMessage[] = '<a id="error_repost_show" href="#">View errors</a> <a target="_blank" href="'.$error_log_filename.'">Save error log</a>' . $render_view_popup;
		}
		$messages[] = '<br />' . implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $errorMessage);
		return $messages;
	}
	
	
	protected function setCountImports($table, $type, $messages = '')
	{
		if (!isset($this->_import_counts['rows'][$type])) {
			$this->_import_counts['rows'][$type] = 0;
		}
	
		if (!isset($this->_import_counts[$table][$type])) {
			$this->_import_counts[$table][$type] = 0;
		}
	
		$this->_import_counts['rows'][$type]++;
		$this->_import_counts[$table][$type]++;
	
		if (!empty($messages)) {
			$this->_import_reports[$table][] = $messages;
		}
	}
	
}