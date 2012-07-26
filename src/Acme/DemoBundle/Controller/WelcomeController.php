<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WelcomeController extends Controller
{
    public function indexAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array('user' => $user));
    }
}
