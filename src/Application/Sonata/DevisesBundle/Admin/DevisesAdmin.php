<?php

namespace Application\Sonata\DevisesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Knp\Menu\ItemInterface as MenuItemInterface;

class DevisesAdmin extends Admin
{
    //create & edit form
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('date', 'date', array(
                'format' => 'dd MMMM yyyy',
                'days' => range(1, 1),
                'years' => range(date('Y'), date('Y')+3),
                'label' => ' ',
                ))
            ->add('money_dollar', 'text',  array(
                'label'=>'Dollar'
        ))
            ->add('money_yen', 'text', array(
                'label'=>'Yen'
        ))
            ->add('money_british', 'text', array(
                'label'=>'Livre Sterling'
        ))
        ;
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('date')
            ->add('money_dollar')
            ->add('money_yen')
            ->add('money_british')
        ;
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('date')
            ->add('money_dollar')
            ->add('money_yen')
            ->add('money_british')
        ;
    }
}