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
    protected $_jsSettingsJson = null;

    public function configure()
    {
        parent::configure();

        if (empty($this->admin->client_id)) {
            throw new NotFoundHttpException('Unable load page with no client_id');
        }
        $this->client_id = $this->admin->client_id;

        $this->client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

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
        $this->_jsSettingsJson = json_encode($data);
    }
}
