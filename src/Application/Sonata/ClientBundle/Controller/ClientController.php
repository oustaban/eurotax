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
    public function createAction()
    {
        $create = parent::createAction();

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'client_id' => null,
            'active_tab' => 'client',
            'content' => $create->getContent(),
        ));
    }


    public function editAction($id = null)
    {
        $edit = parent::editAction($id);

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'client_id' => $id,
            'active_tab' => 'client',
            'content' => $edit->getContent(),
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
