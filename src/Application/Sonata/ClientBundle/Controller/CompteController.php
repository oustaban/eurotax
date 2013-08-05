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
    			
    			$this->saveCompte($amount);
    			
    			$url = $this->generateUrl('admin_sonata_client_compte_virement', array('filter[client_id][value]' => $this->client_id, 
    				'coordonnees' => $coordonnees->getId(), 'amount' => $amount, 'facture' => $facture));
    			
    			if ($this->isXmlHttpRequest()) {
    				return $this->renderJson(array(
    						'result' => 'ok',
    						'url' => $url
    				));
    			}
    			
    			return $this->redirect($url);
    		} else {
    			
    			return $this->redirect($this->generateUrl('admin_sonata_client_compte_list', array('filter[client_id][value]' => $this->client_id)));
    		}
    	}
    	

    	
    	$data = $this->render($this->admin->getTemplate('list'), array(
    			'action'   => 'list',
    			'form'     => $formView,
    			'datagrid' => $datagrid,
    			'virement_form' => $virementForm instanceof Form ? $virementForm->createView() : false,
    			'coordonnees_count' => $this->coordonneesCount()
    	));
    	return $this->_action($data, 'list');
    }
    
    
    
    
    
    
    
    
   
    
}
