<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Application\Tools\mPDF;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Rapprochement controller.
 *
 */
class ImpressionController extends Controller
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
     * @var string
     */
    protected $_tabAlias = 'impression';

    protected $_month = '';
    protected $_year = '';
    protected $_client_id = null;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->configure();
    }

    public function configure()
    {
        $filter = $this->getRequest()->query->get('filter');
        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {
            $this->client_id = $filter['client_id']['value'];
        }

        if (empty($this->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }

        $this->client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

        if (empty($this->client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $this->client_id));
        }
    }

    /**
     * @return array
     */
    public function _action()
    {
        return array(
            'client_id' => $this->client_id,
            'client' => $this->getClient(),
            'active_tab' => $this->_tabAlias,
        	'contact' => $this->getContact(),
        );
    }

    /**
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        return $this->_action();
    }

    /**
     * @Template()
     */
    public function etiquetteAction()
    {
        $action = $this->_action();

        /** @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->get('request');

        if ($request->getMethod() == 'POST'){
            $page = $this->render('ApplicationSonataClientBundle:Impression:etiquette_pdf.html.twig', array_merge($action, array('post' => $request->request->all())));

            $mpdf = new mPDF('c', 'A4', 0, '', 0, 0, 0, 0, 9, 2);
            //$mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML($page->getContent());
            $mpdf->Output();

            exit;
        }

        return $action;
    }

    /**
     * @return \Application\Sonata\ClientBundle\Entity\Client|null
     */
    public function getClient()
    {
        return $this->client;
    }
    
    
    
    public function getContact() {
    	
    	return $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Contact')->findOneByClient($this->client_id);
    	
    	
    	// return $this->client;
    }
    
    
}
