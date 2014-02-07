<?php

namespace Application\Sonata\ClientBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Application\Sonata\ClientBundle\Entity\ListCountries;
use Symfony\Component\HttpFoundation\Request;


/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    protected $_jsSettingsJson = null;

    
    public function deleteAction($id)
    {
    	$id     = $this->get('request')->get($this->admin->getIdParameter());
    	$object = $this->admin->getObject($id);
    	
    	if (!$object) {
    		throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
    	}
    	
    	if (false === $this->admin->isGranted('DELETE', $object)) {
    		throw new AccessDeniedException();
    	}
    	
    	if ($this->getRequest()->getMethod() == 'DELETE') {
    		
    		if (!$object->getDateFinMission()) {
    			$this->get('session')->setFlash('sonata_flash_error', '"Fin de Mission" : Un client ouvert ne peut être supprimé.');
    			return $this->redirectTo($object);
    		}
    		
    		
    		try {
    			$this->admin->delete($object);
    			$this->get('session')->setFlash('sonata_flash_success', 'flash_delete_success');
    		} catch (ModelManagerException $e) {
    			$this->get('session')->setFlash('sonata_flash_error', 'flash_delete_error');
    		}
    	
    		return $this->redirect($this->admin->generateUrl('list'));
    	}
    	
    	return $this->render('ApplicationSonataClientBundle:CRUD:delete.html.twig', array(
    			'current_client' => $object,
    			//'content' => parent::deleteAction($id)->getContent(),
    			'action' => 'delete',
    			'object' => $object

    	));
 
    	 
    	
    	
    	
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction());
    }

    /**
     * {@inheritdoc}
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_update_and_list')) {
            return $this->redirect($this->generateUrl('admin_sonata_clientoperations_v01tva_list', array('filter[client_id][value]' => $object->getId())));
        }

        return parent::redirectTo($object);
    }


    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        $object = $this->admin->getObject($id);
        if (!$object) {
            return $this->redirect($this->admin->generateUrl('list'));
        }

        return $this->_action(parent::editAction($id), $id);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
    	
    	$user = \AppKernel::getStaticContainer()->get('security.context')->getToken()->getUser();
    	$this->jsSettingsJson(array(
    		
    		'isSuperviseur' => $user->hasGroup('Superviseur'),
    	));
    	
        global $clientsDimmed;

        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $clientsDimmed = json_encode(
            $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('c as client, cdi, u, ndc, SUM(cc.montant) as compte_solde_montant')
            ->leftJoin('c.center_des_impots', 'cdi')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.nature_du_client', 'ndc')
            ->leftJoin('c.comptes', 'cc')
            ->andWhere('c.date_fin_mission BETWEEN :date_lowest AND :date_highest')
            ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
            ->setParameter(':date_highest', new \DateTime())
            ->groupBy('c.id')
            ->orderBy('c.raison_sociale')
            ->getQuery()->getArrayResult()
        , 256)
        ;

        //return parent::listAction();
        
        if (false === $this->admin->isGranted('LIST')) {
        	throw new AccessDeniedException();
        }
        
        $datagrid = $this->admin->getDatagrid();
        $formView = $datagrid->getForm()->createView();
        
        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($formView, $this->admin->getFilterTheme());
        
        return $this->render($this->admin->getTemplate('list'), array(
        	'action'   => 'list',
        	'form'     => $formView,
        	'datagrid' => $datagrid,
        	'js_settings_json' => $this->_jsSettingsJson,
        ));
    }

    /**
     * @param $object
     * @param null $id
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($object, $id = null, $template = 'standard_layout_client')
    {
        $client = null;
        if($id){
            $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($id);
        }
        
        $this->jsSettingsJson(array(
            'country_eu' => ListCountries::getCountryEU(),
            'niveau_dobligation' => $this->admin->getNiveauDobligationIdListHelp(),
        
        ));

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $id,
            'current_client' => $client,
            'active_tab' => 'client',
            'content' => $object->getContent(),
            'js_settings_json' => $this->_jsSettingsJson,
        ));
    }

    /**
     * @param array $data
     */
    public function jsSettingsJson(array $data)
    {
        $this->_jsSettingsJson = json_encode($data);
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
                case 'edit':
                case 'create':
                    $parameters['base_template'] = $this->admin->getTemplate('ajax');
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

}
