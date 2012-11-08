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
                'sortable' => $this->admin->generateUrl('sortable', array('filter' => array('client_id' => array('value' => $this->client_id)))),
            ),
            'country_sepa' => $this->admin->getListCountrySepa(),
            'drag_text' => $this->admin->trans('Drag to re-order'),
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

        $em = $this->getDoctrine()->getManager();
        foreach ($ids as $weight => $id) {

            $object = $em->getRepository('ApplicationSonataClientBundle:Coordonnees')->find($id);
            $object->setOrders($weight + 1);
            $em->persist($object);
        }
        $em->flush();

        return $this->renderJson(array(
            'status' => 1,
        ));
    }
}
