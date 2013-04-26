<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Validator\ErrorElement;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Knp\Menu\ItemInterface as MenuItemInterface;

abstract class AbstractTabsAdmin extends Admin
{
    public $dashboards = array();

    /**
     * @var int
     */
    protected $maxPerPage = 10000000;

    /**
     * @var string
     */
    protected $_bundle_name = 'ApplicationSonataClientOperationsBundle';
    protected $_form_label = '';
    protected $_locking = false;
    public $month = '';
    public $query_month = '';
    public $year = '';
    public $client_id = '';
    public $date_filter_separator = '|';
    public $month_default = '';
    public $devise = array();
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';
    protected $_is_validate_import = false;
    protected $_index_import = 0;
    public $_show_all_operations = false;

    
    public $import_file_year,
    	$import_file_month;
    
    /**
     * @param string $code
     * @param string $class
     * @param string $baseControllerName
     */
    public function __construct($code, $class, $baseControllerName)
    {
        $this->getRequestParameters($this->getRequest());

        return parent::__construct($code, $class, $baseControllerName);
    }

    /**
     * @param $request
     */
    public function getRequestParameters($request)
    {
        $filter = $request->query->get('filter');

        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {

            $this->client_id = $this->client_id = $filter['client_id']['value'];

            $this->month_default = '-1' . date($this->date_filter_separator . 'Y', strtotime('-1 month'));
            //$this->month_default = date('m' . $this->date_filter_separator . 'Y', strtotime('-1 month'));
            $this->query_month = isset($filter['month']) ? $filter['month'] : $request->query->get('month', $this->month_default);
            
            if ($this->query_month == 'all'){
                $this->query_month = -1;
                $this->_show_all_operations = true;
            }
            

            list($this->month, $this->year) = $this->getQueryMonth($this->query_month);
        }
    }

    /**
     * @param $query_month
     * @return array
     */
    public function getQueryMonth($query_month)
    {
        if ($query_month == -1) {
            $month = date('n' . $this->date_filter_separator . 'Y', strtotime('-1 month'));
        }
        else {
            $month = $query_month;
        }

        return explode($this->date_filter_separator, $month);
    }

    /**
     * @return string
     */
    public function setQueryMonth()
    {
        if ($this->month_default != $this->query_month) {
            return $this->query_month;
        }

        return '';
    }


    /**
     * @return array
     */
    public function getBatchActions()
    {
        $batch = array();
        if (!$this->getLocking()) {
            $batch = parent::getBatchActions();
        }
        return $batch;
    }


    public function createQuery($context = 'list')
    {
        /** @var $query \Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery */
        $query = parent::createQuery($context);

        /** @var $builder \Doctrine\ORM\QueryBuilder */
        $builder = $query->getQueryBuilder();

        if (!$this->_show_all_operations){
            $form_month = $this->year . '-' . $this->month . '-01';
            $to_month = $this->year . '-' . $this->month . '-31';
            $monthField = 'mois';

            if ($this->query_month == -1) {
                $builder->orWhere($builder->getRootAlias() . '.'.$monthField.' IS NULL');
                $builder->orWhere($builder->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
            } else {
                $builder->andWhere($builder->getRootAlias() . '.'.$monthField.' BETWEEN :form_month AND :to_month');
            }

            $builder->setParameter(':form_month', $form_month);
            $builder->setParameter(':to_month', $to_month);
        }

        $builder->andWhere($builder->getRootAlias() . '.client_id=' . $this->client_id);
		//var_dump($query->__toString());
        return $query;
    }

    /**
     * @param string $action
     * @return array
     */
    public function getBreadcrumbs($action)
    {
        return null;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->_form_label = 'form';

        $id = $this->getRequest()->get($this->getIdParameter());
        
        $formMapper->with($this->getFieldLabel())
            ->add('client_id', 'hidden', array('data' => $this->client_id, 'attr' => array('class' => 'client_id')))
        	->add('is_new', 'hidden', array('data' => $id ? 0 : 1, 'mapped' => false, 'attr' => array('class' => 'is_new')));
        
        
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $this->_form_label = 'list';

        if (!$this->getLocking()) {
            $listMapper->add('_action', 'actions', array(
                'actions' => array(
                    'clone' => array('template' => 'ApplicationSonataClientOperationsBundle:CRUD:clone_action.html.twig'),
                    'delete' => array('template' => 'ApplicationSonataClientOperationsBundle:CRUD:delete_action.html.twig'),
                )
            ));
        }
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function postConfigureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('status.name', null, array('label' => 'ApplicationSonataClientOperationsBundle.list.status'))
            ->add('imports.id', null, array('label' => 'ApplicationSonataClientOperationsBundle.list.import_id'));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity */
        parent::validate($errorElement, $object);

        $value = $object->getDatePiece();
        if ($value) {
            $this->month = $value->format('m');
            $this->year = $value->format('Y');

            if ($this->getLocking()) {
                $errorElement->with('date_piece')->addViolation('Sorry with month is locked')->end();
            }
        }
    }


    /**
     * @return mixed
     */
    public function getLocking()
    {
        if ($this->_locking === false) {
            $this->_locking = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->client_id, 'month' => $this->month, 'year' => $this->year));
            $this->_locking = $this->_locking ? : 0;
        }

        return $this->_locking;
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
            case 'clone':
                $parameters['filter']['client_id']['value'] = $this->client_id;

                if ($month = $this->setQueryMonth()) {

                    $parameters['month'] = $month;
                }

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

            case 'create':
            case 'edit':
                return $this->_bundle_name . ':CRUD:form_abstract_tabs_edit.html.twig';
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
        $collection->add('locking', 'locking/{client_id}/{month}/{year}/{blocked}');
        $collection->add('clone', '{id}/clone');
        $collection->add('importList', 'import-list');
        $collection->add('importRemove', 'import-remove/{id}');
        $collection->add('declaration');
        $collection->add('attestation');
        $collection->add('exportExcel', 'export-excel');
        $collection->add('exportTransDeb', 'export-transdeb');
        $collection->add('RDevises', 'rdevises');
    }

    //customs fields

    /**
     * @param $field
     * @param $value
     * @return string
     */
    public function getFormValue($field, $value)
    {
        /** @var $fieldDescription \Sonata\DoctrineORMAdminBundle\Admin\FieldDescription */
        $fieldDescription = $this->getFormFieldDescription($field);
        $method = 'get' . ucfirst($field) . 'FormValue';
        $v = method_exists($this, $method) ? $this->$method($value) : $value;
        
        if (is_scalar($v)) {
            $v = trim($v);
        }
        if ($fieldDescription && $type = $fieldDescription->getType()) {
            $method = 'get' . ucfirst($type) . 'TypeFormValue';
            $v = method_exists($this, $method) ? $this->$method($v) : $v;
        }

        return $v;
    }

    
    public function getBooleanTypeFormValue($value) {
    	return (boolean) $value;
    }
    
    
    /**
     * @param $value
     * @return string
     */
    public function getMoneyTypeFormValue($value)
    {
        return $this->getNumberFormat($value);
    }


    /**
     * @param $value
     * @return mixed
     */
    public function getPercentTypeFormValue($value)
    {
        if ($value) {
            $value = $this->getNumberFormat($value * 100);
        }

        return $value;
    }

    /**
     * @param $value
     * @param int $precision
     * @return string
     */
    protected function getNumberFormat($value, $precision = 2)
    {
        return (double)$value;
    }

    /**
     * @return array
     */
    public function getFilterParameters()
    {
        $parameters = parent::getFilterParameters();
        unset($parameters['client_id']);

        $parameters['month'] = $this->query_month;
        $parameters['year'] = $this->year;

        return $parameters;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFormats()
    {
        return array();
    }

    /**
     * @param $value
     * @return array
     */
    public function dateFormValue($value)
    {
        if ($value) {
            $t = strtotime($value);

            if (!$t) {
                $t = \PHPExcel_Shared_Date::ExcelToPHP($value);
            }

            $value = date($this->date_format_php, $t);

            return $value;
        }
        return null;
    }

    /**
     * @param $value
     * @return array
     */
    protected function getDate_pieceFormValue($value)
    {
        return $this->dateFormValue($value);
    }


    /**
     * @param $value
     * @return array
     */
    protected function getPaiement_dateFormValue($value)
    {
        return $this->dateFormValue($value);
    }


    protected function getMoisFormValue($value)
    {
        $value = $this->dateFormValue($value);

        if ($value) {
            list($day, $month, $year) = explode('/', $value);

            return array(
                'day' => 1,
                'month' => intval($month),
                'year' => $year,
            );
        }
        return NULL;
    }

    /**
     * @param array $value
     */
    public function setDeviseList(array $value)
    {
        $this->devise = $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function getDevise($value)
    {
        $devise = strtolower($value);

        //  code => alias
        $value_assoc = array(
        );

        $devise = isset($value_assoc[$devise]) ? $value_assoc[$devise] : $devise;

        // $this->devise[alias] = object
        return isset($this->devise[$devise]) ? $this->devise[$devise]->getId() : $value;
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function getDeviseFormValue($value)
    {
        return $this->getDevise($value);
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function getPaiement_deviseFormValue($value)
    {
        return $this->getDevise($value);
    }

    /**
     * @param $value
     * @return int
     */
    protected function getDEBFormValue($value)
    {
        if ($value == 'OUI') {
            return 1;
        }
        return 0;
    }

    /**
     * @param $value
     * @return mixed
     */
    protected function getPays_id_origineFormValue($value)
    {
        return $value;
    }

    
    /**
     * @param $value
     * @return float
     */
    protected function getTaux_de_TVAFormValue($value)
    {
    	//workaround for this strange issue in the test server
    	//http://stackoverflow.com/questions/12965816/php-round-working-strange
    	return rtrim(number_format($value, 4), 0);
    }
    
    

    /**
     * @param bool $value
     */
    public function setValidateImport($value = true)
    {
        $this->_is_validate_import = $value;
    }

    /**
     * @return bool
     */
    protected function getValidateImport()
    {
        return $this->_is_validate_import;
    }

    /**
     * @param $value
     */
    public function setIndexImport($value)
    {
        $this->_index_import = $value;
    }

    /**
     * @return int
     */
    protected function getIndexImport()
    {
        return $this->_index_import;
    }
}