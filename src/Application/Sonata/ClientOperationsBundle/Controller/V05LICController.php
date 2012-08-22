<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class V05LICController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'v05lic';
    protected $_operationType = 'sell';

}
