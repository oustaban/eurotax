<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;

abstract class AbstractTabsAdmin extends Admin
{
    public $dashboards = array();

    protected $maxPerPage = 25;

    /**
     * @var string
     */
    protected $_bundle_name = 'ApplicationSonataClientOperationsBundle';
    protected $_form_label = '';
    protected $_month = '';
    protected $_year = '';
    protected $_client_id = '';


    public function __construct($code, $class, $baseControllerName)
    {
        $filter = $this->getRequest()->query->get('filter');
        $this->_client_id = $filter['client_id']['value'];
        $this->_month = $this->getRequest()->query->get('month', date('m'));
        $this->_year = date('Y');

        $this->datagridValues = array(
            'date_piece' => array('value' => array('day' => 1, 'month' => intval($this->_month), 'year' => $this->_year)),
        );

        return parent::__construct($code, $class, $baseControllerName);
    }

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
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->_form_label = 'form';

        $formMapper->with($this->getFieldLabel())
            ->add('client_id', 'hidden', array('data' => $this->_client_id));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $this->_form_label = 'list';

        if ($this->getLocking()) {
            $listMapper->add('id', null);
        } else {
            $listMapper->addIdentifier('id', null);
        }
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $date_piece = $object->getDatePiece();


        if ($date_piece) {

            $this->_month = $date_piece->format('m');
            $this->_year = $date_piece->format('Y');

            if ($this->getLocking()) {
                $errorElement->addViolation('Sorry with month is locked');
            }
        }
    }

    /**
     * @return mixed
     */
    protected function getLocking()
    {
        $locking = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->_client_id, 'month' => $this->_month, 'year' => $this->_year));
        return $locking ? : 0;
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getFieldLabel($name = 'title')
    {

        return $this->_bundle_name . '.' . $this->_form_label . '.' . str_replace('_', '', $this->getLabel()) . '.' . $name;
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('client_id')
            ->add('date_piece');
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
                $parameters['filter']['client_id']['value'] = $this->_client_id;
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
                return $this->_bundle_name . ':CRUD:list.html.twig';
        }

        return parent::getTemplate($name);
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array($this->_bundle_name . ':Form:form_admin_fields.html.twig');
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('blank');
        $collection->add('import');
        $collection->add('locking', 'locking/{client_id}/{month}/{blocked}');
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getFormValue($field, $value)
    {
        $method = 'get' . ucfirst($field) . 'FormValue';
        return method_exists($this, $method) ? $this->$method($value) : $value;
    }

    /**
     * @param $value
     * @return array
     */
    public function dateFormValue($value)
    {
        $t = strtotime($value);

        $date = array(
            'day' => 1,
            'month' => intval(date('m', $t)),
            'year' => date('Y', $t),
        );

        return $date;
    }

    /**
     * @return array
     */
    public function getFilterParameters(){

        $parameters = parent::getFilterParameters();
        unset($parameters['client_id']);

        return $parameters;
    }
}