<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


use Application\Sonata\ClientOperationsBundle\Entity\ImportNotification;

/**
 * Initial import controller.
 *
 */
class InitialImportController extends Controller {
	
	/**
	 * Compte
	 * 
	 * {@inheritdoc}
	 */
	public function indexAction()
	{
		set_time_limit(0);
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$inserted = 0;
		
			if (!empty($_FILES) && !empty($_FILES["inputFile"]["name"])) {
				$file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
				$tmpFile = $_FILES["inputFile"]["tmp_name"];
				$inputFile = $_FILES['inputFile'];
				 
				if (move_uploaded_file($tmpFile, $file)) {
					/* @var $objReader \PHPExcel_Reader_Excel2007 */
					$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
		
					if (get_class($objReader) == 'PHPExcel_Reader_CSV') {
						$this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Fichier non lisible'));
						return $this->render(':redirects:back.html.twig');
					} else {
						$objReader->setReadDataOnly(true);
					}
					$objPHPExcel = $objReader->load($file);
					$sheet = $objPHPExcel->getSheet(0);
					file_put_contents($tmpFile, '');
					$rows = $sheet->toArray();
					array_shift($rows); //remove column header
					$rows = array_filter($rows);
		
					static $admincompte = null;
					static $admincomptededepot = null;
		
					static $clients = array();
					$skipToLine = 2;
					
					$fields = array(
						'date' => 0,
						'operation' => 1,
						'montant' => 2,
					);
					
					
					foreach($rows as $key => $row) {
						if(empty($row[0])) {
							continue;
						}
						
						
						$date = $this->_dateFormValue($row[0]);
						$libelle = (string)$row[1];
						$montant = (double)$row[2];
						$client_code = (int)$row[3];
						$type = $row[4];
						$class = '';
						$client_id = $this->_clientIdByCode($client_code);
								
						if($type == 'COURANT') {
							$class = 'compte';
							$entity = 'Compte';
						} elseif($type == 'DEPOT') {
							$class = 'comptededepot';
							$entity = 'CompteDeDepot';
						}
						
						if($client_id === false) {
							$label = '(' . \PHPExcel_Cell::stringFromColumnIndex(3) . ':' . ($key+$skipToLine) . ') ';
							$message = 'VALUE : ' . $label . $client_code . "\n" .
							 	"ERROR : Client does not exist in the system. \n\n";
							$this->setCountImports($entity, 'errors', $message);
							continue;
						}
 
						if(!isset($clients[$class][$client_id])) {
							$em = $this->getDoctrine()->getManager();
							$em->createQuery("DELETE FROM ApplicationSonataClientBundle:$entity t WHERE t.client = :clientId") ->setParameter('clientId', $client_id)->execute();
							$clients[$class][$client_id] = $client_id;
						}
						
						$adminVar = 'admin'.$class;
						if(is_null($$adminVar)) {
							$adminCode = 'application.sonata.admin.'.$class;
							$admin = $this->container->get('sonata.admin.pool')->getAdminByAdminCode($adminCode);
							$admin->setValidateImport();
							$$adminVar = $admin;
						}
		
						$this->_current_admin = $admin;
						
						$object = $admin->getNewInstance();
						$admin->setSubject($object);
		
						/* @var $form \Symfony\Component\Form\Form */
						$form_builder = $admin->getFormBuilder();
						$form = $form_builder->getForm();
						$form->setData($object);
		
						$formData = array(
								'date' => $date,
								'operation' => $libelle,
								'montant' => $montant,
								'client' => $client_id,
								'statut' => 1, //Reel
								'_token' => $this->get('form.csrf_provider')->generateCsrfToken('unknown')
						);
						$form->bind($formData);
				   
						if ($form->isValid()) {
							try {
								$admin->create($object);
								$inserted++;
								
								$this->setCountImports($entity, 'inserted');
								
							}catch (\Exception $e) {
								//$this->get('session')->setFlash('sonata_flash_info|raw', $e->getMessage());
								$message = $this->getErrorsAsString($fields, $entity, $admin, $form, $key + ($skipToLine), 0, 0, $e->getMessage());
								$this->setCountImports($entity, 'errors', $message);
								
								
							}
						} else {
							$message = $this->getErrorsAsString($fields, $entity, $admin, $form, $key + ($skipToLine));
							$this->setCountImports($entity, 'errors', $message);
					
						}
						unset($formData, $form, $form_builder, $object);
					}
				}
		
			} else {
				$this->get('session')->setFlash('sonata_flash_info|raw', 'Please upload a file');
			}
		
			$messages = $this->getCountMessageImports();
			if (!empty($messages)) {
				$this->get('session')->setFlash('sonata_flash_info|raw', implode("<br />", $messages));
			}
			
			return $this->render(':redirects:back.html.twig');
		}

		return $this->render('ApplicationSonataClientBundle:InitialImport:index.html.twig');
	}
	
	
	/**
	 * 	  
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function numeroAction() {
		set_time_limit(0);
		
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$inserted = 0;
		
			if (!empty($_FILES) && !empty($_FILES["inputFile"]["name"])) {
				$file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
				$tmpFile = $_FILES["inputFile"]["tmp_name"];
				$inputFile = $_FILES['inputFile'];
					
				if (move_uploaded_file($tmpFile, $file)) {
					/* @var $objReader \PHPExcel_Reader_Excel2007 */
					$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
		
					if (get_class($objReader) == 'PHPExcel_Reader_CSV') {
						$this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Fichier non lisible'));
						return $this->render(':redirects:back.html.twig');
					} else {
						$objReader->setReadDataOnly(true);
					}
					$objPHPExcel = $objReader->load($file);
					$sheet = $objPHPExcel->getSheet(1); // Base Donniee tab
					
					file_put_contents($tmpFile, '');
					$rows = $sheet->toArray();
					array_shift($rows); //remove column header
					$rows = array_filter($rows);
		
					$this->_current_admin = $admin = $this->container->get('sonata.admin.pool')->getAdminByAdminCode('application.sonata.admin.numero_TVA');
					
					static $clients = array();
					$entity = 'NumeroTVA';
					$skipToLine = 2;
					
					$fields = array(
						'date_de_verification' => 5,
						'code' => 2,
						'n_de_TVA' => 4,
					);
					
					foreach($rows as $key => $row) {
						if(empty($row[0])) {
							continue;
						}
						// Columns  A, C,  E and F
						$date_de_verification = $this->_dateFormValue($row[5]);
						$nom = (string)$row[2];
						$n_de_TVA = (string)$row[4];
						$client_code = (int)$row[0];						
						$client_id = $this->_clientIdByCode($client_code);
		
						if($client_id === false) {
							$label = '(' . \PHPExcel_Cell::stringFromColumnIndex(3) . ':' . ($key+$skipToLine) . ') ';
							$message = 'VALUE : ' . $label . $client_code . "\n" .
									"ERROR : Client does not exist in the system. \n\n";
							$this->setCountImports($entity, 'errors', $message);
							continue;
						}
						
						if(!isset($clients[$client_id])) {
							$em = $this->getDoctrine()->getManager();
							$em->createQuery("DELETE FROM ApplicationSonataClientBundle:NumeroTVA t WHERE t.client = :clientId") ->setParameter('clientId', $client_id)->execute();
							$clients[$client_id] = array('inserted' => 0);
						}
	
						$object = $admin->getNewInstance();
						$admin->setSubject($object);
		
						/* @var $form \Symfony\Component\Form\Form */
						$form_builder = $admin->getFormBuilder();
						$form = $form_builder->getForm();
						$form->setData($object);
		
						$formData = array(
								'date_de_verification' => $date_de_verification,
								'code' => $nom,
								'n_de_TVA' => $n_de_TVA,
								'client' => $client_id,
								'_token' => $this->get('form.csrf_provider')->generateCsrfToken('unknown')
						);
						$form->bind($formData);
							
						if ($form->isValid()) {
							try {
								$admin->create($object);
								$inserted++;
								
								
								++$clients[$client_id]['inserted'];
								$this->setCountImports($entity, 'inserted');
								
							}catch (\Exception $e) {
								//$this->get('session')->setFlash('sonata_flash_info|raw', $e->getMessage());
								$message = $this->getErrorsAsString($fields, $entity, $admin, $form, $key + ($skipToLine), 0, 0, $e->getMessage());
								$this->setCountImports($entity, 'errors', $message);
								
								
							}
						} else {
							$message = $this->getErrorsAsString($fields, $entity, $admin, $form, $key + ($skipToLine));
							$this->setCountImports($entity, 'errors', $message);
						}
						unset($formData, $form, $form_builder, $object);
					}
				}
		
			} else {
				$this->get('session')->setFlash('sonata_flash_info|raw', 'Please upload a file');
			}
		
			$messages = $this->getCountMessageImports();
			if (!empty($messages)) {
				$this->get('session')->setFlash('sonata_flash_info|raw', implode("<br />", $messages));
			}
			return $this->render(':redirects:back.html.twig');
		}
		
		return $this->render('ApplicationSonataClientBundle:InitialImport:numero.html.twig');
	}

	
	public function clientAction() {
		set_time_limit(0);
		//ini_set('max_execution_time', 0);
		//ini_set('memory_limit', '256M');
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
	
			if (!empty($_FILES) && !empty($_FILES["inputFile"]["name"])) {
				$file = TMP_UPLOAD_PATH . '/' . str_replace(array(' ', '+', '(', ')'), '', $_FILES["inputFile"]["name"]);
				$tmpFile = $_FILES["inputFile"]["tmp_name"];
				$inputFile = $_FILES['inputFile'];
					
				if (move_uploaded_file($tmpFile, $file)) {
					/* @var $objReader \PHPExcel_Reader_Excel2007 */
					$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
	
					if (get_class($objReader) == 'PHPExcel_Reader_CSV') {
						$this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Fichier non lisible'));
						return $this->render(':redirects:back.html.twig');
					} 
					
					$_FILES = array();
					//create fake process id
					$pid = time();
						

					$user = $this->get('security.context')->getToken()->getUser();
					$kernel = $this->get('kernel');
					$command = 'php '. $kernel->getRootDir() .'/console clientbundle:import:initial client '
						. $user->getId() . ' ' . realpath($file) . ' ' . $pid . ' --env='. $kernel->getEnvironment(); // . ' --no-debug ';
					 
					
					/* var_dump($command);
					exit; */
					$process = new \Symfony\Component\Process\Process($command);
					$process->setTimeout(3600);
					 
					$process->start();
					$start = microtime(true);
					 
					while ($process->isRunning()) {
						$total = microtime(true) - $start;
						if(($total/60) >= 2) { // if process is too long (2 minutes)
							//var_dump(($total/60));
							 
							$em = $this->getDoctrine()->getManager();
							 
							$importNotif = new ImportNotification();
							$importNotif->setPid($pid)
							->setUser($user)
							->setClientId(0);
							 
							$em->persist($importNotif);
							$em->flush();
							 
							$this->_hasImportErrors = true;
							 
							$this->get('session')->setFlash('sonata_flash_error', \AppKernel::getStaticContainer()->get('translator')->trans('There are too many data to be processed. We will just notify you once it\'s done. Check your email ('.$user->getEmail().') within few hours.'));
							 
							break;
							return $this->render(':redirects:back.html.twig');
						}
					}
					 
					$output = unserialize($process->getOutput());
					$messages = $output['messages'];
					$import_counts = $output['import_counts'];
				}
	
			} else {
				$this->get('session')->setFlash('sonata_flash_error|raw', 'Please upload a file');
			}
	
			//$messages = $this->getCountMessageImports();
			if (!empty($messages)) {
				$this->get('session')->setFlash('sonata_flash_info|raw', implode("<br />", $messages));
			}
	
			return $this->render(':redirects:back.html.twig');
		}
	
		return $this->render('ApplicationSonataClientBundle:InitialImport:client.html.twig');
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
	
}
