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

class CommentaireAdmin extends Admin
{
    protected $_prefix_label = 'commentaire';

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('date', null, array(
            'label' => $label . 'date',
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('categorie', null, array('label' => $label . 'categorie'))
            ->add('note', null, array('label' => $label . 'note'));
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
            ->add('date', null, array(
            'label' => $label . 'date',
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date.html.twig'
        ))
            ->add('categorie.name', null, array('label' => $label . 'categorie'))
            ->add('note', null, array('label' => $label . 'note'));
    }
}

