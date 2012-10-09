<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
/**
 * Coordonnees controller.
 *
 */
class CoordonneesController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'coordonnees';

    public function configure()
    {

        parent::configure();

        $this->jsSettingsJson(array(
            'url' => array(
                'sortable' => $this->admin->generateUrl('sortable', array('filter' => array('client_id' => array('value' => $this->client_id))))
            )
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function sortableAction()
    {
        $ids = $this->getRequest()->request->get('ids');

        if (empty($ids)) {
            throw $this->createNotFoundException("No data coordonnees");
        }

        foreach ($ids as $weight => $id) {
            $em = $this->getDoctrine()->getManager();

            $coordonnees = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->find($id);
            $coordonnees->setOrders($weight + 1);

            $em->flush();
        }

        return $this->renderJson(array(
            'status' => 1,
        ));
    }
}
