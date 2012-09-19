<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;

/**
 * Tarif controller.
 *
 */
class TarifController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'tarif';


    /**
     * @param $data
     * @param string $action
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _action($data, $action = 'create', $template = 'standard_layout_tarif')
    {
        if ($this->isXmlHttpRequest()) {
            return $data;
        }

        $em = $this->getDoctrine()->getManager();
        $client_invoicing = $em->getRepository('ApplicationSonataClientBundle:ClientInvoicing')->find($this->client_id);
        $action_invoice = $client_invoicing ? 'edit' : 'create';

        $client = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($this->client_id);

        return $this->render('ApplicationSonataClientBundle::' . $template . '.html.twig', array(
            'client_id' => $this->client_id,
            'client' => $client,
            'content' => $data->getContent(),
            'active_tab' => $this->_tabAlias,
            'action' => $action,
            'action_invoice' => $action_invoice,
            'js_settings_json' => $this->_jsSettingsJson,
        ));
    }
}
