<?php

namespace Application\Sonata\UserBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

/**
 * Group controller.
 *
 */
class GroupController extends Controller
{

    /**
     * @param object $object
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectTo($object)
    {
        if ($this->get('request')->get('btn_create_and_edit')) {
            $this->get('request')->query->set('btn_update_and_list', $this->get('request')->get('btn_create_and_edit'));
        }

        return parent::redirectTo($object);
    }
}
