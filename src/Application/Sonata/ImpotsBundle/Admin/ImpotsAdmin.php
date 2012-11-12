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
    public $dashboards = array('Admin');

    protected $_bundle_name = 'ApplicationSonataImpotsBundle';

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'nom'
    );

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }

    //create & edit form
    protected function configureFormFields(FormMapper $formMapper)
    {
        $label = 'form.';
        $formMapper
            ->add('nom', null, array('label' => $label . 'nom'))
            ->add('location', new LocationType(), array(
            'data_class' => 'Application\Sonata\ImpotsBundle\Entity\Impots',
        ), array('type' => 'location'))
            ->add('nom_de_la_banque', null, array('label' => $label . 'nom_de_la_banque'))
            ->add('no_de_compte', null, array('label' => $label . 'no_de_compte'))
            ->add('code_swift', null, array('label' => $label . 'code_swift'))
            ->add('IBAN', null, array('label' => $label . 'IBAN'))
            ->add('SEPA', null, array('label' => $label . 'SEPA'));
    }

    //filter form
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $label = 'filter.';
        $datagridMapper
            ->add('nom', null, array('label' => $label . 'nom'))
            ->add('nom_de_la_banque', null, array('label' => $label . 'nom_de_la_banque'))
            ->add('code_postal', null, array('label' => $label . 'code_postal'))
            ->add('ville', null, array('label' => $label . 'ville'))
            ->add('pays', null, array('label' => $label . 'pays'))
            ->add('no_de_compte', null, array('label' => $label . 'no_de_compte'))
            ->add('code_swift', null, array('label' => $label . 'code_swift'))
            ->add('IBAN', null, array('label' => $label . 'IBAN'))
            ->add('SEPA', null, array('label' => $label . 'SEPA'));
    }

    //list
    protected function configureListFields(ListMapper $listMapper)
    {
        $label = 'list.';
        $listMapper
            ->add('id', null, array('label' => $label . 'id'))
            ->add('nom', null, array('label' => $label . 'nom'))
            ->add('nom_de_la_banque', null, array('label' => $label . 'nom_de_la_banque'))
            ->add('adresse_1', null, array('label' => $label . 'adresse_1'))
            ->add('adresse_2', null, array('label' => $label . 'adresse_2'))
            ->add('code_postal', null, array('label' => $label . 'code_postal'))
            ->add('ville', null, array('label' => $label . 'ville'))
            ->add('pays.name', null, array('label' => $label . 'pays'))
            ->add('no_de_compte', null, array('label' => $label . 'no_de_compte'))
            ->add('code_swift', null, array('label' => $label . 'code_swift'))
            ->add('IBAN', null, array('label' => $label . 'IBAN'))
            ->add('SEPA', null, array('label' => $label . 'SEPA'));
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array($this->_bundle_name . ':Form:form_admin_fields.html.twig');
    }

    /**
     * @param string $action
     * @return array
     */
    public function getBreadcrumbs($action)
    {
        $res = parent::getBreadcrumbs($action);
        array_shift($res);
        return $res;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        $template = parent::getTemplate($name);
        switch ($name) {
            case 'layout':
                $template = $this->_bundle_name . '::standard_layout.html.twig';
                break;
            case 'create':
            case 'edit':
                return $this->_bundle_name . ':CRUD:edit.html.twig';
        }
        return $template;
    }
}