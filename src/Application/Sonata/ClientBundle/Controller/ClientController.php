<?php

namespace Application\Sonata\ClientBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;


/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction());
    }


    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction($id), $id);
    }

    /**
     * @param $object
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($object, $id = null, $template = 'standard_layout_client')
    {
        return $this->render('ApplicationSonataClientBundle::'.$template.'.html.twig', array(
            'client_id' => $id,
            'active_tab' => 'client',
            'content' => $object->getContent(),
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
                case 'edit':
                case 'create':
                    $parameters['base_template'] = $this->admin->getTemplate('ajax');
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

}
