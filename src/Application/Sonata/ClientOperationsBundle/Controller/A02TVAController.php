<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class A02TVAController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'a02tva';
    protected $_operationType = 'buy';


}
