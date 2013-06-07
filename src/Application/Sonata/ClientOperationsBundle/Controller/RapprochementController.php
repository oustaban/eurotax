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
        
        
        $a06_aib = $this->_getTableData('A06AIB', true);
        $v05_lic = $this->_getTableData('V05LIC', true);
        $deb_intro = $this->_getTableData('DEBIntro');
        $deb_exped = $this->_getTableData('DEBExped');
        $form = $this->form($client_id, $month);
        
        return array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1,
            	'blocked' => $this->getLocking() ? 0 : 1,
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

    
    private function form($client_id = null, $month = null)
    {
    
    	$form = $this->get('form.factory')->create(new RapprochementForm());
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
    			$rap->setIntroInfoText($form['intro_info_text']->getData());
    			
    			$rap->setExpedInfoId((int)$form['exped_info_id']->getData());
    			$rap->setExpedInfoNumber((double)$form['exped_info_number']->getData());
    			$rap->setExpedInfoText($form['exped_info_text']->getData());
    			
    			
    			$rap->setClientId((int)$client_id);
    			$rap->setMois($mois);
    			$rap->setDate(new \DateTime());
    			
    			$em->persist($rap);
    			$em->flush(); 
    			//$this->get('session')->setFlash('notice', 'Success');
    			return $this->render(':redirects:back.html.twig');
    			//return $this->redirect($this->generateUrl('rapprochement_index', array('client_id' => $client_id, 'month' => $month), true));
    		}
    	}
    	
    	return $form;
    
    
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

}
