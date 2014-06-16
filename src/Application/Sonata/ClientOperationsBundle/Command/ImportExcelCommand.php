<?php
namespace Application\Sonata\ClientOperationsBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

use Application\Sonata\ClientOperationsBundle\Entity\Imports;


class ImportExcelCommand extends ContainerAwareCommand {

	protected $_debug = false;
	
	protected $_mailFrom = 'eurotax@hypernaut.com';
	
	protected $em, $formCSRF, $adminPool, $admin, $client, $client_id, $user, $user_id, $pid;
	
	protected $_locking, $_devise = array(), $_year = 0, $_month = 0;
	
	protected $_min_error_count = 50; //break import when it reached this number
	
	protected $_import_counts = array();
	protected $_import_reports = array();
	
	/**
	 * @var \Application\Sonata\ClientOperationsBundle\Entity\Imports
	 */
	protected $_imports = null;
	
	
	protected $_config_excel = array(
		'V01-TVA' => array(
			'name' => 'V01-TVA',
			'entity' => 'V01TVA',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'taux_de_TVA',
				'montant_TVA_francaise',
				'montant_TTC',
				'paiement_montant',
				'paiement_devise',
				'paiement_date',
				'mois',
				'taux_de_change',
				'HT',
				'TVA',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
	
		'V03-283-I' => array(
			'name' => 'V03-283-I',
			'entity' => 'V03283I',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'no_TVA_tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'mois',
				'taux_de_change',
				'HT',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
	
		'V05-LIC' => array(
			'name' => 'V05-LIC',
			'entity' => 'V05LIC',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'no_TVA_tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'mois',
				'taux_de_change',
				'HT',
				'regime',
				'DEB',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires', 'regime'),
				'import' => array()
			)
		),
	
		'DEB Exped' => array(
			'name' => 'DEB Exped',
			'entity' => 'DEBExped',
			'skip_line' => 7,
			'fields' => array(
				'n_ligne',
				'nomenclature',
				'pays_destination',
				'valeur_fiscale',
				'regime',
				'valeur_statistique',
				'masse_mette',
				'unites_supplementaires',
				'nature_transaction',
				'conditions_livraison',
				'mode_transport',
				'departement',
				'pays_origine',
				'CEE',
			),
			'skip_fields' => array(
				'export' => array(),
				'import' => array()
			)
		),
		'V07-EX' => array(
			'name' => 'V07-EX',
			'entity' => 'V07EX',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'mois',
				'taux_de_change',
				'HT',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
		'V09-DES' => array(
			'name' => 'V09-DES',
			'entity' => 'V09DES',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'no_TVA_tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'mois',
				'mois_complementaire',
				'taux_de_change',
				'HT',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array(),
				'import' => array()
			)
		),
		'V11-INT' => array(
			'name' => 'V11-INT',
			'entity' => 'V11INT',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'mois',
				'taux_de_change',
				'HT',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
		'A02-TVA' => array(
			'name' => 'A02-TVA',
			'entity' => 'A02TVA',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'taux_de_TVA',
				'montant_TVA_francaise',
				'montant_TTC',
				'paiement_montant',
				'paiement_devise',
				'paiement_date',
				'mois',
				'taux_de_change',
				'HT',
				'TVA',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
		'A04-283-I' => array(
			'name' => 'A04-283-I',
			'entity' => 'A04283I',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'taux_de_TVA',
				'mois',
				'taux_de_change',
				'HT',
				'TVA',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
	
		'A06-AIB' => array(
			'name' => 'A06-AIB',
			'entity' => 'A06AIB',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'devise',
				'montant_HT_en_devise',
				'taux_de_TVA',
				'mois',
				'taux_de_change',
				'regime',
				'HT',
				'TVA',
				'DEB',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires', 'regime'),
				'import' => array()
			)
		),
	
		'DEB Intro' => array(
			'name' => 'DEB Intro',
			'entity' => 'DEBIntro',
			'skip_line' => 7,
			'fields' => array(
				'n_ligne',
				'nomenclature',
				'pays_destination',
				'valeur_fiscale',
				'regime',
				'valeur_statistique',
				'masse_mette',
				'unites_supplementaires',
				'nature_transaction',
				'conditions_livraison',
				'mode_transport',
				'departement',
				'pays_origine',
				//'CEE',
			),
			'skip_fields' => array(
				'export' => array(),
				'import' => array()
			)
		),
	
		'A08-IM' => array(
			'name' => 'A08-IM',
			'entity' => 'A08IM',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'taux_de_TVA',
				'TVA',
				'mois',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
	
		'A10-CAF' => array(
			'name' => 'A10-CAF',
			'entity' => 'A10CAF',
			'skip_line' => 1,
			'fields' => array(
				'tiers',
				'date_piece',
				'numero_piece',
				'HT',
				'mois',
				'commentaires',
			),
			'skip_fields' => array(
				'export' => array('commentaires'),
				'import' => array()
			)
		),
	);
	
	
	
	/**
	 * @see Command
	 */
	protected function configure()
	{
		$this
		->setName('clientoperationsbundle:import:excel')
		->setDescription('Import data')
		->addArgument('user_id', InputArgument::REQUIRED, 'user_id')
		->addArgument('client_id', InputArgument::REQUIRED, 'client_id')
		->addArgument('admin', InputArgument::REQUIRED, 'The admin service id')
		->addArgument('file', InputArgument::REQUIRED)
		->addArgument('inputFileName', InputArgument::REQUIRED)
		->addArgument('locking', InputArgument::REQUIRED)
		->addArgument('year', InputArgument::REQUIRED)
		->addArgument('month',	InputArgument::REQUIRED)
		->addArgument('pid', InputArgument::REQUIRED);
		
	}

	
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$this->container = $this->getContainer();	
		$this->em = $this->getContainer()->get('doctrine')->getManager();
		$this->em->getConnection()->getConfiguration()->setSQLLogger(null);
		$this->formCSRF = $this->container->get('form.csrf_provider');
		$this->adminPool = $this->container->get('sonata.admin.pool');
		$this->user = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('id' => $input->getArgument('user_id')));
		
		$token = new UsernamePasswordToken($this->user, $this->user->getPassword(), "public", $this->user->getRoles());
		$this->getContainer()->get("security.context")->setToken($token);
		
		// Fire the login event
		$event = new InteractiveLoginEvent($this->getContainer()->get('request'), $token);
		$this->getContainer()->get("event_dispatcher")->dispatch("security.interactive_login", $event);
				
		$this->admin = $admin = $this->adminPool->getAdminByAdminCode($input->getArgument('admin'));
		$this->client_id = $input->getArgument('client_id');
		$this->user_id = $input->getArgument('user_id');
		$this->pid = $input->getArgument('pid');

		$this->client = $this->em->getRepository("ApplicationSonataClientBundle:Client")->findOneBy(array('id' => $this->client_id));
		
		
		$file = $input->getArgument('file');
		$inputFileName = $input->getArgument('inputFileName');
		
		
		$this->_locking = $input->getArgument('locking');
		
		$this->_year = $input->getArgument('year');
		$this->_month = $input->getArgument('month');
		
		
		
		/* @var $objReader \PHPExcel_Reader_Excel2007 */
		$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($file);
		$sheets = $objPHPExcel->getAllSheets();
		
		$content_arr = array();
		foreach ($sheets as $sheet) {
			$title = $sheet->getTitle();
			$content_arr[$title] = $sheet->toArray();
		}
		
		$objPHPExcel->disconnectWorksheets();
		unset($sheets, $objPHPExcel, $objReader);
				
		
		$this->importValidateAndSave($content_arr);
		
		if (empty($this->_import_counts['rows']['errors'])) {
			$this->saveImports($inputFileName);
			$this->importValidateAndSave($content_arr, true);
		}
		
		
		$this->messages = $this->getCountMessageImports();
		
		$this->sendNotification();
		
		echo serialize(array('messages' => $this->messages, 
			'error_counts' => !empty($this->_import_counts['rows']['errors']) ? $this->_import_counts['rows']['errors'] : 0, 
			'import_counts' => $this->_import_counts, 
			'pid' => $this->pid));
		
		
		
	}
	
	
	protected function sendNotification() {
		
		
		$inotification = $this->em->getRepository('ApplicationSonataClientOperationsBundle:ImportNotification')->findOneBy(array('pid' => $this->pid));
		
		/* var_dump($this->pid);
		var_dump($inotification->getId());
		exit; */
		
		if($inotification) {
			$content = implode("<br />", $this->messages);
			
			
			$subject = 'Eurotax Import Status: ' . $this->client->getNom() . ' @' . date('m d, Y h:i:s');
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
	
	
	
	

	/**
	 * If client has NiveauDobligationId | NiveauDobligationExpedId = 0, disallow import
	 *
	 * @param string $class
	 */
	private function _validateDEBClientNiveauDobligation($class, $sheets) {
		if(!in_array($class, array('DEBExped', 'DEBIntro'))) {
			return;
		}
	
		$client = $this->client;
	
		if($class == 'DEBIntro') {
			$niveauDobligationId = $client->getNiveauDobligationId(); //INTRO
			$hasExcelData = $this->hasExcelData('DEB Intro', $sheets);
		} elseif($class == 'DEBExped') {
			$niveauDobligationId = $client->getNiveauDobligationExpedId(); //DEB
			$hasExcelData = $this->hasExcelData('DEB Exped', $sheets);
		}
	
		if($niveauDobligationId == 0 && $hasExcelData) {
			$message = "VALUE : $niveauDobligationId \n";
			$message .= "ERROR :Le niveau d'obligation est à 0 : On ne devrait pas avoir de données\n\n";
			$this->setCountImports($class, 'errors', $message);
	
			return false;
		}
		 
		return true;
	}
	
	
	/**
	 * NLigne rows must be consecutive 1,2,3,4,5...
	 *
	 * @param unknown $class
	 * @param unknown $data
	 */
	private function _validateDEBNLigne($class, $skip_line, $data) {
		if(!in_array($class, array('DEBExped', 'DEBIntro'))) {
			return;
		}
		$fields = array_flip($this->_config_excel[$this->admin->trans('ApplicationSonataClientOperationsBundle.form.' . $class . '.title')]['fields']);
		 
		$entities = $this->em->getRepository('ApplicationSonataClientOperationsBundle:' . $class)
		->findBy(array('client_id' => $this->client_id, 'mois' =>  new \DateTime("{$this->_year}-{$this->_month}-01")));
		 
		
		//$this->em->clear();
 
		$expectedFirstVal = $entities ? count($entities) + 1 : 1;
		$nlignes = array();
		foreach($data as $row) {
			if((empty($row[0]) && empty($row[4]))) {
				continue;
			}
			$nlignes[] = (float)$row[0];
		}
		 
		$checkConsec = function($d) use ($fields, $skip_line, $class, $expectedFirstVal) {
			$errors = array();
			$count = count($d);
			for($i=0;$i<$count;$i++) {
				$start = $i+$expectedFirstVal;
				if( isset($d[$i]) && $start != $d[$i] ) {
					$line = $skip_line+($i+1);
					$repeat = str_repeat(' ', 4);
					$label = isset($fields['n_ligne']) ? '(' . (($fields['n_ligne'] ? chr($fields['n_ligne'] + 65) : 'A') . ':' . $line) . ') ' : '';
					$errors[] = $repeat . 'VALUE : ' . $label . ($d[$i] ? $d[$i] : '(empty)') . "\n";
					$errors[] = $repeat . 'ERROR : Le numero de ligne n\'est pas correct (numéro consécutif)' . "\n\n";
				}
			}
	
	
			if(!empty($errors)) {
				return $errors;
			}
	
			return true;
		};
		 
		 
		if(!empty($nlignes)) {
			$errors = $checkConsec($nlignes);
			if($data[0][0] != $expectedFirstVal || is_array($errors)) {
				$msg = $this->admin->trans('ApplicationSonataClientOperationsBundle.form.' . $class . '.n_ligne') . "\n";
				$msg .= implode($errors);
				$this->setCountImports($class, 'errors', $msg);
			}
	
	
			if(is_array($errors)) {
				return false;
			} else {
				return true;
			}
		}
		 
		return true;
	}
	
	
	
	protected function hasExcelData($title, $sheets) {
		if(!isset($sheets[$title])) {
			return false;
		}
		 
		$config_excel = $this->_config_excel[$title];
		$data = $sheets[$title];
		$data = $this->skipLine($config_excel, $data);
		 
		if(isset($data)) {
			if(isset($data[0][0]) && !empty($data[0][0])) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	
	
	protected function _validateAIB06DEBIntro($sheets) {
		// if AIB-06 has no data and DEB-Intro has data put a message "AIB-06 vide mais data dans DEB-Intro" and stop the import
		// A06-AIB
		// DEB Intro
		if(!$this->hasExcelData('A06-AIB', $sheets) && $this->hasExcelData('DEB Intro', $sheets)) {
			$this->setCountImports('A06AIB', 'errors', 'AIB-06 vide mais data dans DEB-Intro');
		}
	
		/* if($this->hasExcelData('A06-AIB', $sheets) && !$this->hasExcelData('DEB Intro', $sheets)) {
		 $this->setCountImports('DEBIntro', 'errors', 'DEB-Intro vide mais data dans AIB-06');
		} */
	}
	
	protected function _validateV05LICDEBExped($sheets) {
		//if V05-LIC has no data and DEB-Exped has data put a message "V05-LIC vide mais data dans DEB-Exped" and stop the import
		// A06-AIB
		// DEB Intro
		if(!$this->hasExcelData('V05-LIC', $sheets) && $this->hasExcelData('DEB Exped', $sheets)) {
			$this->setCountImports('V05LIC', 'errors', 'V05-LIC vide mais data dans DEB-Exped');
		}
		 
		/* if($this->hasExcelData('V05-LIC', $sheets) && !$this->hasExcelData('DEB Exped', $sheets)) {
		 $this->setCountImports('DEBExped', 'errors', 'DEB-Exped vide mais data dans V05-LIC');
		} */
	}
	
	
	/**
	 * @param $sheets
	 * @param bool $save
	 */
	protected function importValidateAndSave($sheets, $save = false)
	{
		$this->_validateAIB06DEBIntro($sheets);
		$this->_validateV05LICDEBExped($sheets);
		
		list($current_year, $current_month) = explode('-', date('Y-m', strtotime('now' . (date('d') < 25 ? ' -1 month' : ''))));
		 
		foreach ($sheets as $title => $data) {
			if (array_key_exists($title, $this->_config_excel)) {
				$config_excel = $this->_config_excel[$title];
				$class = $config_excel['entity'];
				$fields = $config_excel['fields'];
				$skip_line = $config_excel['skip_line'];
				$data = $this->skipLine($config_excel, $data);
	
				//DEB Exped | DEB Intro
				if($this->getLocking() && ($class == 'DEBExped' || $class == 'DEBIntro')) {
					continue;
				}
	
				$continue = false;
				if($this->_validateDEBClientNiveauDobligation($class, $sheets) === false) {
					$continue = true;
				}
				if($this->_validateDEBNLigne($class, $skip_line, $data) === false) {
					$continue = true;
				}
	
				if($continue) {
					continue;
				}
	
 				$adminCode = 'application.sonata.admin.' . strtolower($class);
 				/* @var $admin \Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin */
 				$admin = $this->adminPool->getAdminByAdminCode($adminCode);
 				$admin->setDeviseList($this->getDeviseList());
 				$admin->setValidateImport();
 				
				foreach ($data as $key => $line) {
					if ($this->getImportsBreak($data, $key)) {
						break;
					}
					
					
					
					$object = $admin->getNewInstance();
					$admin->setSubject($object);
					$admin->setIndexImport($key + 1);
					$admin->setClientId($this->client_id);
					
					$admin->import_file_year = $this->_year;
					$admin->import_file_month = $this->_month;
	
	
					/* @var $form \Symfony\Component\Form\Form */
					$form_builder = $admin->getFormBuilder();
					$form = $form_builder->getForm();
	
	
					if($object instanceof ListDevises) {
						continue;
					}
	
					$form->setData($object);
					$formData = array('client_id' => $this->client_id, '_token' => $this->formCSRF->generateCsrfToken('unknown'));
					
					foreach ($line as $index => $value) {
						if (isset($fields[$index])) {
							$fieldName = $fields[$index];
							$newValue = $admin->getFormValue($fieldName, $value);
							$formData[$fieldName] = $newValue;
						}
					}
					//exit;
					
					if($class != 'DEBExped' && $class != 'DEBIntro') {
						$_line = $line;
						$mois = false;
					
						if(!empty($formData['mois'])) {
							$mois = $formData['mois'];
								
							if ($mois instanceof \DateTime) {
								$month = $mois->format('n');
								$year = $mois->format('Y');
							} else {
								$month = $mois['month'];
								$year = $mois['year'];
							}
					
							//Only lines with MOIS = current month must be imported.
							if (!$this->admin->getLocking() && !($year == $current_year && $month == $current_month)) {
								continue;
							}
							
					
							if (in_array('commentaires', $fields)) {
								array_pop($_line); // Exclude commentaires column
							}
								
							if (count($_line) != count(array_filter($_line))) {
								//$this->setCountImports($class, 'errors', 'Row ('. ($key + ($skip_line+1)) .') has empty column.');
								$save = false;
							} 
						}
					}
	
					if(in_array('paiement_date', $fields)) {
						//if paiement_date is empty, empty the ff. fields.
						if(!isset($formData['paiement_date']) || (isset($formData['paiement_date']) && empty($formData['paiement_date']))) {
							unset($formData['mois']);
							unset($formData['taux_de_change']);
							unset($formData['HT']);
							unset($formData['TVA']);
						}
					}
	
					// Ensure that CEE field is excluded from existing excel files
					if($class == 'DEBIntro' && isset($formData['CEE'])) {
						unset($formData['CEE']);
					}
	
	
					// Ensure that pays_origine field is excluded from existing excel files
					if($class == 'DEBExped' && isset($formData['pays_origine'])) {
						unset($formData['pays_origine']);
					}
					
					$form->bind($formData);
					if ($form->isValid()) {
						try {
							if ($save) {
								$object->setImports($this->_imports);
								$object->setClientId($this->client_id);
								
								$admin->create($object);
								$this->setCountImports($class, 'success');
							}
						} catch (\Exception $e) {
	
							$message = $this->getErrorsAsString($class, $form, $key + ($skip_line+1), 0, 0, $e->getMessage());
							$this->setCountImports($class, 'errors', $message);
						}
					} else {
						$message = $this->getErrorsAsString($class, $form, $key + ($skip_line+1));
						$this->setCountImports($class, 'errors', $message);
					}
					unset($formData, $form, $form_builder, $object);
					
					if(isset($this->_import_counts['rows']) && isset($this->_import_counts['rows']['errors']) && $this->_import_counts['rows']['errors'] >= $this->_min_error_count) {
						return;
					}	
				}	
				unset($data, $admin);
			}
		}
	}
	
	
	/**
	 * @param $config_excel
	 * @param $data
	 * @return mixed
	 */
	private function skipLine($config_excel, $data)
	{
		$skip_line = $config_excel['skip_line'];
		for ($i = 0; $i < $skip_line; $i++) {
			array_shift($data);
		}
		return $data;
	}
	
	
	
	/**
	 * @param $data
	 * @param $key
	 * @param int $tabs
	 * @return bool
	 */
	protected function getImportsBreak($data, $key, $tabs = 3)
	{
		$data_counter = 0;
	
		$limit = $key + ($tabs - 1);
		for ($i = $key; $i <= $limit; $i++) {
			$line_counter = false;
	
			if (!empty($data[$i]) && $line = $data[$i]) {
				$columns = 0;
				foreach ($line as $value) {
					$columns++;
					if ($columns > 4){
						break;
					}
	
					$value = trim($value);
					if (empty($value) || $value == '#VALUE!') {
						$line_counter = true;
					} else {
						$line_counter = false;
						break;
					}
				}
			}
			if ($line_counter) {
				$data_counter++;
			}
		}
	
		if ($data_counter >= $tabs) {
			return true;
		}
		return false;
	}
	
	/**
	 * @return array
	 */
	protected function getCountMessageImports()
	{
		$translator = $this->admin;
		$messages = array();
	
	
	
		if(!empty($this->_import_counts)) {
			
			
			
			
			foreach ($this->_import_counts as $table => $values) {
	
				$message = array();
				$table_trans = $translator->trans('ApplicationSonataClientOperationsBundle.form.' . $table . '.title');
				switch ($table) {
	
					case 'rows';
					if (isset($values['success'])) {
						$message[] = $translator->trans('Imported %table% : %count%', array(
							'%table%' => $table,
							'%count%' => $values['success'],
						));
					}
					if (isset($values['errors'])) {
	
						//$this->_hasImportErrors = true;
	
						$error_log_filename = '/data/imports/import-error-log-' . md5(time() . rand(1, 99999999)) . '.txt';
						
						
						
						
	
						$render_view_popup = $this->container->get('templating')->render('ApplicationSonataClientOperationsBundle:popup:popup_message.html.twig', array(
							'error_reports' => $this->_import_reports,
							'active_tab' => '',
							'import_id' => $this->_imports ? $this->_imports->getId() : null,
						));
	
						preg_match('#<div class="modal-body">(.*)#is', $render_view_popup, $matches);
						file_put_contents(DOCUMENT_ROOT.$error_log_filename, strip_tags($matches[1]));
	
						$message[] = '<span class="error">' . $translator->trans('Not valid %table% : %count%', array(
							'%table%' => $table,
							'%count%' => $values['errors'],
						)) . '</span>, <a id="error_repost_show" href="#">View errors</a> <a target="_blank" href="'.$error_log_filename.'">Save error log</a>' . $render_view_popup;
					}
	
					$messages[] = implode('&nbsp;&nbsp;|&nbsp;&nbsp;', $message);
					break;
					default;
					$str_repeat = str_repeat('&nbsp;', 4);
					if (isset($values['success'])) {
						$message[] = $str_repeat . $translator->trans('Imported : %count%', array('%count%' => $values['success']));
					}
					if (isset($values['errors'])) {
						//$this->_hasImportErrors = true;
						$message[] = $str_repeat . '<span class="error">' . $translator->trans('Not valid : %count%', array('%count%' => $values['errors'])) . '</span>';
					}
					$messages[] = '<strong>' . $table_trans . '</strong><br />' . implode('; ', $message);
					break;
				}
			}
			 
		}
		return $messages;
	}
	
	/**
	 * @param $table
	 * @param $type
	 * @param string $messages
	 */
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
	
	
	
	protected function getLocking()
	{
		return $this->_locking;
	}
	
	
	
	protected function getDeviseList() {
		//return $this->_devise;
		
		static $devises;

		if (empty($devises)) {
			$objects = $this->em->getRepository('ApplicationSonataClientBundle:ListDevises')->findAll();
			foreach ($objects as $object) {
				$devises[strtolower($object->getAlias())] = $object;
			}
		}
		
		return $devises;
	}
	
	/**
	 * @param $class
	 * @param $form
	 * @param $line
	 * @param int $level
	 * @param string $field
	 * @param null $message
	 * @return string
	 */
	protected function getErrorsAsString($class, $form, $line, $level = 0, $field = '', $message = null)
	{
		$fields = array_flip($this->_config_excel[$this->admin->trans('ApplicationSonataClientOperationsBundle.form.' . $class . '.title')]['fields']);
	
		$errors = array();
	
		if ($form->getErrors()) {
	
			$one_view = array();
			foreach ($form->getErrors() as $keys => $error) {
				/** @var $error \Symfony\Component\Form\FormError */
	
				if (!isset($one_view[$field][$line])) {
	
					$one_view[$field][$line] = true;
	
					$repeat = str_repeat(' ', $level);
					$label = isset($fields[$field]) ? '(' . (($fields[$field] ? chr($fields[$field] + 65) : 'A') . ':' . $line) . ') ' : '';
	
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
			if ($err = $this->getErrorsAsString($class, $child, $line, ($level + 4), $field, null)) {
				$errors[] = $this->admin->trans('ApplicationSonataClientOperationsBundle.form.' . $class . '.' . $field) . "\n";
				$errors[] = $err;
			}
		}
	
		if (!empty($message)) {
			$errors[] = $message;
		}
	
		return implode($errors);
	}
	
	
	/**
	 * saveImports
	 */
	protected function saveImports($inputFileName)
	{
		//$em = $this->getContainer()->get('doctrine')->getManager();
		$user = $this->em->getRepository('ApplicationSonataUserBundle:User')->findOneBy(array('id' => $this->user_id));
	
		//$this->em->clear();

		$import_date = new \DateTime($this->_year . '-' . $this->_month . '-' . date('d H:i:s'));
		
		
		$this->_imports = new Imports();
		$this->_imports->setUser($user);
		$this->_imports->setDate($import_date);
		$this->_imports->setClientId($this->client_id);
		$this->_imports->setFileName($inputFileName);
	
		$this->em->persist($this->_imports);
		$this->em->flush();
	}
	
}
