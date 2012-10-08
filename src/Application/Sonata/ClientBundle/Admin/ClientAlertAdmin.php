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
        parent::configureFormFields($formMapper);

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('tabs', null, array('label' => $label . 'tabs', 'empty_value' => '', 'required' => false))
            ->add('text', null, array('label' => $label . 'text'))
            ->add('is_blocked', null, array('label' => $label . 'is_blocked'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->addIdentifier('id', null);

        $label = 'list.' . $this->_prefix_label . '.';
        $listMapper
            ->add('tabs.name', null, array('label' => $label . 'tabs'))
            ->add('text', null, array('label' => $label . 'text'));
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

