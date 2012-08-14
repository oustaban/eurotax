<?php

namespace Application\Sonata\ClientBundle\Controller;

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
    protected  $_tabAlias = '';

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $list = parent::listAction();

        $create = parent::createAction();

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'client_id' => $this->client_id,
            'active_tab' => $this->_tabAlias,
            'list_table' => $list->getContent(),
            'form' => $create->getContent(),
        ));
    }


    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        $list = parent::listAction();
        $edit = parent::editAction($id);

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'client_id' => $this->client_id,
            'active_tab' => $this->_tabAlias,
            'list_table' => $list->getContent(),
            'form' => $edit->getContent(),
        ));
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
                    //fix template to delete
                    if (!$this->getRequest()->query->get('client_id')) {
                        $parameters['base_template'] = $this->admin->getTemplate('ajax');
                    }
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

}
