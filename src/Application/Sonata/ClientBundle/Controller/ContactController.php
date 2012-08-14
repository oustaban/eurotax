<?php

namespace Application\Sonata\ClientBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Application\Sonata\ClientBundle\Entity\Contact;

#use Application\Sonata\ClientBundle\Form\ContactType;


/**
 * Contact controller.
 *
 */
class ContactController extends Controller
{


    public function createAction()
    {
       $list = parent::listAction();

        $create = parent::createAction();

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'list_table'=>$list->getContent(),
            'form'=>$create->getContent(),
        ));
    }



    public function editAction($id = null)
    {
        $list = parent::listAction();
        $edit = parent::editAction($id);

        return $this->render('ApplicationSonataClientBundle::standard_layout.html.twig', array(
            'list_table'=>$list->getContent(),
            'form'=>$edit->getContent(),
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
                    $parameters['base_template'] = $this->admin->getTemplate('ajax');
                    break;
            }
        }

        return parent::render($view, $parameters, $response);
    }

}
