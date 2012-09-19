<?php

namespace Application\Sonata\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


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
     * @var string
     */
    protected $_tabAlias = '';

    /**
     * @var string
     */
    protected $_jsSettingsJson = null;

    protected $maxPerPage = 25;

    public function __construct()
    {
        $filter = Request::createFromGlobals()->query->get('filter');
        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {
            $this->client_id = $filter['client_id']['value'];
        } else {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }
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

        $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $client,
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
        return $this->_action(parent::listAction(), 'list');
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


    public function jsSettingsJson(array $data)
    {

        $this->_jsSettingsJson = json_encode($data);
    }
}
