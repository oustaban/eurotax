<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;


abstract class AbstractTabsAdmin extends Admin
{
    public $dashboards = array();
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';
    public $client_id = '';
    protected $_client;
    protected $_generate_url = true;

    protected $_bundle_name = 'ApplicationSonataClientBundle';
    protected $_form_label = '';
    protected $_country_eu = null;


    protected $_is_validate_import = false;
    
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
     * @return array
     */
    public function getBatchActions()
    {

        return array();
    }

    /**
     * @param $request
     */
    public function getRequestParameters($request)
    {
        $filter = $request->query->get('filter');
        if (!empty($filter['client_id']) && !empty($filter['client_id']['value'])) {
            $this->client_id = $filter['client_id']['value'];
            $this->setClient($this->client_id);
        }
    }

    /**
     * @param $client_id
     */
    protected function setClient($client_id)
    {
        /** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        $this->_client = $doctrine->getManager()->getRepository('ApplicationSonataClientBundle:Client')->find($client_id);
    }

    /**
     * @return mixed
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * @param string $context
     * @return \Sonata\AdminBundle\Datagrid\ProxyQueryInterface
     */
    public function createQuery($context = 'list')
    {
        /** @var $query \Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery */
        $query = parent::createQuery($context);

        /** @var $builder \Doctrine\ORM\QueryBuilder */
        $builder = $query->getQueryBuilder();
        $builder->andWhere($builder->getRootAlias() . '.client=:client')
            ->setParameter(':client', $this->getClient());

        return $query;
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
     * {@inheritdoc}
     */
    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        if ($this->_generate_url) {
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
        }
        return parent::generateUrl($name, $parameters, $absolute);
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
     * @return string
     */
    protected function getFieldLabel($name = 'title')
    {
        return $this->_form_label . '.' . str_replace('_', '', $this->getLabel()) . '.' . $name;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $this->_form_label = 'form';

        parent::configureFormFields($formMapper);

        $formMapper->with(' ');
        $formMapper->add('client', null, array('label' => ' ', 'data' => $this->getClient(), 'attr' => array('class' => 'client_id important_hidden'),));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $this->_form_label = 'list';

        $listMapper->add('_action', 'actions', array(
            'actions' => array(
                'delete' => array(),
            )
        ));

        parent::configureListFields($listMapper);
    }

    /**
     * {@inheritdoc}
     */
    protected function configureRoutes(RouteCollection $collection)
    {
    	$collection->add('initialImport', 'initial-import');
    }
    
    
    
    /**
     * @return array|null
     */
    protected function getListCountryEU()
    {
        if (!$this->_country_eu) {
            $this->_country_eu = \Application\Sonata\ClientBundle\Entity\ListCountries::getCountryEUCode();
        }

        return $this->_country_eu;
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
    
    
}