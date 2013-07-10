<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Application\Sonata\ClientBundle\Form\VirementForm;
use Application\Sonata\ClientBundle\Entity\Coordonnees;
use Application\Sonata\ClientBundle\Entity\Compte;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Form;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Application\Tools\mPDF;

/**
 * Compte controller.
 *
 */
class CompteController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'compte';
    
    

    public function listAction()
    {
    	if (false === $this->admin->isGranted('LIST')) {
    		throw new AccessDeniedException();
    	}
    	
    	$user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
    	$this->jsSettingsJson(array(
    		'isSuperviseur' => $user->hasGroup('Superviseur'),
    	));
    	
    	
    	
    	$datagrid = $this->admin->getDatagrid();
    	$formView = $datagrid->getForm()->createView();
    
    	// set the theme for the current Admin Form
    	$this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());
    
    	
    	$virementForm = $this->get('form.factory')->create(new VirementForm($this->client_id));
    	$request = $this->get('request');
    	if ($request->getMethod() == 'POST')
    	{
    		$virementForm->bindRequest($request);
    		if ($virementForm->isValid()) {
				// Get raw data coz of issue on euro number format.    			
    			$formRequestData = $request->request->all();
    			$formRequestData = $formRequestData[$virementForm->getName()];
    			
    			$amount = $formRequestData['amount'];
    			$coordonnees = $virementForm['coordonnees']->getData();
    			$facture = $virementForm['facture']->getData();
    			
    			/* @var $em \Doctrine\ORM\EntityManager */
    			$em = $this->getDoctrine()->getManager();
    			$status = $em->getRepository('ApplicationSonataClientBundle:ListCompteStatuts')->find(2); // previsionnel
    			$compte = new Compte();
    			$compte->setClient($this->client)
    				->setStatut($status)
    				->setMontant( $this->_amountToInt($amount) )
    				->setOperation('Notre Transfert en votre faveur')
    				->setDate(new \DateTime())
    			;
    			
    			$em->persist($compte);
    			$em->flush();
    			
    			return $this->redirect($this->generateUrl('admin_sonata_client_compte_virement', array('filter[client_id][value]' => $this->client_id, 
    				'coordonnees' => $coordonnees->getId(), 'amount' => $amount, 'facture' => $facture)));
    		} else {
    			
    			return $this->redirect($this->generateUrl('admin_sonata_client_compte_list', array('filter[client_id][value]' => $this->client_id)));
    		}
    	}
    	
    	
    	$data = $this->render($this->admin->getTemplate('list'), array(
    			'action'   => 'list',
    			'form'     => $formView,
    			'datagrid' => $datagrid,
    			'virement_form' => $virementForm instanceof Form ? $virementForm->createView() : false
    	));
    	return $this->_action($data, 'list');
    }
    
    
    
    public function virementAction($amount, $coordonnees, $facture) {
    	$client = $this->getClient();
    	$coordonneesId = (int) $coordonnees;
    	
    	$amountEuro = $this->_amountToEuro($amount);
    	$amount = $this->_amountToInt($amount);
  
 
    	$em = $this->getDoctrine()->getManager();
    	$coordonnees = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->find($coordonneesId);
    	$page = $this->render('ApplicationSonataClientBundle::virement.html.twig', array(
    			'client' => $client,
    			'amount' => $amountEuro,
    			'amountWords' => $this->_amountToWords($amount),
    			'coordonnees' => $coordonnees,
    			'facture' => $facture
    		));
    	
    	if(isset($_GET['d'])) {
    		echo $page->getContent();
    	} else {
	    	$mpdf = new mPDF('c', 'A4', 0, '', 15, 15, 13, 13, 9, 2);
	    	$mpdf->WriteHTML($page->getContent());
	    	$mpdf->Output();
    	}
    	
    	exit;
    }
    
    
    // for 100 000.00 ... I shoud have"un zero zero zero zero zero € 00 centimes
    private function _amountToWords($amount) {
    	$words = array();
    	$amounts = explode('.', (string)$amount);
    	$cents = '00';
    	$amount = $amounts[0];
    	if(isset($amounts[1])) {
    		$cents = $amounts[1];
    	}
    	
    	$len = strlen($amount);
    	for($i = 0; $i < $len; $i++) {
    		$num = $amount[$i];
    		if((int)$num > -1) {
    			$words[] = $this->get('translator')->trans(\Pear\NumbersWordsBundle\Numbers\Words::toWords($num));
    		} else {
    			$words[] = $num;
    		}
    	}
    	
    	$words = implode(' ', $words) . ' € ';
    	if($cents) {
    		$words .= $cents . ' centimes';
    	}
    	return $words;

    }
    
    
    private function _amountToInt($amount) {
    	$amount = str_replace(array("\n","\t","\r"), " ", $amount);
    	$amount =  str_replace(array(' ', ','), array('', '.'), $amount);
    	$amount = preg_replace('/[^(\x20-\x7F)]*/','', $amount);
    	return (float) $amount;
    }
    
    
    
    private function _amountToEuro($amount) {
    	$amount = $this->_amountToInt($amount);
    	return number_format($amount, 2, ",", " ");
    }
    
    
    
    
    
   
    
}
