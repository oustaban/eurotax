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
            ->select('c, cdi, u, ndc, SUM(cc.montant) as compte_solde_montant, 0 as dimmed')
            ->leftJoin('c.center_des_impots', 'cdi')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.nature_du_client', 'ndc')
            ->leftJoin('c.comptes', 'cc')
            ->andWhere('(NOT c.date_fin_mission BETWEEN :date_lowest AND :date_highest) OR (c.date_fin_mission IS NULL)')
            ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
            ->setParameter(':date_highest', new \DateTime())
            ->groupBy('c.id')
            ->orderBy('c.raison_sociale')
            ->getQuery()->execute();

        $clientsDimmed = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('c, cdi, u, ndc, SUM(cc.montant) as compte_solde_montant, 1 as dimmed')
            ->leftJoin('c.center_des_impots', 'cdi')
            ->leftJoin('c.user', 'u')
            ->leftJoin('c.nature_du_client', 'ndc')
            ->leftJoin('c.comptes', 'cc')
            ->andWhere('c.date_fin_mission BETWEEN :date_lowest AND :date_highest')
            ->setParameter(':date_lowest', new \DateTime('1000-01-01'))
            ->setParameter(':date_highest', new \DateTime())
            ->groupBy('c.id')
            ->orderBy('c.raison_sociale')
            ->getQuery()->execute();

        $alerts = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('SUM(c.alert_count) as cnt')
            ->getQuery()->execute();

        
        $now = new \DateTime();
        // if DD > 25 Mois-TVA MM.YYYY - 1MM  else Mois-TVA MM.YYYY - 2MM
        $moisExtraColTitle = $now->format('d') > 25 ? date('m.Y', strtotime('now -1 month')) : date('m.Y', strtotime('now -2 month'));
        
        return array(
            'clients' => array_merge($clients, $clientsDimmed),
            'cookies' => $this->getRequest()->cookies,
            'alert_count' => $alerts[0]['cnt'],
        	'moisExtraColTitle' => $moisExtraColTitle
        );
    }

}
