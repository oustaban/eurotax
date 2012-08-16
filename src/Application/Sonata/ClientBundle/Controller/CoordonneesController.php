<?php

namespace Application\Sonata\ClientBundle\Controller;

/**
 * Coordonnees controller.
 *
 */
class CoordonneesController extends AbstractTabsController
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
