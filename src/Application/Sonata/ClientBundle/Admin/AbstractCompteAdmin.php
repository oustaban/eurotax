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

        $formMapper->with($this->getFieldLabel('title'))
            ->add('date', null, array(
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'label' => $this->getFieldLabel('date'),
        ))
            ->add('operation', null, array('label' => $this->getFieldLabel('operation')))
            ->add('montant', 'money', array('label' => $this->getFieldLabel('montant')))
            ->add('commentaire', null, array('label' => $this->getFieldLabel('commentaire')))
            ->add('statut', null, array(
            'label' => $this->getFieldLabel('statut'),
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
            'label' => $this->getFieldLabel('date'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date.html.twig'
        ))
            ->add('operation', null, array('label' => $this->getFieldLabel('operation')))
            ->add('montant', 'money', array('label' => $this->getFieldLabel('montant')))
            ->add('solde', 'money', array(
            'label' => $this->getFieldLabel('solde'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_solde.html.twig'
        ))
            ->add('commentaire', null, array('label' => $this->getFieldLabel('commentaire')))
            ->add('statut.name', null, array('label' => $this->getFieldLabel('statut')));
    }
}

