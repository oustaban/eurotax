<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * ClientInvoicing controller.
 *
 */
class ClientInvoicingController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'clientinvoicing';

    /**
     * @param $data
     * @param string $action
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($data, $action = 'create', $template = 'standard_layout')
    {
        if (($action == 'create' || $action == 'edit') && empty($_POST)) {
            $template = 'standard_layout_content';
        }

        return parent::_action($data, $action, $template);
    }

    /**
     * @param object $object
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function redirectTo($object)
    {
        $url = $this->admin->getLinkTarif();

        return new RedirectResponse($url);
    }

}

