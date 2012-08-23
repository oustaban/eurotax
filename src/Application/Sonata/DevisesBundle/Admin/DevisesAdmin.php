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
    public $dashboards = array('Admin');

    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );


    //create & edit form
    protected $_money_arr = array(
        'money_dollar' => array(
            'label' => 'Dollar',
            'currency' => 'USD',
        ),
        'money_yen' => array(
            'label' => 'Yen',
            'currency' => 'JPY',
        ),
        'money_british' => array(
            'label' => 'British Pound',
            'currency' => 'GBP',
        ),
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

        foreach ($this->_money_arr as $field => $labelData)
            $formMapper->add($field, 'money', array('label' => 'form.' . $labelData['label'], 'divisor' => 1, 'currency'=>$labelData['currency']));
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        foreach ($this->_money_arr as $field => $labelData)
            $datagridMapper->add($field, null, array('label' => 'filter.' . $labelData['label']));
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('date', null, array('template' => 'ApplicationSonataDevisesBundle:CRUD:list_date.html.twig'));

        foreach ($this->_money_arr as $field => $labelData)
            $listMapper->add($field, null, array('label' => 'list.' . $labelData['label']));
    }

    public function getFormTheme()
    {
        return array('ApplicationSonataDevisesBundle:Form:form_admin_fields.html.twig');
    }
}