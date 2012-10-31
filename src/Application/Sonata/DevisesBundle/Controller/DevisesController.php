<?php

namespace Application\Sonata\DevisesBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Controller\CRUDController as Controller;

class DevisesController extends Controller
{
    protected $_jsSettingsJson = null;

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $object = $this->admin->getCurrentDevises(date('Y-m'));
        if ($object) {
            $url = $this->admin->generateObjectUrl('edit', $object);
            return $this->redirect($url);
        }

        return parent::createAction();
    }


    /**
     * @param string $view
     * @param array $parameters
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        echo $parameters['base_template'] = 'ApplicationSonataDevisesBundle::layout.html.twig ';
        exit;

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