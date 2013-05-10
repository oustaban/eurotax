<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Initial import controller.
 *
 */
class InitialImportController extends Controller
{

	/**
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
		
		
					foreach($rows as $row) {
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
		
						if($client_id === false) {
							continue;
						}
		
						if($type == 'COURANT') {
							$class = 'compte';
						} elseif($type == 'DEPOT') {
							$class = 'comptededepot';
						}
		
						$adminVar = 'admin'.$class;
						if(is_null($$adminVar)) {
							$adminCode = 'application.sonata.admin.'.$class;
							$admin = $this->container->get('sonata.admin.pool')->getAdminByAdminCode($adminCode);
							$admin->setValidateImport();
							$$adminVar = $admin;
						}
		
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
								'_token' => $this->get('form.csrf_provider')->generateCsrfToken('unknown')
						);
						$form->bind($formData);
				   
						if ($form->isValid()) {
							try {
								$admin->create($object);
								$inserted++;
							}catch (\Exception $e) {
								$this->get('session')->setFlash('sonata_flash_info|raw', $e->getMessage());
							}
						} else {
							if ($form->getErrors()) {
								$messages = array();
								foreach ($form->getErrors() as $keys => $error) {
									$messages[] =$error->getMessage();
								}
								if(!empty($messages)) {
									$this->get('session')->setFlash('sonata_flash_info|raw', implode("<br/>", $messages));
								}
		
							}
						}
						unset($formData, $form, $form_builder, $object);
					}
				}
		
			} else {
				$this->get('session')->setFlash('sonata_flash_info|raw', 'Please upload a file');
			}
		
			if($inserted > 0) {
				$this->get('session')->setFlash('sonata_flash_info|raw', $inserted . ' rows are inserted.');
			}
			
			return $this->render(':redirects:back.html.twig');
		}

		return $this->render('ApplicationSonataClientBundle:InitialImport:index.html.twig');
	}
	
	
	private function _clientIdByCode($code) {
		/* @var $em \Doctrine\ORM\EntityManager */
		$em = $this->getDoctrine()->getManager();
		 
		$client = $em->getRepository('ApplicationSonataClientBundle:Client')->findOneBy(array('code_client' => $code));
	
		if ($client) {
			return $client->getId();
		}
		 
		return false;
	}
	
	
	
	
	/**
	 * @param $value
	 * @return array
	 */
	private function _dateFormValue($value)
	{
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
