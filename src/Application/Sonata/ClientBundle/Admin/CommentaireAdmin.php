<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

class CommentaireAdmin extends AbstractTabsAdmin
{
    protected $_prefix_label = 'commentaire';

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->getClassnameLabel();

        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.' . $this->_prefix_label;
        $formMapper->with($label . '.title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('date', null, array('label' => $label . '.date'))
            ->add('categorie', null, array('label' => $label . '.categorie'))
            ->add('note', null, array('label' => $label . '.note'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        $label = 'list.' . $this->_prefix_label;
        $listMapper
            ->add('date', null, array('label' => $label . '.date'))
            ->add('categorie', null, array('label' => $label . '.categorie'))
            ->add('note', null, array('label' => $label . '.note'));
    }
}

