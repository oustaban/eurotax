<?php

namespace Application\Sonata\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * EtatsController controller.
 *
 */
class EtatsController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template()
     */
    public function garantiesAction()
    {
        $excel = $this->get('bashboard.garanties.excel');
        $excel->render();
        exit;
    }


}
