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
    public $month = '';
    public $query_month = '';
    public $year = '';
    public $client_id = '';
    public $date_filter_separator = '|';
    public $month_default = '';
    public $devise = array();
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';

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

            $this->month_default = '-1' . date($this->date_filter_separator . 'Y');

            $this->query_month = isset($filter['month']) ? $filter['month'] : $request->query->get('month', $this->month_default);

            list($this->month, $this->year) = $this->getQueryMonth($this->query_month);
        }
    }


    /**
     * @param $query_month
     * @return array
     */
    public function getQueryMonth($query_month)
    {
        $year = substr($query_month, -4);
        $month = $query_month == -1 ? (date('n') - 1) . $this->date_filter_separator . $year : $query_month;

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
        $query = parent::createQuery($context);

        $form_date_piece = $this->year . '-' . $this->month . '-01';
        $to_date_piece = $this->year . '-' . $this->month . '-31';

        if ($this->query_month == -1) {
            $query->orWhere($query->getRootAlias() . '.date_piece IS NULL');
            $query->orWhere($query->getRootAlias() . '.date_piece BETWEEN :form_date_piece AND :to_date_piece');
        } else {
            $query->andWhere($query->getRootAlias() . '.date_piece BETWEEN :form_date_piece AND :to_date_piece');
        }
        $query->setParameter(':form_date_piece', $form_date_piece);
        $query->setParameter(':to_date_piece', $to_date_piece);

        $query->andWhere($query->getRootAlias() . '.client_id=' . $this->client_id);

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

        $formMapper->with($this->getFieldLabel())
            ->add('client_id', 'hidden', array('data' => $this->client_id));
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
                )
            ));
        }

        $listMapper->add('imports.id', null);
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
                $errorElement->addViolation('Sorry with month is locked');
            }
        }
    }

    /**
     * @return mixed
     */
    public function getLocking()
    {
        $locking = $this->getConfigurationPool()->getContainer()->get('doctrine')->getRepository('ApplicationSonataClientOperationsBundle:Locking')->findOneBy(array('client_id' => $this->client_id, 'month' => $this->month, 'year' => $this->year));
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

        $type = $fieldDescription->getType();

        $method = 'get' . ucfirst($field) . 'FormValue';
        $v = method_exists($this, $method) ? $this->$method($value) : $value;
        if (is_scalar($v)) {
            $v = trim($v);
        }

        $method = 'get' . ucfirst($type) . 'TypeFormValue';
        $v = method_exists($this, $method) ? $this->$method($v) : $v;

        return $v;
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
     * @return float
     */
    public function getNumberRound($value, $precision = 2)
    {
        return round($value, $precision);
    }

    /**
     * @param $value
     * @param int $precision
     * @return string
     */
    protected function getNumberFormat($value, $precision = 2)
    {
        if ($value) {
            $value = number_format($value, $precision, ',', '');
        }
        return $value;
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
        $t = strtotime($value);

        if (!$t) {
            $t = \PHPExcel_Shared_Date::ExcelToPHP($value);
        }

        $value = date($this->date_format_php, $t);

        return $value;
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
        $value = strtolower($value);

        $value_assoc = array(
            'eur' => 'euro',
            'usd' => 'dollar',
            'jpy' => 'yen',
            'gbr' => 'british',
            'nok' => 'norwegian_krone',
            'dkk' => 'danish_krone',
            'sek' => 'swedish_krone',
            'chf' => 'swiss_franc',
        );

        $value = isset($value_assoc[$value]) ? $value_assoc[$value] : $value;

        return isset($this->devise[$value]) ? $this->devise[$value]->getId() : '';
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

}