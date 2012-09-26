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

class TarifAdmin extends Admin
{
    protected $_prefix_label = 'tarif';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('client_id', 'hidden', array(
            'data' => $filter['client_id']['value'],
            'attr' => array('class' => 'client_id'),
        ))
            ->add('mode_de_facturation', null, array('label' => $label . 'mode_de_facturation'))
            ->add('value', 'money', array('label' => $label . 'value'))
            ->add('value_percentage', 'percent', array('label' => $label . 'value_percentage'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $label = 'list.' . $this->_prefix_label . '.';
        $listMapper
            ->add('mode_de_facturation', null, array('label' => $label . 'mode_de_facturation'))
            ->add('value', 'money', array('label' => $label . 'value'))
            ->add('value_percentage', 'percent', array('label' => $label . 'value_percentage'))
            ->add('mode_de_facturation.invoice_type.name', null, array('label' => $label . 'invoice_type'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'delete' => array(),
                )
            )
        );
    }
}

