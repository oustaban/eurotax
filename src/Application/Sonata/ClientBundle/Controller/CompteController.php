<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Application\Sonata\ClientBundle\Form\VirementForm;
use Application\Sonata\ClientBundle\Entity\Coordonnees;

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
    			$amount = (float)$virementForm['amount']->getData();
    			$coordonnees = $virementForm['coordonnees']->getData();
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
    			'coordonnees' => $coordonnees
    		));
    	$mpdf = new mPDF('c', 'A4', 0, '', 15, 15, 13, 13, 9, 2);
    	$mpdf->WriteHTML($page->getContent());
    	$mpdf->Output();
    	exit;
    }
    
    
    
   
    
}
