<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

abstract class AbstractTabsAdmin extends Admin
{
    public $dashboards = array();

    /**
     * @var string
     */
    protected $_bundle_name = 'ApplicationSonataClientOperationsBundle';
    protected $_form_label = '';


    public function __construct($code, $class, $baseControllerName){


        $month = $this->getRequest()->query->get('month', date('m'));

        $this->datagridValues = array(
            'date_piece' => array('value' => array('day' => 1, 'month' => intval($month), 'year' => date('Y'))),
        );

        return parent::__construct($code, $class, $baseControllerName);
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper){

        $filter = $this->getRequest()->query->get('filter');
        $this->_form_label = 'form';

        $formMapper->with($this->getFieldLabel())
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper){

        $this->_form_label = 'list';
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getFieldLabel($name = 'title'){

        return $this->_bundle_name.'.'.$this->_form_label.'.'.$this->getLabel().'.'.$name;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('client_id')
            ->add('date_piece');
            #->add('date_piece', 'doctrine_orm_date_range');
    }

    /**
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        switch ($name) {
            case 'list':
            case 'create':
            case 'edit':
            case 'delete':
            case 'batch':
                $filter = $this->getRequest()->query->get('filter');
                $parameters['filter']['client_id']['value'] = $filter['client_id']['value'];
                break;
        }
        return parent::generateUrl($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        if (!$this->request) {
            $this->setRequest(Request::createFromGlobals());
        }

        return $this->request;
    }

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch ($name) {
            case 'list':
                return $this->_bundle_name.':CRUD:list.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array($this->_bundle_name.':Form:form_admin_fields.html.twig');
    }
}