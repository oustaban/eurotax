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
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->findAll();

        $dql = "SELECT SUM(c.alert_count) AS counts FROM ApplicationSonataClientBundle:Client c";
        $sql = $em->createQuery($dql);
        list($result) = $sql->getArrayResult();

        return array(
            'clients' => $clients,
            'cookies' => $this->getRequest()->cookies,
            'alert_count' => $result['counts'],
        );
    }

}
