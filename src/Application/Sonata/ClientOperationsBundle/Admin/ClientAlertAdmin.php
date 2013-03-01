<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

use Application\Sonata\ClientBundle\Admin\ClientAlertAdmin as ClientAlertAdminBase;

class ClientAlertAdmin extends ClientAlertAdminBase
{
    protected $_bundle_name = 'ApplicationSonataClientOperationsBundle';

    protected $maxPerPage = 10000000;

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);
        $listMapper->remove('id');
    }


    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {

        parent::configureRoutes($collection);

        $collection->remove('edit')
            ->remove('create')
            ->remove('show')
            ->remove('export');
    }

    /**
     * {@inheritdoc}
     */
    public function getClass()
    {
        $class = parent::getClass();

        $backtrace = (version_compare(PHP_VERSION, '5.4.0') >= 0) ? debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2) : debug_backtrace();
        if (in_array($backtrace[1]['function'], array('getBaseRouteName', 'getBaseRoutePattern'))) {
            $class = str_replace('\\ClientBundle\\', '\\ClientOperationsBundle\\', $class);
        }

        return $class;
    }
}