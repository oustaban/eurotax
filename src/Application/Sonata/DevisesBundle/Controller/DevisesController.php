<?php

namespace Application\Sonata\DevisesBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class DevisesController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        $date = new \DateTime(date('Y-m') . '-01');
        $object = $this->getDoctrine()->getManager()->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($date);

        if ($object) {

            $url = $this->admin->generateObjectUrl('edit', $object);
            return $this->redirect($url);
        }

        return parent::createAction();
    }
}