<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientBundle\Admin\ClientAdmin as ClientAdminBase;

class ClientAdmin extends ClientAdminBase
{
    public $dashboards = array('Client');

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('client_operations', null, array(
            'label' => 'list.' . 'client_operations',
            'template' => 'ApplicationSonataClientOperationsBundle:CRUD:client_operations.html.twig'
        ));
    }

    /**
     * @param string $action
     * @return array
     */
    public function getBreadcrumbs($action)
    {
        return null;
    }
}