<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ImpersonatingController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
}