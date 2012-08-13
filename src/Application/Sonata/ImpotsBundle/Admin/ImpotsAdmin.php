<?php

namespace Application\Sonata\ImpotsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Application\Form\Type\LocationType;

use Knp\Menu\ItemInterface as MenuItemInterface;

class ImpotsAdmin extends Admin
{
    //create & edit form
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('nom')
            ->add('nom_de_la_banque')
            ->add('location', new LocationType(), array(
            'data_class' => 'Application\Sonata\ImpotsBundle\Entity\Impots',
            //'label' => ' ',
        ), array('type' => 'location'))
            ->add('no_de_compte')
            ->add('code_swift')
            ->add('IBAN')
            ->add('SEPA');
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('nom')
            ->add('nom_de_la_banque')
            ->add('code_postal')
            ->add('ville')
            ->add('pays_id')
            ->add('no_de_compte')
            ->add('code_swift')
            ->add('IBAN')
            ->add('SEPA');
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('nom')
            ->add('nom_de_la_banque')
            ->add('adresse_1')
            ->add('adresse_2')
            ->add('code_postal')
            ->add('ville')
            ->add('pays_id')
            ->add('no_de_compte')
            ->add('code_swift')
            ->add('IBAN')
            ->add('SEPA');
    }
}