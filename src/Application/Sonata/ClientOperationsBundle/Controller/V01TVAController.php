<?php

namespace Application\Sonata\ClientOperationsBundle\Controller;

use Application\Sonata\ClientOperationsBundle\Controller\AbstractTabsController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

class V01TVAController extends Controller
{
    /**
     * @var string
     */
    protected $_tabAlias = 'v01tva';
    protected $_operationType = 'sell';

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createAction()
    {
        return $this->_action(parent::createAction());
    }

    /**
     * @param null $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        return $this->_action(parent::editAction());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        return $this->_action(parent::listAction());
    }
}
