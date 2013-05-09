<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


/**
 * AbstractTabsController controller.
 *
 */
abstract class AbstractTabsController extends Controller
{

    /**
     * @var int
     */
    public $client_id = null;

    /**
     * @var \Application\Sonata\ClientBundle\Entity\Client
     */
    protected $client = null;

    /**
     * @var string
     */
    protected $_tabAlias = '';
    protected $_template = null;

    /**
     * @var string
     */
    protected $_jsSettingsJson = null;

    public function configure()
    {
        parent::configure();

        if (empty($this->admin->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }
        $this->client_id = $this->admin->client_id;
        $this->client = $this->admin->getClient();

        if (empty($this->client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $this->admin->client_id));
        }
    }

    

    
    
    
    /**
     * @return \Application\Sonata\ClientBundle\Entity\Client|null
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param $data
     * @param string $action
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($data, $action = 'create', $template = 'standard_layout')
    {

        if ($this->isXmlHttpRequest()) {
            return $data;
        }

        if ($this->_template) {
            $template = $this->_template;
        }

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $this->getClient(),
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'action' => $action,
            'js_settings_json' => $this->_jsSettingsJson,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction(), 'create');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction(), 'edit');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->_action(parent::listAction(), 'list');
    }

    /**
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $action = parent::deleteAction($id);

        if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
            ));
        }

        return $action;
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_create_and_edit')) {
            $url = $this->admin->generateUrl('list');

            return new RedirectResponse($url);
        }

        return parent::redirectTo($object);
    }

    /**
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if ($parameters && isset($parameters['action'])) {

            switch ($parameters['action']) {
                case 'list':
                case 'edit':
                case 'create':
                    if ($this->get('request')->getMethod() != 'POST') {
                        $parameters['base_template'] = 'ApplicationSonataClientBundle::ajax_layout.html.twig';
                    }
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

    /**
     * @param array $data
     */
    public function jsSettingsJson(array $data)
    {
        $this->_jsSettingsJson = json_encode($data);
    }
    
    
    
    
    /**
     * {@inheritdoc}
     */
    public function initialImportAction()
    {
    	set_time_limit(0);
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
