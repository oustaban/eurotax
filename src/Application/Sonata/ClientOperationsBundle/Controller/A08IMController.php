<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class A08IMController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'a08im';
    protected $_operationType = 'buy';
}
