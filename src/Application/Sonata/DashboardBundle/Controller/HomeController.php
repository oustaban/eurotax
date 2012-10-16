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
            ->andWhere('(NOT c.date_fin_mission BETWEEN :date_lowest AND :date_highest) OR (c.date_fin_mission IS NULL)')
            ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
            ->setParameter(':date_highest', new \DateTime())
            ->getQuery()->execute();

        $alerts = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('SUM(c.alert_count) as cnt')
            ->getQuery()->execute();

        return array(
            'clients' => $clients,
            'cookies' => $this->getRequest()->cookies,
            'alert_count' => $alerts[0]['cnt'],
        );
    }

}
