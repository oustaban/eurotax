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

class GarantieAdmin extends Admin
{
    protected $_prefix_label = 'garantie';

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('type_garantie', null, array('label' => $label . 'type_garantie'))
            ->add('montant', null, array('label' => $label . 'montant'))
            ->add('devise', null, array('label' => $label . 'devise'))
            ->add('nom_de_la_banque', null, array('label' => $label . 'nom_de_la_banque'))
            ->add('nom_de_la_banques_id', 'choice', array(
            'label' => ' ',
            'required'  => false,
            'choices' => array(
                'a établir',
                'Nom demandé'
            )
        ))
            ->add('num_de_ganrantie', null, array('label' => $label . 'num_de_ganrantie'))
            ->add('date_demission', null, array('label' => $label . 'date_demission'))
            ->add('date_decheance', null, array('label' => $label . 'date_decheance'));
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        $label = 'list.' . $this->_prefix_label . '.';
        $listMapper
            ->add('type_garantie', null, array('label' => $label . 'type_garantie'))
            ->add('montant', null, array('label' => $label . 'montant'))
            ->add('devise', null, array('label' => $label . 'devise'));

    }
}

