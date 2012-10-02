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
    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $this->getRequest()->query->get('filter');

        $formMapper->with('form.compte.title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
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
        $listMapper->addIdentifier('id', null);

        $listMapper->add('date', null, array('label' => 'list.compte.date'))
            ->add('operation', null, array('label' => 'list.compte.operation'))
            ->add('montant', null, array('label' => 'list.compte.montant'))
            ->add('commentaire', null, array('label' => 'list.compte.commentaire'))
            ->add('statut.name', null, array('label' => 'list.compte.statut'));
    }
}

