<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Rapprochement controller.
 *
 */
class RapprochementController extends Controller
{
    protected $_month = '';
    protected $_year = '';
    protected $_client_id = null;

    /**
     * @Template()
     */
    public function indexAction($client_id, $month)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($client_id);

        if (empty($client)) {
            throw new NotFoundHttpException(sprintf('unable to find Client with id : %s', $client_id));
        }

        $this->_client_id = $client_id;
        list($this->_month, $this->_year) = $this->getQueryMonth($month);

        $a06_aib = $this->_getTableData('A06AIB');
        $v05_lic = $this->_getTableData('V05LIC');
        $deb_intro = $this->_getTableData('DEBIntro', true);
        $deb_exped = $this->_getTableData('DEBExped', true);

        return array(
            'info' => array(
                'time' => strtotime($this->_year . '-' . $this->_month . '-01'),
                'month' => $this->_month,
                'year' => $this->_year,
                'quarter' => floor(($this->_month - 1) / 3) + 1
            ),
            'client' => $client,
            'a06_aib' => $a06_aib,
            'v05_lic' => $v05_lic,
            'deb_intro' => $deb_intro,
            'deb_exped' => $deb_exped,
        );
    }

    /**
     * @param $query_month
     * @return array
     */
    public function getQueryMonth($query_month)
    {
        $year = substr($query_month, -4);
        $month = $query_month == -1 ? (date('n') - 1) . '|' . $year : $query_month;

        return explode('|', $month);
    }

    protected function _getTableData($clientOperationName, $isDEB = false)
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();

        if ($isDEB) {
            $deb = '';
            $v1 = 'valeur_statistique';
            $v2 = 'valeur_fiscale';
        }
        else {
            $deb = '*o.DEB';
            $v1 = 'montant_HT_en_devise/o.taux_de_change';
            $v2 = 'HT';
        }

        $qb = $em->getRepository('ApplicationSonataClientOperationsBundle:' . $clientOperationName)
            ->createQueryBuilder('o')
            ->select('o.regime' . $deb . ' AS DEB, o.regime, SUM(o.' . $v1 . ') AS v1, COUNT(o.id) AS nb , SUM(o.' . $v2 . ') AS v2')
            ->andWhere('o.date_piece BETWEEN :form_date_piece AND :to_date_piece')
            ->setParameter(':form_date_piece', $this->_year . '-' . $this->_month . '-01')
            ->setParameter(':to_date_piece', $this->_year . '-' . $this->_month . '-31')
            ->andWhere('o.client_id = :client_id')
            ->setParameter(':client_id', $this->_client_id)
            /*  */
            
            
            ->groupBy('DEB')
            ->orderBy('DEB');
        
        if($clientOperationName == 'V05LIC') {
        	
        	$qb
        	->andWhere('o.DEB = :DEB')
        	->setParameter(':DEB', (int) $isDEB);
        }
        
        
        
        
        return $qb->getQuery()->execute();
    }

}
