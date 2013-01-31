<?php

namespace Application\Sonata\DevisesBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;


use Knp\Menu\ItemInterface as MenuItemInterface;

use Application\Sonata\ClientBundle\DataFixtures\ORM\LoadListDevisesData;
use Application\Sonata\ClientBundle\Entity\ListDevises;

class DevisesAdmin extends Admin
{
    /**
     * @var array
     */
    public $dashboards = array('Admin');
    protected $_bundle_name = 'ApplicationSonataDevisesBundle';
    protected $_current_devises = null;

    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC',
        '_sort_by' => 'date'
    );

    /**
     * @return array
     */
    public function getBatchActions()
    {
        return array();
    }

    public function getCurrentYearMonth() {
        return date('Y-m', strtotime('now' . (date('d') > 24 ? ' +8 days' : '')));
    }

    /**
     * @param FormMapper $formMapper
     */
    //form create and edit
    protected function configureFormFields(FormMapper $formMapper)
    {
        /** @var $entity_devises \Application\Sonata\DevisesBundle\Entity\Devises */
        $entity_devises = $this->getEntityDevises();

        list($create_years, $create_months) = explode('-', $this->getCurrentYearMonth());

        $this->_current_devises = $this->getCurrentDevises($create_years . '-' . $create_months);

        $months = $entity_devises ? $entity_devises->getDate()->format('n') : $create_months;
        $years = $entity_devises ? $entity_devises->getDate()->format('Y') : $create_years;

        /* @var $securityContext SecurityContext */
        $securityContext = \AppKernel::getStaticContainer()->get('security.context');
        $disables = array();
        if (!$securityContext->isGranted('ROLE_EDIT_DEVISES_WRITE') || !($months == $create_months && $years == $create_years)) {
            $disables = array('disabled' => true);
        }

        $formMapper->with($this->_bundle_name . '.form.Devises')

            ->add('date_change', 'choice', array(
            'label' => $this->_bundle_name . '.form.DateChange',
            'choices' => $this->getDateChange(),
            'data' => $entity_devises ? $this->generateObjectUrl('edit', $entity_devises) : $this->generateUrl('create'),
            'attr' => array('style' => 'width:auto'),
        ));

        $devisesList = LoadListDevisesData::getStaticList();
        unset($devisesList[ListDevises::Device]);
        ksort($devisesList);

        foreach ($devisesList as $field => $labelData) {

            /** @var $entity  \Application\Sonata\ClientBundle\Entity\ListDevises */
            $formMapper->add('money' . $field, 'money', array(
                'decorator' => function ($pattern) use ($field){
                    return $pattern . ' ' . $field;
                },
                'label' => '1 euro',
                'precision' => 5,
                'divisor' => 1,
                'currency' => false,
            ) + $disables);
        }
    }

    /**
     * @return array
     */
    protected function getDateChange()
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        $devises = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findBy(array(), array('date' => 'DESC'));
        $rows = array();

        if (!$this->_current_devises) {
            $rows[$this->generateUrl('create')] = ucwords($this->datefmtFormatFilter(new \DateTime($this->getCurrentYearMonth().'-01'), 'YYYY MMMM'));
        }

        foreach ($devises as $value) {

            /** @var $value \Application\Sonata\DevisesBundle\Entity\Devises */
            $rows[$this->generateObjectUrl('edit', $value)] = ucwords($this->datefmtFormatFilter($value->getDate(), 'YYYY MMMM'));
        }

        return $rows;
    }

    /**
     * @param $datetime
     * @param null $format
     * @return string
     */
    public function datefmtFormatFilter($datetime, $format = null)
    {
        $dateFormat = is_int($format) ? $format : \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($format) ? $format : null;

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            $dateFormat,
            $timeFormat,
            null,
            $calendar,
            $pattern
        );
        $formatter->setLenient(false);
        $timestamp = $datetime->getTimestamp();

        return $formatter->format($timestamp);
    }

    /**
     * @return array
     */
    protected function getEntityDevises()
    {
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();
        return $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneById($this->getRequest()->get($this->getIdParameter()));
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getCurrentDevises($value)
    {
        $date = new \DateTime($value . '-01');

        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();
        return $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($date);
    }

    /**
     * @return array
     */
    public function getFormTheme()
    {
        return array($this->_bundle_name . ':Form:form_admin_fields.html.twig');
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