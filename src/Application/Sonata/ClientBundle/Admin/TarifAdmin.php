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
use Doctrine\ORM\EntityRepository;

class TarifAdmin extends Admin
{
    protected $_prefix_label = 'tarif';

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('mode_de_facturation', null, array('label' => $label . 'mode_de_facturation', 'query_builder' => function(EntityRepository $er)
        {
            return $er->createQueryBuilder('u')
                ->orderBy('u.name', 'ASC');
        },))
            ->add('value', 'money', array('label' => $label . 'value'))
            ->add('value_percentage', 'percent', array('label' => $label . 'value_percentage'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

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

