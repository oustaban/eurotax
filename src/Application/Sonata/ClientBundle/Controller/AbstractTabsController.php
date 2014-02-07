<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

use Application\Sonata\ClientBundle\Entity\Coordonnees;
use Application\Sonata\ClientBundle\Entity\Compte;


use Application\Tools\mPDF;


/**
 * AbstractTabsController controller.
 *
 */
abstract class AbstractTabsController extends Controller
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
    protected $_tabAlias = '';
    protected $_template = null;

    /**
     * @var string
     */
    protected $_jsSettingsJson = null, $_jsSettingsJsonData = array();

    public function configure()
    {
        parent::configure();

        if (empty($this->admin->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }
        $this->client_id = $this->admin->client_id;
        $this->client = $this->admin->getClient();

        if (empty($this->client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $this->admin->client_id));
        }
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

        if ($this->_template) {
            $template = $this->_template;
        }

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $this->getClient(),
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'action' => $action,
            'js_settings_json' => $this->_jsSettingsJson,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction(), 'create');
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction(), 'edit');
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
    	
        return $this->_action(parent::listAction(), 'list');
    }

    /**
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction($id)
    {
        $action = parent::deleteAction($id);

        if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
            ));
        }

        return $action;
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
                case 'list':
                case 'edit':
                case 'create':
                    if ($this->get('request')->getMethod() != 'POST') {
                        $parameters['base_template'] = 'ApplicationSonataClientBundle::ajax_layout.html.twig';
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
    
    protected function saveCompte($amount) {
    	/* @var $em \Doctrine\ORM\EntityManager */
    	$em = $this->getDoctrine()->getManager();
    	$status = $em->getRepository('ApplicationSonataClientBundle:ListCompteStatuts')->find(2); // previsionnel
    	$compte = new Compte();
    	$compte->setClient($this->client)
    	->setStatut($status)
    	->setMontant( $this->amountToInt($amount) * -1 )
    	->setOperation('Notre Transfert en votre faveur')
    	->setDate(new \DateTime())
    	;
    
    	$em->persist($compte);
    	$em->flush();
    
    }
    
    
    protected function coordonneesCount() {
    	$em = $this->getDoctrine()->getManager();
    	$coordonnees = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->findByClient($this->client_id);
    	return count($coordonnees);
    }
    
    
    
    public function virementAction($amount, $coordonnees, $facture) {
    	$client = $this->getClient();
    	$coordonneesId = (int) $coordonnees;
    	 
    	$amountEuro = $this->amountToEuro($amount);
    	$amount = $this->amountToInt($amount);
    
    	$em = $this->getDoctrine()->getManager();
    	$coordonnees = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->find($coordonneesId);
    	$page = $this->render('ApplicationSonataClientBundle::virement.html.twig', array(
    			'client' => $client,
    			'amount' => $amountEuro,
    			'amountWords' => $this->amountToWords($amount),
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
    protected function amountToWords($amount) {
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
    
    
    protected function amountToInt($amount) {
    	$amount = str_replace(array("\n","\t","\r"), " ", $amount);
    	$amount =  str_replace(array(' ', ','), array('', '.'), $amount);
    	$amount = preg_replace('/[^(\x20-\x7F)]*/','', $amount);
    	return (float) $amount;
    }
    
    
    
    protected function amountToEuro($amount) {
    	$amount = $this->amountToInt($amount);
    	return number_format($amount, 2, ",", " ");
    }
    
}
