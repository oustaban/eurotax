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
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('c, cdi, u, ndc')
            ->leftJoin('c.center_des_impots', 'cdi')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.nature_du_client', 'ndc')
            ->getQuery()->execute();

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
