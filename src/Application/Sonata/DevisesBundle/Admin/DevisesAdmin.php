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
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );


    //create & edit form
    protected $_money_arr = array(
        'money_dollar' => 'Dollar',
        'money_yen' => 'Yen',
        'money_british' => 'British Pound',
    );

    //form create and edit
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.Devises')
            ->add('date', 'date', array(
            'format' => 'dd MMMM yyyy',
            'days' => range(1, 1),
            'years' => range(date('Y'), date('Y') + 3),
            'label' => ' ',
        ));

        foreach ($this->_money_arr as $field => $label)
            $formMapper->add($field, 'money', array('label' => 'form.' . $label, 'divisor' => 1));
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        foreach ($this->_money_arr as $field => $label)
            $datagridMapper->add($field, null, array('label' => 'filter.' . $label));
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('date', null, array('template' => 'ApplicationSonataDevisesBundle:CRUD:list_date.html.twig'));

        foreach ($this->_money_arr as $field => $label)
            $listMapper->add($field, null, array('label' => 'list.' . $label));
    }

    public function getFormTheme()
    {
        return array('ApplicationSonataDevisesBundle:Form:form_admin_fields.html.twig');
    }
}