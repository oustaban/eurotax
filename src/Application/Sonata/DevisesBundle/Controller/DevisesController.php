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
     * @return mixed|string
     */
    public function getBaseTemplate()
    {
        return str_replace('SonataAdminBundle', 'ApplicationSonataDevisesBundle', parent::getBaseTemplate());
    }

    /**
     * @param array $data
     */
    public function jsSettingsJson(array $data)
    {
        $this->_jsSettingsJson = json_encode($data);
    }
}