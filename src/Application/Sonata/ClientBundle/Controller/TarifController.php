<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

        $this->jsSettingsJson(array(
            'mode_de_facturation' => $this->getModeDeFacturationJson()
        ));

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

    /**
     *
     */
    protected function getModeDeFacturationJson()
    {
        $rows = array();

        $repository = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataClientBundle:ListModeDeFacturations');

        /** @var $query \Doctrine\ORM\Query */
        $query = $repository->createQueryBuilder('m')
            ->getQuery();

        $lists = $query->getArrayResult();

        foreach ($lists as $list) {
            $rows[$list['id']] = $list;
        }

        return $rows;
    }

    /**
     * @param mixed $id
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     * @throws NotFoundHttpException
     */
    public function deleteAction($id)
    {
        /** @var $action RedirectResponse */
        $action = parent::deleteAction($id);

        if ($this->getRequest()->getMethod() == 'DELETE' && $this->isXmlHttpRequest()) {
            return $this->renderJson(array(
                'result' => 'ok',
            ));
        }

        return $action;
    }
}
