<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\ClientOperationsBundle\Entity\Locking;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Sonata\ClientOperationsBundle\Entity\Imports;
use Application\Tools\mPDF;
use Application\Sonata\DevisesBundle\Entity\Devises;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;

class AbstractTabsController extends Controller
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
     * @var null|array
     */
    protected $devise = null;

    /**
     * @var string
     */
    protected $_tabAlias = '';
    protected $_operationType = '';
    protected $_jsSettingsJson = null;
    protected $_month = 0;
    protected $_query_month = 0;
    protected $_year = 0;
    protected $_import_counts = array();
    protected $_import_reports = array();
    /** @var null|\DateTime */
    protected $_import_date = null;
    protected $_client_documents = array();
    protected $_client_dir = '';
    protected $_show_all_operations = false;

    
    
    protected $_lockingTab, $_lockingDate, $_lockingMonth, $_lockingYear, 
    	$_unlockingMonth, $_unlockingYear;
    
    /**
     * @var \Application\Sonata\ClientOperationsBundle\Entity\Imports
     */
    protected $_imports = null;

    /**
     * @var array
     */
    protected $_parameters_url = array();

    /**
     * @var array
     */
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
            )
        ),
    );

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function configure()
    {
        parent::configure();
        $this->admin->getRequestParameters($this->getRequest());

        if (empty($this->admin->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }

        $this->client_id = $this->admin->client_id;

        $this->client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

        if (empty($this->client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $this->admin->client_id));
        }

        $this->_month = $this->admin->month;
        $this->_query_month = $this->admin->query_month;
        $this->_year = $this->admin->year;
        $this->_show_all_operations = $this->admin->_show_all_operations;

        $this->_parameters_url['filter']['client_id']['value'] = $this->client_id;

        if ($this->admin->setQueryMonth()) {
            $this->_parameters_url['month'] = $this->_query_month;
        }
        
        if($this->_query_month == -1 && $this->_show_all_operations) {
        	$this->_parameters_url['month'] = 'all';
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
        
        $this->jsSettingsJson(array(
            'url' => array(
                'rdevises' => $this->admin->generateUrl('RDevises', array('filter' => array('client_id' => array('value' => $this->client_id)))),
            ),
        	'locked' => $this->getLocking() ? 1 : 0,
        	'active_tab' => $this->_tabAlias,
        ));

        return $this->render('ApplicationSonataClientOperationsBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $this->getClient(),
            'client_documents' => $this->_client_documents,
            'client_dir' => $this->_client_dir,
            'month_list' => $this->getMonthList(),
            'month' => $this->_month,
            'query_month' => $this->_query_month,
            'year' => $this->_year,
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'operation_type' => $this->_operationType,
            'action' => $action,
            'blocked' => $this->getLocking() ? 0 : 1,
        	'locked' => $this->getLocking() ? 1 : 0,
            'js_settings_json' => $this->_jsSettingsJson,
            '_filter_json' => $this->_parameters_url,
        ));
    }

    
   
    
    /**
     * @return mixed
     */
    protected function getLocking()
    {
        return $this->admin->getLocking();
    }

    /**
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    protected function getLockingAccessDenied()
    {
        if ($this->getLocking()) {
            throw new AccessDeniedException();
        }
    }


    /**
     *
     */
    protected function getObjectMonthYear()
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);

        if ($object) {
            $date_piece = $object->getDatePiece();
            if ($date_piece) {
                $this->_month = $date_piece->format('m');
                $this->_year = $date_piece->format('Y');
            }
        }
    }

    /**
     * @return array
     */
    protected function getMonthList()
    {
        $year = date('Y');

        $month_list = array();
        $month_list[] = array('key' => '', 'name' => 'Operations en cours');
        $minDate = explode('-', date('Y-m', strtotime('-24 days')));

        for ($month = $minDate[1]; $month > date('n') - 12; $month--) {
        
        	$mktime = mktime(0, 0, 0, $month, 1, $minDate[0]);
        
        	$month_list[] = array(
        			'key' => date('n' . $this->admin->date_filter_separator . 'Y', $mktime),
        			'name' => ucwords($this->datefmtFormatFilter(new \DateTime(date('Y-m-d', $mktime)), 'YYYY MMMM')));
        }
        
        $month_list[] = array('key' => 'all', 'name' => 'Toutes les opérations');

        return $month_list;
    }


    /**
     * @param $datetime
     * @param null $format
     * @return string
     */
    public function datefmtFormatFilter($datetime, $format = null)
    {
        $dateFormat = is_int($format) ? $format : \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($format) ? $format : null;

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            $dateFormat,
            $timeFormat,
            $datetime->getTimezone()->getName(),
            $calendar,
            $pattern
        );
        $formatter->setLenient(false);
        $timestamp = $datetime->getTimestamp();

        return $formatter->format($timestamp);
    }

    public function cloneAction($id = null)
    {
        return $this->_action($this->abstractCloneAction($id), 'create', 'form_layout');
    }

    protected function abstractCloneAction($id = null)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $object = $this->admin->getObject($id);
        $object = clone $object;

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        // the key used to lookup the template
        $templateKey = 'edit';

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($object);
        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form' => $view,
            'object' => $object,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
    	if ($this->getLocking() && ($this->_tabAlias == 'debexped' || $this->_tabAlias == 'debintro')) {
    		throw new AccessDeniedException();
    	}
    	
        return $this->_action(parent::createAction(), 'create', 'form_layout');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        $this->getObjectMonthYear();
        //$this->getLocking();
        

        
        if ($this->get('request')->getMethod() == 'POST') {
        	$id     = $this->get('request')->get($this->admin->getIdParameter());
        	if($this->admin->isObjectLocked($id)) {
        		$this->getLockingAccessDenied();
        	}
        }
        
        
        $action = $this->_action(parent::editAction(), 'edit', 'form_layout');
        return $action;
    }

    /**
     * @param mixed $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $this->getObjectMonthYear();
        /* $this->getLocking();
        $this->getLockingAccessDenied(); */

        /** @var $action RedirectResponse */
        //$action = parent::deleteAction($id);

        $id     = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        
        if (!$object) {
        	throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        // status = Vérouillé cannot be deleted
        if($this->admin->isObjectLocked($id)) {
        	$this->get('session')->setFlash('sonata_flash_error', 'On ne peut pas supprimer les éléments vérouillés');

        	if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest()) {
        		return $this->renderJson(array(
        				'result' => 'ok',
        		));
        	} else if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest() === false) {
        		return new RedirectResponse($this->admin->generateUrl('list'));
        	}
        }
        
        
        
        if (false === $this->admin->isGranted('DELETE', $object)) {
        	throw new AccessDeniedException();
        }
        
        
        
        if ($this->getRequest()->getMethod() == 'DELETE') {
        	try {
        		$this->admin->delete($object);
        		$this->get('session')->setFlash('sonata_flash_success', 'flash_delete_success');
        	} catch (ModelManagerException $e) {
        		$this->get('session')->setFlash('sonata_flash_error', 'flash_delete_error');
        	}
        }
        
        
        if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest()) {
        	return $this->renderJson(array(
        			'result' => 'ok',
        	));
        } else if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest() === false) {
        	return new RedirectResponse($this->admin->generateUrl('list'));
        }
        
        
        
        return $this->render($this->admin->getTemplate('delete'), array(
        		'object' => $object,
        		'action' => 'delete'
        ));
        
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function batchAction()
    {

        $date_piece = $this->getRequest()->query->get('date_piece');
        $this->_month = $date_piece['value']['month'];

        $this->getLocking();
        $this->getLockingAccessDenied();

        return parent::batchAction();
    }
    
    

    public function batchActionDelete(ProxyQueryInterface $query)
    {
		// status = Vérouillé cannot be deleted    	 
    	$query->andWhere('o.status = 2 OR o.status IS NULL'); 
    	return parent::batchActionDelete($query);
    }
    

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        $this->getLocking();
        $this->_initClientDocuments();

        $action = $this->_action(parent::listAction(), 'list', 'list_layout');
        return $action;
    }

    /**
     * @return AbstractTabsController
     */
    protected function _initClientDocuments()
    {
        $this->_client_documents = $this->getClient()->getFiles();

        $this->_client_dir = \Application\Sonata\ClientBundle\Entity\Client::getFilesWebDir($this->getClient());

        return $this;
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
     * saveImports
     */
    protected function saveImports($inputFile)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('ApplicationSonataUserBundle:User')->find($user->getId());

        $this->_imports = new Imports();
        $this->_imports->setUser($users);
        $this->_imports->setDate($this->_import_date);
        $this->_imports->setClientId($this->client_id);
        $this->_imports->setFileName($inputFile['name']);

        $em->persist($this->_imports);
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function importAction()
    {
    	
    	$this->getLockingAccessDenied();
        if (!empty($_FILES) && !empty($_FILES["inputFile"])) {
            $file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
            $tmpFile = $_FILES["inputFile"]["tmp_name"];
            $inputFile = $_FILES['inputFile'];

            if ($this->importFileValidate($inputFile)) {
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
                    $sheets = $objPHPExcel->getAllSheets();

                    $content_arr = array();
                    foreach ($sheets as $sheet) {

                        $title = $sheet->getTitle();
                        $content_arr[$title] = $sheet->toArray();
                    }
                    unset($sheets, $objPHPExcel, $objReader);

                    file_put_contents($tmpFile, '');
                    $this->importValidateAndSave($content_arr);

                    if (empty($this->_import_counts['rows']['errors'])) {

                        $this->saveImports($inputFile);
                        $this->importValidateAndSave($content_arr, true);
                    }
                }
            }
        }

        $messages = $this->getCountMessageImports();
        if (!empty($messages)) {
            $this->get('session')->setFlash('sonata_flash_info|raw', implode("<br/>", $messages));
        } else {
        	
        	$message = trim($this->get('session')->getFlash('sonata_flash_info|raw'));
        	if($message == '') {
        		$this->get('session')->setFlash('sonata_flash_info|raw', $this->admin->trans('Imported : %count%', array('%count%' => 0)));
        	} else {
        		$this->get('session')->setFlash('sonata_flash_info|raw', $message);
        	}
        }

        return $this->render(':redirects:back.html.twig');
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
    	
    	$entities = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientOperationsBundle:' . $class)
    		->findBy(array('client_id' => $this->client_id, 'mois' =>  new \DateTime("{$this->_year}-{$this->_month}-01")));
    	
    	
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
                $admin = $this->container->get('sonata.admin.pool')->getAdminByAdminCode($adminCode);
                $admin->setDeviseList($this->getDeviseList());
                $admin->setValidateImport();
                
                foreach ($data as $key => $line) {
                    if ($this->getImportsBreak($data, $key) || ( empty($line[0]) && empty($line[1]) && empty($line[2]) && empty($line[3]) ) ) {
                        break;
                    }
                    $object = $admin->getNewInstance();
                    $admin->setSubject($object);
                    $admin->setIndexImport($key + 1);
                    
                    $admin->import_file_year = $this->_year;
                    $admin->import_file_month = $this->_month;
                    

                    /* @var $form \Symfony\Component\Form\Form */
                    $form_builder = $admin->getFormBuilder();
                    $form = $form_builder->getForm();

                    
                    if($object instanceof ListDevises) {
                    	continue;
                    }
                    
                    $form->setData($object);
                    $formData = array('client_id' => $this->client_id, '_token' => $this->get('form.csrf_provider')->generateCsrfToken('unknown'));

                    foreach ($line as $index => $value) {
                        if (isset($fields[$index])) {
                            $fieldName = $fields[$index];
                            $newValue = $admin->getFormValue($fieldName, $value);
                            $formData[$fieldName] = $newValue;
                        }
                    }
                    
                    
                    if(isset($formData['mois']) && $mois = $formData['mois']) {
                    	
                    	list($current_year, $current_month) = explode('-', date('Y-m', strtotime('now' . (date('d') < 25 ? ' -1 month' : ''))));
                    	
                    	if ($mois) {
                    		if ($mois instanceof \DateTime) {
                    			$month = $value->format('n');
                    			$year = $value->format('Y');
                    		} else {
                    			$month = $mois['month'];
                    			$year = $mois['year'];
                    		}
                    	
                    		if (!$this->admin->getLocking() && !($year == $current_year && $month == $current_month)) {
                    			continue;
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
     * @param $file
     * @return bool
     */
    protected function importFileValidate($file)
    {
        $file_name = $file['name'];
        $validate = false;

        list($y, $m) = explode('-', date('Y-m', strtotime('now' . (date('d') < 25 ? ' -1 month' : ''))));

        /**
         *  example file_name
         *  $file_name = 'FUJITSU-Importation-TVA-2012-10-01.xlsx';
         */

        $validate_fields = array();
        if (preg_match('/(.*)\-(TVA|DEB)\.xlsx/i', $file_name, $matches)) {

            array_shift($matches);
            list($nom_client) = $matches;
            
            
            $this->_import_date = new \DateTime($this->_year . '-' . $this->_month . '-' . date('d H:i:s'));

            $client = $this->getImportFileValidateNomClient($nom_client);

            if (!empty($client)) {

            	if ($this->client_id != $client->getId()) {
            		$validate_fields[] = 'nom_client';
            	}


            	if($this->getImportFileValidateExist($client, $y, $m)) {
            		$this->get('session')->setFlash('sonata_flash_info|raw', 'Vous ne pouvez pas uploader le fichier pour le mois courant');
            		return false;
            	}

            	$dateDebut = $client->getDateDebutMission();


            	if($dateDebut->format('Ym') > $y . $m) {
            		$this->get('session')->setFlash('sonata_flash_info|raw', 'Le fichier de données est antérieur à la date de début de mission');
            		return false;
            	}


            } else {
            	$validate_fields[] = 'nom_client';
            }

            if (count($validate_fields) > 0) {
            	$this->getImportFileValidateMessage();
            	$validate = false;
            } else {
            	$validate = true;
            }

        } else {

            $this->getImportFileValidateMessage();
        }

        return $validate;
    }

    protected function getImportFileValidateMessage()
    {
        $data = array(
            '%nom_client%' => strtoupper($this->client),
        );

        $filename = '<strong>' . strtr("%nom_client%-TVA.xlsx", $data) . '</strong>';

        $this->get('session')->setFlash('sonata_flash_info|raw', $this->admin->trans('Nom de fichier invalide: Format requis %filename%', array('%filename%' => $filename)));
    }


    /**
     * @param $nom_client
     * @return mixed|null
     */
    protected function getImportFileValidateNomClient($nom_client)
    {
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();

        $client = $qb->select('c')
            ->from('Application\Sonata\ClientBundle\Entity\Client', 'c')
            ->where('UPPER(c.nom) = UPPER(:nom)')
            ->setParameter(':nom', $nom_client)
            ->getQuery()
            ->getResult();

        if (!empty($client)) {
            return array_shift($client);
        }
        return null;
    }

    /**
     * @param $client
     * @param $year
     * @param $month
     * @return int
     */
    protected function getImportFileValidateExist($client, $year, $month)
    {
        /*
        * example source : http://www.simukti.net/blog/2012/04/05/how-to-select-year-month-day-in-doctrine2/
        */

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $emConfig = $em->getConfiguration();
        $emConfig->addCustomStringFunction('YEAR', 'Application\Sonata\ClientOperationsBundle\DQL\YearFunction');
        $emConfig->addCustomDatetimeFunction('MONTH', 'Application\Sonata\ClientOperationsBundle\DQL\MonthFunction');

        $dql = "SELECT i.id AS counts FROM ApplicationSonataClientOperationsBundle:Imports i
                        WHERE i.client_id = :client_id
                        AND YEAR(i.date) = :year
                        AND MONTH(i.date) = :month
        				AND i.is_deleted = 0
        		";

        $sql = $em->createQuery($dql);
        $sql->setParameter(':client_id', $client->getId());
        $sql->setParameter(':year', $year);
        $sql->setParameter(':month', $month);

        $result = $sql->getArrayResult();

        if(!empty($result)) {
        	return true;
        }
        return false;
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
    public function getErrorsAsString($class, $form, $line, $level = 0, $field = '', $message = null)
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
     * @return array
     */
    public function getDeviseList()
    {
        if (is_null($this->devise)) {
            $objects = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:ListDevises')->findAll();
            foreach ($objects as $object) {
                $this->devise[strtolower($object->getAlias())] = $object;
            }
        }

        return $this->devise;
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
	                        $error_log_filename = '/data/imports/import-error-log-' . md5(time() . rand(1, 99999999)) . '.txt';
	
	                        $render_view_popup = $this->renderView('ApplicationSonataClientOperationsBundle:popup:popup_message.html.twig', array(
	                            'error_reports' => $this->_import_reports,
	                            'active_tab' => $this->_tabAlias,
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

    /**
     * blankAction
     */
    public function blankAction()
    {
        $file_name = 'blank-' . md5(time() . rand(1, 99999999));

        $response = new Response();

        $content = file_get_contents('bundles/applicationsonataclientoperations/excel/blank.xlsx');
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/excel');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $file_name . '.xlsx"');

        return $response;
    }

    /**
     *  exportExcelAction
     */
    public function exportExcelAction()
    {
        $excel = $this->get('client.operation.excel');
        $excel->set('_client', $this->client);
        $excel->set('_config_excel', $this->_config_excel);
        $excel->set('_locking', $this->getLocking());
        $excel->set('_admin', $this->admin);
        $excel->render();

        if ($this->getLocking()) {

            $this->client->moveFile($excel->getFileAbsolute(), $excel->getFileNameExt());

            $this->get('session')->setFlash('sonata_flash_success', $this->admin->trans('File %name% was successfully saved at Client directory', array('%name%' => $excel)));
            return new RedirectResponse($this->admin->generateUrl('list'));
        }
        exit;
    }

    
    public function exportTransDebAction() {
    	
    	$transdeb = $this->get('client.operation.transdeb');
    	$transdeb->set('_client', $this->client);
    	
    	$transdeb->set('_admin', $this->admin);
    	$transdeb->render();
    	$transdeb->download();
    	
    	exit;
    }
    
    
    /**
     * @param $client_id
     * @param $month
     * @param $year
     * @param int $blocked
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function lockingAction($client_id, $month, $year, $blocked = 1)
    {
        list($_month, $_year) = $this->admin->getQueryMonth($month);

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $_month, 'year' => $_year));

        if ($locking) {
            $status_id = 2;
        }

        if ($blocked) {
            $status_id = 1;
        }

        if($status_id == 1 && !$this->acceptLocking($client_id, $month)) {
        	$this->get('session')->setFlash('sonata_flash_error', 'Cloture Mois-TVA ' . $_year . '-' . $_month . ' impossible car au moins une opération n\'a pas été prise en compte sur une des Ca3 précédente dans : ' . $this->_lockingTab . ' - ' . $this->datefmtFormatFilter($this->_lockingDate, 'YYYY MMMM'));
        	return $this->redirect($this->generateUrl('admin_sonata_clientoperations_' . $this->_tabAlias . '_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
        }
        
        
        if($status_id == 2 && !$this->acceptUnlocking($client_id, $month)) {
        	$this->get('session')->setFlash('sonata_flash_error', 'Le mois ' . $this->_unlockingYear . '-' . $this->_unlockingMonth . ' est déjà vérouillé, vous ne pouvez donc pas dévérouillé le mois sélectionné.');
        	return $this->redirect($this->generateUrl('admin_sonata_clientoperations_' . $this->_tabAlias . '_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
        }
        
        
        if($locking) {
        	$em->remove($locking);
        	$em->flush();
        }
        
        if ($blocked) {
        	$locking = new Locking();
        	$locking->setClientId($client_id);
        	$locking->setMonth($_month);
        	$locking->setYear($_year);
        	$em->persist($locking);
        	$em->flush();
        }
        
        $status = $em->getRepository('ApplicationSonataClientOperationsBundle:ListStatuses')->find($status_id);

        if ($status) {
            foreach ($this->_config_excel as $table => $params) {
                $objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
                    ->createQueryBuilder('o')
                    ->where('o.mois BETWEEN :from_date AND :to_date')
                    ->andWhere('o.client_id = :client_id')
                    
                    ->setParameter(':from_date', $_year . '-' . $_month . '-01')
                    ->setParameter(':to_date', $_year . '-' . $_month . '-31')
                    ->setParameter(':client_id', $client_id)
                    ->getQuery()->getResult();
                foreach ($objects as $obj) {
                    /** @var $obj \Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity */
                    $obj->setStatus($status);
                    $em->persist($obj);
                    $em->flush();
                }
                unset($objects);
            }
        }

        return $this->redirect($this->generateUrl('admin_sonata_clientoperations_' . $this->_tabAlias . '_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
    }

    
    
    protected function acceptLocking($client_id, $month) {
    	list($_month, $_year) = $this->admin->getQueryMonth($month);
    	
    	$lastMonth = new \DateTime("$_year-$_month-01 -1 month");
    	$hasRecordLastMonth = false;
    	
    	$_year = $lastMonth->format('Y');
    	$_month = $lastMonth->format('m');
    	//var_dump($lastMonth->format('Y m'));
    	 
    	$em = $this->getDoctrine()->getManager();
    	
    	foreach ($this->_config_excel as $table => $params) {
    		$objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
    		->createQueryBuilder('o')
    		->where('o.mois BETWEEN :from_date AND :to_date')
    		->andWhere('o.client_id = :client_id')
    		->orderBy('o.mois', 'DESC')
    		->setParameter(':from_date', $_year . '-' . $_month . '-01')
    		->setParameter(':to_date', $_year . '-' . $_month . '-31')
    		->setParameter(':client_id', $client_id)
    		->getQuery()->getResult();
    		foreach ($objects as $obj) {
    			$hasRecordLastMonth = true;
    			break;
    		}
    		if($hasRecordLastMonth) {
    			//$this->_lockingMonth = $obj->getMois()->format('m');
    			//$this->_lockingYear = $obj->getMois()->format('Y');
    			$this->_lockingTab = $params['name'];
    			$this->_lockingDate = $obj->getMois();
    			break;
    		}
    		unset($objects);
    	}
    	
    	$lastMonthLocking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $_month, 'year' => $_year));
    	
    	// Last month must be locked first
    	if($hasRecordLastMonth && !$lastMonthLocking) {
    		return false;
    	}
    	
    	return true;
    }
    
    
    
    protected function acceptUnlocking($client_id, $month) {
    	list($_month, $_year) = $this->admin->getQueryMonth($month);
    	
    	
    	$hasRecordLatestMonth = false;
    	
    	$em = $this->getDoctrine()->getManager();
    	
    	//var_dump($_year . '-' . $_month . '-01', $client_id);
    	
    	
    	$emConfig = $em->getConfiguration();
    	$emConfig->addCustomStringFunction('DATE_FORMAT', 'Application\Sonata\ClientOperationsBundle\DQL\DateFormatFunction');
    	 
    	foreach ($this->_config_excel as $table => $params) {
    		$objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
    		->createQueryBuilder('o')
	    	->where("DATE_FORMAT(o.mois, '%Y-%m') > :date")
	    	->andWhere('o.client_id = :client_id')
	    	->andWhere('o.status = 1') //verrouillé
	    	->orderBy('o.mois', 'ASC')
	    	->setParameter(':date', $_year . '-' . str_pad($_month, 2, 0, STR_PAD_LEFT))
	    	->setParameter(':client_id', $client_id)
	    	->getQuery()
    		
	    	//->getSQL();exit($objects);
    		->getResult();
    		
    		foreach ($objects as $obj) {
    			$hasRecordLatestMonth = true;
    			break;
    		}
    		if($hasRecordLatestMonth) {
    			
    			$this->_unlockingMonth = $obj->getMois()->format('m');
    			$this->_unlockingYear = $obj->getMois()->format('Y');
    			
    			break;
    		}
    		unset($objects);
    	
    	}
    	
    	if($hasRecordLatestMonth) {
    		return false;
    	}
    	 
    	return true;
    	
    	
    	
    }
    
    
    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importRemoveAction($id)
    {
        $id = (int)$id;
        if ($id) {
            /* @var $em \Doctrine\ORM\EntityManager */
            $em = $this->getDoctrine()->getManager();
            foreach ($this->_config_excel as $table => $params) {
                $object = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])->findByImports($id);
                foreach ($object as $obj) {
                    $em->remove($obj);
                }
                unset($object);
            }
            $em->flush();

            /** @var $import Imports */
            $import = $em->getRepository('ApplicationSonataClientOperationsBundle:Imports')->find($id);
            $import->setIsDeleted(true);
            $em->persist($import);
            unset($import);
            $em->flush();
        }
        return $this->render(':redirects:back.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function importListAction()
    {
        $form_month = $this->_year . '-' . $this->_month . '-01';
        $to_month = $this->_year . '-' . $this->_month . '-31';

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $iDB = $em->getRepository('ApplicationSonataClientOperationsBundle:Imports')->createQueryBuilder('i');

        /* @var $lastImport Imports */
        $lastImports = $iDB->select('i, u')
            ->leftJoin('i.user', 'u')
            ->andWhere('i.date BETWEEN :form_month AND :to_month')
            ->andWhere('i.client_id = :client_id')
            ->andWhere('i.is_deleted = :is_deleted')
            ->addOrderBy('i.date', 'DESC')
            ->setParameter(':client_id', $this->client_id)
            ->setParameter(':is_deleted', false)
            ->setParameter(':form_month', $form_month)
            ->setParameter(':to_month', $to_month)
            ->getQuery()
        //->getSQL();exit($lastImports);
           ->getResult();

        $lastImports = $lastImports ? : array();
        $lastImportsArray = array();
        foreach ($lastImports as $import) {
            /** @var $import \Application\Sonata\ClientOperationsBundle\Entity\Imports */
            $lastImportsArray[] = array(
                'id' => $import->getId(),
                'date' => checkdate($import->getTs()->format('m'), $import->getTs()->format('d'), $import->getTs()->format('Y')) ? $import->getTs(): $import->getDate(),
                'username' => (string)$import->getUser(),
                'filename' => (string)$import->getFileName(),
            );
        }

        return $this->renderJson(array(
            'title' => '<span style="text-transform:none;">' . $this->admin->trans('ApplicationSonataClientOperationsBundle.imports.list_title') . '</span>',
            'imports' => $lastImportsArray
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function declarationAction()
    {
        $client = $this->getClient();
        $this->get('request')->setLocale(strtolower($client->getLanguage()));

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $bank \Application\Sonata\ClientBundle\Entity\Coordonnees */
        $bank = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->findOneBy(array());

        $debug = isset($_GET['d']);
        
        
        $V01TVAlist = $this->getEntityList('V01TVA', false);
        $V03283Ilist = $this->getEntityList('V03283I', false);
        $V05LIClist = $this->getEntityList('V05LIC', false);
        $V07EXlist = $this->getEntityList('V07EX', false);
        $V11INTlist = $this->getEntityList('V11INT', false);
        
        $A02TVAlist = $this->getEntityList('A02TVA', false);
        $A04283Ilist = $this->getEntityList('A04283I', false);
        $A06AIBlist = $this->getEntityList('A06AIB', false);
        $A08IMlist = $this->getEntityList('A08IM', false);
        $A10CAFlist = $this->getEntityList('A10CAF', false);
        
        $A02TVAPrevlist = $this->getEntityList('A02TVA', true); // Previous month
        $A08IMPrevlist = $this->getEntityList('A08IM', true); // Previous month
        
        $totalLines = count($V01TVAlist) + count($V03283Ilist) + count($V05LIClist) + count($V07EXlist)
        	+ count($A02TVAlist) + count($A04283Ilist) + count($A06AIBlist);
        
        
        if($totalLines > 3) {
        	$V01TVAlist = $this->getEntityList('V01TVA', false, true);
        	$V03283Ilist = $this->getEntityList('V03283I', false, true);
        	$V05LIClist = $this->getEntityList('V05LIC', false, true);
        	$V07EXlist = $this->getEntityList('V07EX', false, true);
        	$V11INTlist = $this->getEntityList('V11INT', false, true);
        	
        	$A02TVAlist = $this->getEntityList('A02TVA', false, true);
        	$A04283Ilist = $this->getEntityList('A04283I', false, true);
        	$A06AIBlist = $this->getEntityList('A06AIB', false, true);
        	$A08IMlist = $this->getEntityList('A08IM', false, true);
        	$A10CAFlist = $this->getEntityList('A10CAF', false, true);
        	
        	$A02TVAPrevlist = $this->getEntityList('A02TVA', true, true, 'date_piece'); // Previous month
        	$A08IMPrevlist = $this->getEntityList('A08IM', true, true, 'date_piece'); // Previous month
        }
        
        
        
        
        
        $A04283ISumPrev = $this->_sumData($this->getEntityList('A04283I', true, false, 'date_piece'));
        $A06AIBSumPrev = $this->_sumData($this->getEntityList('A06AIB', true, false, 'date_piece'));
        
        
        $rulingNettTotal = 0;
        $rulingVatTotal = 0;
        
        
        if($A04283ISumPrev) {
        	$rulingNettTotal += $A04283ISumPrev->getHT();
        	$rulingVatTotal += $A04283ISumPrev->getTVA();
        }
        
        if($A06AIBSumPrev) {
        	$rulingNettTotal += $A06AIBSumPrev->getHT();
        	$rulingVatTotal += $A06AIBSumPrev->getTVA();
        }
        
        
        
        
        //var_dump($A04283ISumPrev);
        
        
        
        $page = $this->render('ApplicationSonataClientOperationsBundle::declaration.html.twig', array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1),
            'debug' => $debug,
            'client' => $client,
            'bank' => $bank,
        		
        	'V01TVAlist' => $V01TVAlist,
        	'V07EXlist' => $V07EXlist,
        	'V05LIClist' => $V05LIClist,
        	'V03283Ilist' => $V03283Ilist,
        	'V11INTlist' => $V11INTlist,	
        			
        		
        	'A02TVAlist' => $A02TVAlist,
        	'A08IMlist' => $A08IMlist,
        	'A02TVAPrevlist' => $A02TVAPrevlist,
        	'A08IMPrevlist' => $A08IMPrevlist,
        	'A06AIBlist' => $A06AIBlist,
        	'A04283Ilist' => $A04283Ilist,
        	'A10CAFlist' => $A10CAFlist,	
        		
        	'A04283ISumPrev' => $A04283ISumPrev,	
			'A06AIBSumPrev' => $A06AIBSumPrev,
			'RulingNettTotal' => $rulingNettTotal,
			'RulingVatTotal' => $rulingVatTotal,
        		
        	'locked' => $this->getLocking()
        ));


        if (!$debug) {
            $mpdf = new mPDF('c', 'A4', 0, '', 15, 15, 13, 13, 9, 2);
            //$mpdf->SetDisplayMode('fullpage');
            //$mpdf->SetHeader('test header', 'E');
            
            $mpdf->WriteHTML($page->getContent());
            $mpdf->Output();

            exit;
        }

        return $page;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attestationAction()
    {
        $debug = isset($_GET['d']);
        $page = $this->render('ApplicationSonataClientOperationsBundle::attestation.html.twig', array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1),
            	'debug' => $debug,
        ));


        if (!$debug) {
            $mpdf = new mPDF('c', 'A4', 0, '', 5, 10, 13, 13, 9, 2);
            //$mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($page->getContent());
            $mpdf->Output();

            exit;
        }

        return $page;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function RDevisesAction()
    {
        $devise_id = $this->getRequest()->request->get('devise');
        $date_piece = $this->getRequest()->request->get('date');

        if (!$devise_id && !$date_piece) {
            throw new NotFoundHttpException('Must be devise and date_piece');
        }

        $value = Devises::getDevisesValue($devise_id, \DateTime::createFromFormat('d/m/Y', $date_piece));

        return $this->renderJson(array(
            'value' => $value,
        ));
    }

    /**
     * @param string $view
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if ($parameters && isset($parameters['action'])) {

            switch ($parameters['action']) {
                case 'list':
                case 'edit':
                case 'create':
                    if (!($this->get('request')->getMethod() == 'POST' && $this->get('request')->request->get('action') == 'delete')) {
                        $parameters['base_template'] = 'ApplicationSonataClientOperationsBundle::ajax_layout.html.twig';
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
    
    
    
    protected function getEntityList($entity, $isPrevMonth = false, $mergeData = false, $monthField = 'mois')
    {
    	static $results = array();
    	$key = $entity . $isPrevMonth . $monthField;
    	
    	if(!isset($results[$key])) {
	    	/* @var $em \Doctrine\ORM\EntityManager */
	    	$em = $this->getDoctrine()->getManager();
	    	$qb = $em->createQueryBuilder();
	    	$q = $qb->select('v')->from("Application\Sonata\ClientOperationsBundle\Entity\\". $entity, 'v');
	    	$qb = $this->_listQueryFilter($qb, $isPrevMonth, $monthField);
	    	
	    	if($entity == 'V05LIC') {
	    		$qb->andWhere('(' . $qb->getRootAlias() . '.regime IN (21, 25, 26) OR ' . $qb->getRootAlias() . '.regime IS NULL)');
	    	} elseif($entity == 'A06AIB') {
	    		$qb->andWhere('(' . $qb->getRootAlias() . '.regime IN (11) OR ' . $qb->getRootAlias() . '.regime IS NULL)');
	    	}
	    	$results[$key] = $q->getQuery()->getResult();
    	}	
    	
    	
    	if (!empty($results[$key])) {
    		if($mergeData) {
    			return $this->_mergeData($results[$key]);
    		} else{
    			return $results[$key];
    		}
    	}
    	return null;
    }
    
    
    /**
     * Merge entities based on percentage
     * 
     * @param unknown $entities
     * @return array
     */
    private function _mergeData($entities) {
    	$dataSet = array();
    	$hts = array();
    	$tvas = array();
    	$rawEntities = array();
    	
    	foreach($entities as $entity) {
    		
    		$key = method_exists($entity, 'getTauxDeTVA') ? $entity->getTauxDeTVA() : 0;
    		
    		if(method_exists($entity, 'getHT')) {

    			if($entity->getHT() < 0) {
    				$key++;
    			}
    			
    			$key = base64_encode($key);
    			$hts[$key][] = $entity->getHT();
    		}
    		
    			
    		if(method_exists($entity, 'getTVA')) {
    			
    			$key = base64_encode($key);
    			$tvas[$key][] = $entity->getTVA();
    		}
    			
    		/* var_dump($entity->getHT());
    		echo '<br />'; */
    		
    		$rawEntities[$key] = $entity;
    	}
    	
    	foreach($rawEntities as $k => $entity) {
    		$ht = 0;
    		$tva = 0;
    		if(isset($hts[$k])) {
	    		foreach ($hts[$k] as $v) {
	    			$ht+=$v;
	    		}
    		}
    		
    		if(isset($tvas[$k])) {
	    		foreach ($tvas[$k] as $v) {
	    			$tva+=$v;
	    		}
    		}
    		
    		if(method_exists($entity, 'setTVA')) {
    			$entity->setTVA($tva);
    		}
    		if(method_exists($entity, 'setHT')) {
    			$entity->setHT($ht);
    		}
    		
    		$dataSet[] = $entity;
    	}
    	
    	return $dataSet;
    	
    }

	/**
	 * Sets total values for Nett = HT (€) and VAT = TVA (€)
	 * 
	 * @param unknown $entities
	 * @return NULL|unknown
	 */    
    private function _sumData($entities) {
    	$ht = 0;
    	$tva = 0;
    	 
    	if(empty($entities)) {
    		return null;
    	}
    	
    	foreach($entities as $entity) {
    		if(method_exists($entity, 'getHT')) {
    			$ht += $entity->getHT();
    		}
    
    		 
    		if(method_exists($entity, 'getTVA')) {
    			$tva += $entity->getTVA();
    		}
    		 
    	}
    	 
    	if(method_exists($entity, 'setTVA')) {
    		$entity->setTVA($tva);
    	}
    	if(method_exists($entity, 'setHT')) {
    		$entity->setHT($ht);
    	}
    	 
    	return $entity;
    	 
    }
    
    
    
    
    private function _listQueryFilter(\Doctrine\ORM\QueryBuilder $qb, $isPrevMonth = false, $monthField = 'mois') {
    	if (!$this->_show_all_operations){
    	
    		$form_month = $this->_year . '-' . $this->_month . '-01';
    		$to_month = $this->_year . '-' . $this->_month . '-31';
    		if($isPrevMonth) {
    			$lastMonth = new \DateTime($form_month);
    			$lastMonth->sub(\DateInterval::createFromDateString('1 month'));
    			
    			$to_month = $lastMonth->format('Y-m') . '-31';
    			$form_month = $lastMonth->format('Y-m') . '-01';
    		}

    		
    	
    		if ($this->_query_month == -1) {
    			$qb->orWhere($qb->getRootAlias() . '.'.$monthField.' IS NULL');
    			$qb->orWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
    		} else {
    			$qb->andWhere($qb->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
    		}
    		 
    		$qb->setParameter(':form_month', $form_month);
    		$qb->setParameter(':to_month', $to_month);
    		 
    	}
    	 
    	$qb->andWhere($qb->getRootAlias() . '.client_id=' . $this->client_id)
    		//->orderBy($qb->getRootAlias() .'.TVA')
    		;
    	
    	return $qb;
    }
    
    
}
