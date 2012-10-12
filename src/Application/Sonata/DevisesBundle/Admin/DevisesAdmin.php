<?php

namespace Application\Sonata\DevisesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

use Knp\Menu\ItemInterface as MenuItemInterface;

class DevisesAdmin extends Admin
{
    /**
     * @var array
     */
    public $dashboards = array('Admin');
    protected $_bundle_name = 'ApplicationSonataDevisesBundle';

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );


    //create & edit form
    protected $_money_arr = array(
        'money_dollar' => array(
            'label' => 'Dollar',
        ),
        'money_yen' => array(
            'label' => 'Yen',
        ),
        'money_british' => array(
            'label' => 'British Pound',
        ),
    );


    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }

    /**
     * @param FormMapper $formMapper
     */
    //form create and edit
    protected function configureFormFields(FormMapper $formMapper)
    {
        $months = date('m');
        $years = date('Y');

        $formMapper
            ->with($this->_bundle_name . '.form.Devises')
            ->add('date', 'date', array(
            'format' => 'dd MMMM yyyy',
            'days' => range(1, 1),
            'months' => range($months, $months),
            'years' => range($years, $years),
            'label' => ' ',
        ));

        foreach ($this->_money_arr as $field => $labelData) {

            $formMapper->add($field, 'money', array(
                'label' => $this->_bundle_name . '.form.' . $labelData['label'],
                'precision' => 5,
                'divisor' => 1,
                'currency' => 'EUR',
            ));
        }
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array('ApplicationSonataDevisesBundle:Form:form_admin_fields.html.twig');
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'create':
            case 'edit':
                return $this->_bundle_name . ':CRUD:edit.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        if ($name == 'list') {
            $name = 'create';
        }

        return parent::generateUrl($name, $parameters, $absolute);
    }

    /**
     * @param string $action
     * @return array|void
     */
    public function getBreadcrumbs($action)
    {
        return parent::getBreadcrumbs('list');
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('list')
            ->remove('delete');
    }
}