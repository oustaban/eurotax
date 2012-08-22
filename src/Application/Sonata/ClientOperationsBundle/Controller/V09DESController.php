<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class V09DESController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'v09des';
    protected $_operationType = 'sell';


}
