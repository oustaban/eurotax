<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Application\Sonata\ClientOperationsBundle\Entity\Locking;
use Application\Sonata\ClientOperationsBundle\Entity\RapprochementState;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Sonata\ClientOperationsBundle\Entity\Imports;
use Application\Sonata\ClientOperationsBundle\Entity\ImportNotification;
use Application\Tools\mPDF;
use Application\Sonata\DevisesBundle\Entity\Devises;
use Application\Sonata\ClientOperationsBundle\Helpers\ClientDeclaration;

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
    protected $_jsSettingsJson = null, $_jsSettingsJsonData = array();
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
    
    

    protected $_hasImportErrors = false;
    
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
        
        //$this->get('request')->setLocale(strtolower($this->client->getLanguage()));
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
        	'client_ferme' => $this->clientFerme(),
        ));
    }

    
    protected function clientFerme() {
    	if($this->client->getDateFinMission()) {
    		return true;
    	}
    	return false;
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
     * {@inheritdoc}
     */
    public function importAction()
    {
    	set_time_limit(1200);
    	$this->getLockingAccessDenied();
    	
    	$import_counts = array();
    	$messages = array();
    	
        if (!empty($_FILES) && !empty($_FILES["inputFile"])) {
            $file = TMP_UPLOAD_PATH . '/' . $_FILES["inputFile"]["name"];
            $tmpFile = $_FILES["inputFile"]["tmp_name"];
            $inputFile = $_FILES['inputFile'];

            if ($this->importFileValidate($inputFile)) {
                if (move_uploaded_file($tmpFile, $file)) {
                	
                	
                	$objReader = \PHPExcel_IOFactory::createReaderForFile($file);
		
					if (get_class($objReader) == 'PHPExcel_Reader_CSV') {
						unset($objReader);
						$this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('Fichier non lisible'));
						return $this->render(':redirects:back.html.twig');
					}
                	
                	$_FILES = array();
                	

                	//create fake process id
                	$pid = time();
                	
                	$user = $this->get('security.context')->getToken()->getUser();
                	$kernel = $this->get('kernel');
                	$command = 'php '. $kernel->getRootDir() .'/console clientoperationsbundle:import:excel ' 
                		. $user->getId() . ' ' . $this->client_id . ' application.sonata.admin.v01tva ' 
                		. $file . ' ' . $inputFile['name'] . ' ' . $this->getLocking() . ' ' . $this->_year . ' ' . $this->_month . ' '. $pid . ' --env='. $kernel->getEnvironment() . ' --no-debug ';
                	

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
                				->setClientId($this->client_id);
                			
                			$em->persist($importNotif);
                			$em->flush(); 
                			
                			$this->_hasImportErrors = true;
                			
                			$this->get('session')->setFlash('sonata_flash_error', $this->admin->trans('There are too many data to be processed. We will just notify you once it\'s done. Check your email ('.$user->getEmail().') within few hours.'));
                			
                			break;
                			return $this->render(':redirects:back.html.twig');
                		}
                	}
                	
                	$output = unserialize($process->getOutput());
                	
                	$messages = $output['messages'];
                	$import_counts = $output['import_counts'];
                }
            }
        }

        
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
        
        if( (isset($import_counts['rows']['errors']) && !empty($import_counts['rows']['errors'])) || $this->_hasImportErrors) {
        	return $this->render(':redirects:back.html.twig');
        } else {
        	//return $this->forward('ApplicationSonataClientOperationsBundle:Rapprochement:index', array('client_id' => $this->client_id, 'month' => $this->_query_month));
        	return $this->redirect($this->generateUrl('rapprochement_index', array('client_id' => $this->client_id, 'month' => $this->_query_month, 'fromImport' => 1)));
        	
        }
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
            		$this->_hasImportErrors = true;
            		return false;
            	}

            	$dateDebut = $client->getDateDebutMission();


            	if($dateDebut->format('Ym') > $y . $m) {
            		$this->_hasImportErrors = true;
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
    	
    	$this->_hasImportErrors = true;
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
    	$this->get('request')->setLocale(strtolower($this->client->getLanguage()));
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
    	$transdeb->set('_year', $this->_year);
    	$transdeb->set('_month', $this->_month);
    	
    	
    	$transdeb->render();
    	$transdeb->saveFile();
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
    	$this->getLockingAccessDenied();
    	
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
        
        
        $clientDeclaration = new ClientDeclaration($client);
        $clientDeclaration->setShowAllOperations($this->_show_all_operations)
        	->setYear($this->_year)
        	->setMonth($this->_month);
        	
        
        
        $this->get('request')->setLocale(strtolower($client->getLanguage()));

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        /** @var $bank \Application\Sonata\ClientBundle\Entity\Coordonnees */
        $bank = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->findOneBy(array());

        
        
        $debug = isset($_GET['d']);
        
        
        $page = $this->render('ApplicationSonataClientOperationsBundle::declaration.html.twig', array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1),
            'debug' => $debug,
            'client' => $client,
            'bank' => $bank,
        	'rapState' => $clientDeclaration->getRapprochementState(),
        		
        	'V01TVAlist' => $clientDeclaration->getV01TVAList(),
        	'V07EXlist' => $clientDeclaration->getV07EXList(),
        	'V05LIClist' => $clientDeclaration->getV05LICList(),
        	'V03283Ilist' => $clientDeclaration->getV03283IList(),
        	'V11INTlist' => $clientDeclaration->getV11INTList(),	
        		
        	'A02TVAlist' => $clientDeclaration->getA02TVAList(),
        	'A08IMlist' => $clientDeclaration->getA08IMList(),
        	'A02TVAPrevlist' => $clientDeclaration->getA02TVAPrevList(),
        	'A08IMPrevlist' => $clientDeclaration->getA08IMPrevList(),
        	'A06AIBlist' => $clientDeclaration->getA06AIBList(),
        	'A04283Ilist' => $clientDeclaration->getA04283IList(),
        	'A10CAFlist' => $clientDeclaration->getA10CAFList(),	
        		
        	'A04283ISumPrev' => $clientDeclaration->getA04283ISumPrev(),	
			'A06AIBSumPrev' => $clientDeclaration->getA06AIBSumPrev(),
			'RulingNettTotal' => $clientDeclaration->getRulingNettTotal(),
			'RulingVatTotal' => $clientDeclaration->getRulingVatTotal(),
			
			'Total1' => $clientDeclaration->getTotalVat1(),
			'Total2' => $clientDeclaration->getTotalVat2(),
        	'SoldeTVATotal' => $clientDeclaration->getSoldeTVATotal(),
        	'TotalBalance' => $clientDeclaration->getTotalBalance(),
        	'CreditToBeReportedTotal' => $clientDeclaration->getCreditToBeReportedTotal(),
        		
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
        $this->_jsSettingsJsonData = array_merge($this->_jsSettingsJsonData, $data);
        $this->_jsSettingsJson = json_encode($this->_jsSettingsJsonData);
    	
    }
    
    
    
 
    
    
}
