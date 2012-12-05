<?php

namespace Application\Sonata\UserBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

/**
 * User controller.
 *
 */
class UserController extends Controller
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

    /**
     * {@inheritdoc}
     */
    public function deleteAction($id)
    {
        if (!$this->_canUserBeDeleted($id)) {
            return $this->redirect($this->admin->generateUrl('edit', array('id' => $id)));
        }

        return parent::deleteAction($id);
    }

    /**
     * @param $idx array
     * @param $all_elements bool
     * @return bool
     */
    public function batchActionDeleteIsRelevant($idx, $all_elements)
    {
        $return = true || $all_elements;
        foreach ($idx as $index => $id) {
            if (!$this->_canUserBeDeleted($id)) {
                $return = false;
                unset($idx[$index]);
            }
        }

        return $return;
    }

    protected function _canUserBeDeleted($id)
    {
        /** @var $user \Application\Sonata\UserBundle\Entity\User */
        $user = $this->admin->getObject($id);

        if ($user) {
            $em = $this->getDoctrine()->getManager();
            $clients = $em->getRepository('ApplicationSonataClientBundle:Client')->findByUser($user);
            if ($clients) {
                $editUrl = '<a href="' . $this->admin->generateUrl('edit', array('id' => $id)) . '">' . $user . '</a>';
                $messages = array();
                foreach ($clients as $client) {
                    /** @var $client \Application\Sonata\ClientBundle\Entity\Client */
                    $messages[] = '<a href="' . $this->generateUrl('admin_sonata_client_client_edit', array('id' => $client->getId())) . '">' . $client->getNom() . '</a>';
                }

                $this->get('session')->getFlashBag()->add('sonata_flash_error|raw', 'L\'utilisateur ' . $editUrl . ' est le gestionnaire du/des client(s) ' . implode(', ', $messages) . '!');
                return false;
            }
        }

        return true;
    }
}
