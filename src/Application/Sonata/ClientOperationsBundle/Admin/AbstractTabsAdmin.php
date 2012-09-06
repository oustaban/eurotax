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

    protected $maxPerPage = 25;

    /**
     * @var string
     */
    protected $_bundle_name = 'ApplicationSonataClientOperationsBundle';
    protected $_form_label = '';
    public $month = '';
    public $query_month = '';
    public $year = '';
    public $client_id = '';


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

            $this->year = isset($filter['year']) ? $filter['year'] : $request->query->get('year', date('Y'));
            $this->month = $this->query_month = isset($filter['month']) ? $filter['month'] : $request->query->get('month', date('n'));

            if ($this->query_month == -1) {
                $this->month = date('n') - 1;
            }
        }
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);

        if ($this->query_month == -1) {

            $date = new \DateTime($this->year . '-' . $this->month . '-01');


            $where = array();
            $where[] = $query->getRootAlias() . '.date_piece IS NULL';
            $where[] = $query->getRootAlias() . ".date_piece = '" . $date->format('Y-m-d') . "'";

            $query->andWhere(implode(' OR ', $where));
        }

        $query->andWhere($query->getRootAlias() . '.client_id=' . $this->client_id);

        return $query;
    }

    /**
     * @param string $action
     * @param ItemInterface|null $menu
     * @return array
     */
    public function buildBreadcrumbs($action, MenuItemInterface $menu = null)
    {
        $result = parent::buildBreadcrumbs($action, $menu);
        unset($result['Dashboard']);
        return $result;
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

        if ($this->getLocking()) {
            $listMapper->add('id', null);
        } else {
            $listMapper->addIdentifier('id', null);
        }
        $listMapper->add('imports.id', null);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        $date_piece = $object->getDatePiece();

        if ($date_piece) {

            $this->month = $date_piece->format('m');
            $this->year = $date_piece->format('Y');

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
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        #$datagridMapper->add('client_id');
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
            'day' => date('d', $t),
            'month' => date('n', $t),
            'year' => date('Y', $t),
        );

        return $date;
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
}