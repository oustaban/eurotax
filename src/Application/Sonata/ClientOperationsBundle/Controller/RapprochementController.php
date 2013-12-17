<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Form;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Application\Sonata\ClientOperationsBundle\Entity\Locking;
use Application\Sonata\ClientOperationsBundle\Entity\Rapprochement;
use Application\Sonata\ClientOperationsBundle\Entity\RapprochementState;
use Application\Sonata\ClientOperationsBundle\Form\RapprochementForm;

/**
 * Rapprochement controller.
 *
 */
class RapprochementController extends Controller
{
	protected $_query_month = '',
    	$_month = '',
	    $_year = '',
	    $_client = null,
	    $_client_id = null,
	    $_blocked = 1,
	    $_locking = false,
	    
	    $_lockingTab, 
	    $_lockingDate, 
	    $_lockingMonth, 
	    $_lockingYear,
	    $_unlockingMonth, 
		$_unlockingYear;
    
    
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
    
    protected function validateParams($client_id, $month) {
    	/** @var $em \Doctrine\ORM\EntityManager */
    	$em = $this->getDoctrine()->getManager();
    	$client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($client_id);
    	
    	if (empty($client)) {
    		throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $client_id));
    	}
    	
    	$this->_client = $client;
        $this->_client_id = $client->getId();
        $this->_query_month = $month;
        
        list($this->_month, $this->_year) = $this->getQueryMonth($month);
        
        $this->_blocked = $this->getLocking() ? 0 : 1;
    }
    
    public function configure() {
    	parent::configure();
    	
    }
    
    /**
     * @Template()
     */
    public function indexAction($client_id, $month) {

    	$this->validateParams($client_id, $month);

        $month_default = '-1' . date('|Y', strtotime('-1 month'));
        $query_month = $month ? $month : $month_default;
        
        if ($query_month == 'all'){
        	$query_month = -1;
        }
        
        $fromImport = isset($_GET['fromImport']);
        $hasImportDataOnly = true;
        
        
        //array('isDEB' => false, 'groupResults' => true, 'importDataOnly' => false)
        $a06_aib = $this->_getTableData('A06AIB', array('isDEB' => true));
        $deb_intro = $this->_getTableData('DEBIntro');
        
        if( ($this->_noImportIdFromTableData($this->_getTableData('A06AIB', array('isDEB' => true, 'groupResults' => false))) ||
        	 $this->_noImportIdFromTableData($this->_getTableData('DEBIntro', array('isDEB' => false, 'groupResults' => false))) ||
        	 $this->_noImportIdFromTableData($this->_getTableData('V05LIC', array('isDEB' => true, 'groupResults' => false, 'importDataOnly' => false))) ||
        	 $this->_noImportIdFromTableData($this->_getTableData('DEBExped', array('isDEB' => false, 'groupResults' => false, 'importDataOnly' => false)))) && $fromImport ) {
        	
        	$hasImportDataOnly = false;
        }
        
        $v05_lic = $this->_getTableData('V05LIC', array('isDEB' => true, 'groupResults' => true, 'importDataOnly' => $fromImport ? true : false));
        $deb_exped = $this->_getTableData('DEBExped', array('isDEB' => false, 'groupResults' => true, 'importDataOnly' => $fromImport ? true : false));
        $form = $this->form();
        
        return array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1,
            	'blocked' => $this->_blocked,
            	'query_month' => $query_month
            ),
            'client' => $this->_client,
            'a06_aib' => $a06_aib,
            'v05_lic' => $v05_lic,
            'deb_intro' => $deb_intro,
            'deb_exped' => $deb_exped,
        	'form' => 	$form instanceof Form ? $form->createView() : false,
        	'fromImport' => $fromImport,
        	'hasImportDataOnly' => $hasImportDataOnly,
       		'declarationLink' => $this->generateUrl('rapprochement_frame', array('client_id' =>  $this->_client_id, 'month' => $this->_query_month)),
        	'listLink' => $this->generateUrl('admin_sonata_clientoperations_v01tva_list',
        				array('filter' => array('client_id' => array('value' => $this->_client_id)), 'month' => $this->_query_month)),
        );
    }

    
    private function form() {
    	$form = $this->get('form.factory')->create(new RapprochementForm($this->_blocked));
    	$request = $this->get('request');
    
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		if ($form->isValid()) {
    			//$moisDate = explode('|', $this->_query_month);
    			$rap = new Rapprochement();
    			$em = $this->get('doctrine')->getEntityManager();
    			$mois = new \DateTime();
    			$mois->setDate($this->_year, $this->_month, '01');
    			
    			$rap->setIntroInfoId((int)$form['intro_info_id']->getData());
    			$rap->setIntroInfoNumber((double)$form['intro_info_number']->getData());
    			$rap->setIntroInfoNumber2((double)$form['intro_info_number2']->getData());
    			$rap->setIntroInfoText($form['intro_info_text']->getData());
    			
    			$rap->setExpedInfoId((int)$form['exped_info_id']->getData());
    			$rap->setExpedInfoNumber((double)$form['exped_info_number']->getData());
    			$rap->setExpedInfoNumber2((double)$form['exped_info_number2']->getData());
    			$rap->setExpedInfoText($form['exped_info_text']->getData());
    			
    			$rap->setClientId((int)$this->_client_id);
    			$rap->setMois($mois);
    			$rap->setDate(new \DateTime());
    			
    			$em->persist($rap);
    			$em->flush(); 
    			//return $this->render(':redirects:back.html.twig');
    			//return $this->redirect($this->generateUrl('rapprochement_index', array('client_id' => $client_id, 'month' => $month), true));
    			
    			if(isset($_POST['btn_locking'])) {
    				if($this->executeLocking()) {
    					header('Location: ' . $this->generateUrl('rapprochement_frame', array('client_id' =>  $this->_client_id, 'month' => $this->_query_month)));
	    			} else {
	    				header('Location: ' . $this->generateUrl('rapprochement_index', array('client_id' =>  $this->_client_id, 'month' => $this->_query_month)));	    				
	    			}
	    			exit;
    			}
    				
    			header('Location: ' . $this->generateUrl('admin_sonata_clientoperations_v01tva_list', array('filter' => array('client_id' => array('value' => $this->_client_id)), 'month' => $this->_query_month)));
	    		exit;
    		}
    	}
    	return $form;
    }
    
    
    public function executeLocking() {
    	$success = true;
    	$em = $this->get('doctrine')->getEntityManager();
    	$locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    	
    	if ($locking) {
    		$status_id = 2;
    	}
    	
    	if ($this->_blocked) {
    		$status_id = 1;
    	}
    	
    	if($status_id ==1 && !$this->acceptLocking()) {
    		$success = false;
    		$this->get('session')->setFlash('sonata_flash_error', 'Cloture Mois-TVA ' . $this->_year . '-' . $this->_month . ' impossible car au moins une opération n\'a pas été prise en compte sur une des Ca3 précédente dans : ' . $this->_lockingTab . ' - ' . $this->datefmtFormatFilter($this->_lockingDate, 'YYYY MMMM'));
    	} elseif($status_id == 2 && !$this->acceptUnlocking()) {
    		$success = false;
    		$this->get('session')->setFlash('sonata_flash_error', 'Le mois ' . $this->_unlockingYear . '-' . $this->_unlockingMonth . ' est déjà vérouillé, vous ne pouvez donc pas dévérouillé le mois sélectionné.');
    	} else {
    		$this->setLocking();
    		$this->exportTransDeb();
    	}
    	return $success;
    }
    
    
    public function setLocking() {
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $this->getDoctrine()->getManager();
    
    	$locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    
    	if ($locking) {
    		$em->remove($locking);
    		$em->flush();
    		$status_id = 2;
    	}
    
    	if ($this->_blocked) {
    		$locking = new Locking();
    		$locking->setClientId($this->_client_id);
    		$locking->setMonth($this->_month);
    		$locking->setYear($this->_year);
    		$em->persist($locking);
    		$em->flush();
    		$status_id = 1;
    	}
    	$status = $em->getRepository('ApplicationSonataClientOperationsBundle:ListStatuses')->find($status_id);
    
    	if ($status) {
    		foreach ($this->_config_excel as $table => $params) {
    			$objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
    			->createQueryBuilder('o')
    			->where('o.mois BETWEEN :from_date AND :to_date')
    			->andWhere('o.client_id = :client_id')
    			->setParameter(':from_date', $this->_year . '-' . $this->_month . '-01')
    			->setParameter(':to_date', $this->_year . '-' . $this->_month . '-31')
    			->setParameter(':client_id', $this->_client_id)
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
    }
    
    
    protected function exportTransDeb() {
    	$transdeb = $this->get('client.operation.transdeb');
    	$transdeb->set('_client', $this->_client);
    	$transdeb->set('_year', $this->_year);
    	$transdeb->set('_month', $this->_month);
    	$transdeb->render();
    	$transdeb->saveFile();
    }
    
    protected function acceptLocking() {
   	
    	$lastMonth = new \DateTime("{$this->_year}-{$this->_month}-01 -1 month");
    	$hasRecordLastMonth = false;
    	
    	$_year = $lastMonth->format('Y');
    	$_month = $lastMonth->format('m');
    	 
    	$em = $this->getDoctrine()->getManager();
    	
    	foreach ($this->_config_excel as $table => $params) {
    		$objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
    		->createQueryBuilder('o')
    		->where('o.mois BETWEEN :from_date AND :to_date')
    		->andWhere('o.client_id = :client_id')
    		->setParameter(':from_date', $_year . '-' . $_month . '-01')
    		->setParameter(':to_date', $_year . '-' . $_month . '-31')
    		->setParameter(':client_id', $this->_client_id)
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
    	
    	$lastMonthLocking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    	
    	// Last month must be locked first
    	if($hasRecordLastMonth && !$lastMonthLocking) {
    		return false;
    	}
    	
    	return true;
    }
    
    
    protected function acceptUnlocking() {
    	$hasRecordLatestMonth = false;
    	$em = $this->getDoctrine()->getManager();
    	 
    	$emConfig = $em->getConfiguration();
    	$emConfig->addCustomStringFunction('DATE_FORMAT', 'Application\Sonata\ClientOperationsBundle\DQL\DateFormatFunction');
    
    	foreach ($this->_config_excel as $table => $params) {
    		$objects = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $params['entity'])
    		->createQueryBuilder('o')
    		->where("DATE_FORMAT(o.mois, '%Y-%m') > :date")
    		->andWhere('o.client_id = :client_id')
    		->andWhere('o.status = 1') //verrouillé
    		->orderBy('o.mois', 'ASC')
    		->setParameter(':date', $this->_year . '-' . str_pad($this->_month, 2, 0, STR_PAD_LEFT))
    		->setParameter(':client_id', $this->_client_id)
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
     * @return mixed
     */
    public function getLocking() {
    	if ($this->_locking === false) {
    		$this->_locking = $this->getDoctrine()->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    		$this->_locking = $this->_locking ? : 0;
    	}
    
    	return $this->_locking;
    }
    
    public function getQueryMonth($query_month) {
    	if ($query_month == -1) {
    		$month = date('n|Y', (date('d') < 25 ? strtotime('-1 month') : time()) );
    	}
    	else {
    		$month = $query_month;
    	}
    
    	return explode('|', $month);
    }
    
    protected function _noImportIdFromTableData($rows) {
    	$found = false;
    	foreach($rows as $row) {
    		if( !$row[0]->getImports()  ) {
    			$found = true;
    			break;
    		}
    	}
    	return $found;
    } 
    
    protected function _getTableData($clientOperationName, Array $params = array()) {
    	$params = $params + array('isDEB' => false, 'groupResults' => true, 'importDataOnly' => false);
    	
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $groupSelect = '';

        if ($params['isDEB']) {
        	$deb = '*o.DEB';
        	$v1 = 'montant_HT_en_devise/o.taux_de_change';
        	$v2 = 'HT';
        } else {
        	$deb = '';
        	$v1 = 'valeur_statistique';
        	$v2 = 'valeur_fiscale';
        }
        
        
        if($params['groupResults']) {
        	$groupSelect = ', SUM(o.' . $v1 . ') AS v1, COUNT(o.id) AS nb , SUM(o.' . $v2 . ') AS v2';
        }
        

        $qb = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $clientOperationName)
            ->createQueryBuilder('o')
            ->select('o, o.client_id, o.regime' . $deb . ' AS DEB, o.regime' . $groupSelect)
            ->andWhere('o.mois BETWEEN :form_date_mois AND :to_date_mois')
            ->setParameter(':form_date_mois', $this->_year . '-' . $this->_month . '-01')
            ->setParameter(':to_date_mois', $this->_year . '-' . $this->_month . '-31')
            ->andWhere('o.client_id = :client_id')
            ->setParameter(':client_id', $this->_client_id)
        	;
        
        
        if($params['importDataOnly']) {
        	$qb->andWhere('o.imports IS NOT NULL');
        }
        
        if ($params['isDEB']) {
        	$qb
        	->andWhere('o.DEB = :DEB')
        	->setParameter(':DEB', (int) $params['isDEB']);
        }
        
        if($params['groupResults']) {
        	$qb->groupBy('DEB');
        }
        
        $qb->orderBy('DEB')->orderBy('o.imports', 'ASC');
        
        
        /* var_dump($isDEB, (string)$qb->getQuery()->getSql(), $this->_year . '-' . $this->_month . '-01', $this->_year . '-' . $this->_month . '-31');
        exit; */
        return $qb->getQuery()->execute();
    }

    
    public function datefmtFormatFilter($datetime, $format = null) {
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
    
    public function frameAction($client_id, $month) {
    	$this->validateParams($client_id, $month);
    	
    	$em = $this->get('doctrine')->getEntityManager();
    	$rap = $em->getRepository('ApplicationSonataClientOperationsBundle:RapprochementState')
    		->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    	
    	if(!$rap) {
    		$rap = new RapprochementState();
    	}
    	
    	$request = $this->get('request');
    	
    	if ($request->getMethod() == 'POST') {
    		$rap->setClientId((int) $this->_client_id)
    			->setMonth($this->_month)
    			->setYear($this->_year);
    		
    		if($_POST['type'] == 'ddr') {
    			$rap->setDemandeDeRemboursement((float)$_POST['number']);
    		} elseif($_POST['type'] == 'ctar') {
    			$rap->setCreditTvaAReporter((float)$_POST['number']);
    		}
    		$em->persist($rap);
    		$em->flush();
    		
    		return $this->render(':redirects:back.html.twig');
    	}
    	
    	return $this->render('ApplicationSonataClientOperationsBundle:Rapprochement:frame.html.twig', array(
    		'client_id' => $this->_client_id,
    		'month' => $this->_month,
    		'year' => $this->_year,	
    		'blocked' => $this->_blocked,
			'rapState' => $rap,
    		'declarationLink' => $this->generateUrl('admin_sonata_clientoperations_v01tva_declaration', array('filter' => array('client_id' => array('value' => $this->_client_id)), 'month' => $this->_query_month)),
    		'exporterDebLink' => $this->generateUrl('admin_sonata_clientoperations_v01tva_exportTransDeb', array('filter' => array('client_id' => array('value' => $this->_client_id)), 'month' => $this->_query_month)),
    	));
    }
    
    
    public function lockingAction($client_id, $month) {
    	$this->validateParams($client_id, $month);
    	$this->executeLocking();
    	//return $this->render(':redirects:back.html.twig');
    	
    	return $this->redirect($this->generateUrl('admin_sonata_clientoperations_v01tva_list', array('filter' => array('client_id' => array('value' => $this->_client_id)), 'month' => $this->_query_month)));
    }
    
}
