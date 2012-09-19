<?php

namespace Application\Sonata\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * HomeController controller.
 *
 */
class HomeController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        $clients = $this->getDoctrine()->getManager()
            ->getRepository('Application\Sonata\ClientBundle\Entity\Client')
            ->findAll();
        ;

        return array('clients' => $clients, 'cookies'=>$this->getRequest()->cookies);
    }

}
