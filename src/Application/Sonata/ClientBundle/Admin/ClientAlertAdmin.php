<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class ClientAlertAdmin extends Admin
{
    protected $_prefix_label = 'client_alert';

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('tabs', null, array('label' => $label . 'tabs'))
            ->add('text', null, array('label' => $label . 'text'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        $label = 'list.' . $this->_prefix_label . '.';
        $listMapper
            ->add('tabs.name', null, array('label' => $label . 'tabs'))
            ->add('text', null, array('label' => $label . 'text'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'delete' => array(),
                )
            )
        );
    }


    /**
     * @return array|void
     */
    public function getExportFormats()
    {

        return null;
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

