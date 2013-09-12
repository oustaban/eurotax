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
use Application\Sonata\ClientOperationsBundle\Form\RapprochementForm;

/**
 * Rapprochement controller.
 *
 */
class RapprochementController extends Controller
{
    protected $_month = '';
    protected $_year = '';
    protected $_client_id = null;
    protected $_locking = false;

    protected $_lockingTab, $_lockingDate, $_lockingMonth, $_lockingYear,
    	$_unlockingMonth, $_unlockingYear;
    
    
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
    		'CEE',
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
     * @Template()
     */
    public function indexAction($client_id, $month)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($client_id);

        if (empty($client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $client_id));
        }

        $this->_client_id = $client_id;
        list($this->_month, $this->_year) = $this->getQueryMonth($month);

        
        
        $month_default = '-1' . date('|Y', strtotime('-1 month'));
        
        $query_month = $month ? $month : $month_default;
        
        if ($query_month == 'all'){
        	$query_month = -1;
        }
        
        
        $blocked = $this->getLocking() ? 0 : 1;
        
        $a06_aib = $this->_getTableData('A06AIB', true);
        $v05_lic = $this->_getTableData('V05LIC', true);
        $deb_intro = $this->_getTableData('DEBIntro');
        $deb_exped = $this->_getTableData('DEBExped');
        $form = $this->form($client_id, $month, $blocked);
        
        
        return array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1,
            	'blocked' => $blocked,
            	'query_month' => $query_month
            ),
            'client' => $client,
            'a06_aib' => $a06_aib,
            'v05_lic' => $v05_lic,
            'deb_intro' => $deb_intro,
            'deb_exped' => $deb_exped,
        	'form' => 	$form instanceof Form ? $form->createView() : false
        );
    }

    
    private function form($client_id = null, $month = null, $blocked = 1)
    {
    
    	$form = $this->get('form.factory')->create(new RapprochementForm($blocked));
    	$request = $this->get('request');
    
    	if ($request->getMethod() == 'POST')
    	{
    		$form->bindRequest($request);
    		if ($form->isValid())
    		{
    			
    			$moisDate = explode('|', $month);
    			
    			$rap = new Rapprochement();
    			$em = $this->get('doctrine')->getEntityManager();
    			
    			$mois = new \DateTime();
    			$mois->setDate($moisDate[1], $moisDate[0], '01');
    			
    			
    			$rap->setIntroInfoId((int)$form['intro_info_id']->getData());
    			$rap->setIntroInfoNumber((double)$form['intro_info_number']->getData());
    			$rap->setIntroInfoNumber2((double)$form['intro_info_number2']->getData());
    			$rap->setIntroInfoText($form['intro_info_text']->getData());
    			
    			$rap->setExpedInfoId((int)$form['exped_info_id']->getData());
    			$rap->setExpedInfoNumber((double)$form['exped_info_number']->getData());
    			$rap->setExpedInfoNumber2((double)$form['exped_info_number2']->getData());
    			$rap->setExpedInfoText($form['exped_info_text']->getData());
    			
    			
    			$rap->setClientId((int)$client_id);
    			$rap->setMois($mois);
    			$rap->setDate(new \DateTime());
    			
    			$em->persist($rap);
    			$em->flush(); 
    			//$this->get('session')->setFlash('notice', 'Success');
    			//return $this->render(':redirects:back.html.twig');
    			//return $this->redirect($this->generateUrl('rapprochement_index', array('client_id' => $client_id, 'month' => $month), true));
    			
    			
    			
    			list($_month, $_year) = $this->getQueryMonth($month);
    			$locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $_month, 'year' => $_year));
    			
    			if ($locking) {
    				$status_id = 2;
    			}
    			
    			if ($blocked) {
    				$status_id = 1;
    			}
    			
    			if($status_id ==1 && !$this->acceptLocking($client_id, $month)) {
    				$this->get('session')->setFlash('sonata_flash_error', 'Cloture Mois-TVA ' . $_year . '-' . $_month . ' impossible car au moins une opération n\'a pas été prise en compte sur une des Ca3 précédente dans : ' . $this->_lockingTab . ' - ' . $this->datefmtFormatFilter($this->_lockingDate, 'YYYY MMMM'));
    			} elseif($status_id == 2 && !$this->acceptUnlocking($client_id, $month)) {
    				$this->get('session')->setFlash('sonata_flash_error', 'Le mois ' . $this->_unlockingYear . '-' . $this->_unlockingMonth . ' est déjà vérouillé, vous ne pouvez donc pas dévérouillé le mois sélectionné.');
    			} else {
    				$this->setLocking($client_id, $month, $blocked);
    			}

    				
    			header('Location: ' . $this->generateUrl('admin_sonata_clientoperations_v01tva_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
	    		//return $this->redirect($this->generateUrl('admin_sonata_clientoperations_v01tva_list', array('filter' => array('client_id' => array('value' => $client_id)), 'month' => $month)));
	    		exit;
    			
    			
    		}
    	}
    	
    	return $form;
    
    
    }
    
    
    /**
     * @param $client_id
     * @param $month
     * @param int $blocked
     
     */
    public function setLocking($client_id, $month, $blocked = 1)
    {
    	list($_month, $_year) = $this->getQueryMonth($month);
    	
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $this->getDoctrine()->getManager();
    
    	$locking = $em->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $client_id, 'month' => $_month, 'year' => $_year));
    
    	if ($locking) {
    		$em->remove($locking);
    		$em->flush();
    		$status_id = 2;
    	}
    
    	if ($blocked) {
    		$locking = new Locking();
    		$locking->setClientId($client_id);
    		$locking->setMonth($_month);
    		$locking->setYear($_year);
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
    }
    
    
    protected function acceptLocking($client_id, $month) {
    	list($_month, $_year) = $this->getQueryMonth($month);
    	
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
    	list($_month, $_year) = $this->getQueryMonth($month);
    	 
    	 
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
     * @return mixed
     */
    public function getLocking()
    {
    	
    	
    	if ($this->_locking === false) {
    		$this->_locking = $this->getDoctrine()->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
    		$this->_locking = $this->_locking ? : 0;
    	}
    
    	return $this->_locking;
    }
    
    
   
    public function getQueryMonth($query_month)
    {
    	if ($query_month == -1) {
    		$month = date('n|Y', strtotime('-1 month'));
    	}
    	else {
    		$month = $query_month;
    	}
    
    	return explode('|', $month);
    }
    
    
    protected function _getTableData($clientOperationName, $isDEB = false)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        if ($isDEB) {

        	$deb = '*o.DEB';
        	$v1 = 'montant_HT_en_devise/o.taux_de_change';
        	$v2 = 'HT';
        }
        else {
        	
        	$deb = '';
        	$v1 = 'valeur_statistique';
        	$v2 = 'valeur_fiscale';
        	
        }

        $qb = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $clientOperationName)
            ->createQueryBuilder('o')
            ->select('o.regime' . $deb . ' AS DEB, o.regime, SUM(o.' . $v1 . ') AS v1, COUNT(o.id) AS nb , SUM(o.' . $v2 . ') AS v2')
            ->andWhere('o.mois BETWEEN :form_date_mois AND :to_date_mois')
            ->setParameter(':form_date_mois', $this->_year . '-' . $this->_month . '-01')
            ->setParameter(':to_date_mois', $this->_year . '-' . $this->_month . '-31')
            ->andWhere('o.client_id = :client_id')
            ->setParameter(':client_id', $this->_client_id)
            /*  */
            
            
            ->groupBy('DEB')
            ->orderBy('DEB');
        
        if ($isDEB) {
        	
        	$qb
        	->andWhere('o.DEB = :DEB')
        	->setParameter(':DEB', (int) $isDEB);
        }
        
        
        /* var_dump($isDEB, (string)$qb->getQuery()->getSql(), $this->_year . '-' . $this->_month . '-01', $this->_year . '-' . $this->_month . '-31');
        exit; */
        
        
        return $qb->getQuery()->execute();
    }

    
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
    
}
