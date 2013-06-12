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
    			
    			return $this->redirect($this->generateUrl('admin_sonata_client_compte_virement', array('filter[client_id][value]' => $this->client_id, 'coordonnees' => $coordonnees->getId(), 'amount' => $amount)));
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
    
    
    
    public function virementAction($amount, $coordonnees) {
    	$client = $this->getClient();
    	
    	
    	$coordonneesId = (int) $coordonnees;
    	
    	$em = $this->getDoctrine()->getManager();
    	$coordonnees = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->find($coordonneesId);
    	$page = $this->render('ApplicationSonataClientBundle::virement.html.twig', array(
    			'client' => $client,
    			'amount' => $amount,
    			'amountWords' => $this->_amountToWords($amount),
    			'coordonnees' => $coordonnees
    		));
    	$mpdf = new mPDF('c', 'A4', 0, '', 15, 15, 13, 13, 9, 2);
    	$mpdf->WriteHTML($page->getContent());
    	$mpdf->Output();
    	exit;
    }
    
    
    
    private function _amountToWords($amount) {
    	$words = array();
    	$len = strlen($amount);
    	for($i = 0; $i < $len; $i++) {
    		$num = $amount[$i];
    		if((int)$num) {
    			$words[] = $this->get('translator')->trans(\Pear\NumbersWordsBundle\Numbers\Words::toWords($num));
    		} else {
    			$words[] = $num;
    		}
    	}
    	
    	return str_replace(' ,', ',', implode(' ', $words));
    }
    
    
    private function _amountToInt($amount) {
    	$amount = str_replace(array("\n","\t","\r"), " ", $amount);
    	$amount =  str_replace(array(' ', ','), array('', '.'), $amount);
    	$amount = preg_replace('/[^(\x20-\x7F)]*/','', $amount);
    	return (float) $amount;
    }
    
    
    
    
    
    
   
    
}
