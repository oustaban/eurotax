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

abstract class AbstractCompteAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with('form.compte.title')
            ->add('date', null, array(
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'label' => 'form.compte.date'
        ))
            ->add('operation', null, array('label' => 'form.compte.operation'))
            ->add('montant', 'money', array('label' => 'form.compte.montant'))
            ->add('commentaire', null, array('label' => 'form.compte.commentaire'))
            ->add('statut', null, array(
            'label' => 'form.compte.statut',
            'empty_value' => '',
            'required' => false,
        ));

    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('date', null, array(
                'label' => 'list.compte.date',
                'template' => 'ApplicationSonataClientBundle:CRUD:list_date.html.twig'
        ))
            ->add('operation', null, array('label' => 'list.compte.operation'))
            ->add('montant', null, array('label' => 'list.compte.montant'))
            ->add('commentaire', null, array('label' => 'list.compte.commentaire'))
            ->add('statut.name', null, array('label' => 'list.compte.statut'));
    }
}

