<?php

namespace Application\Sonata\ClientBundle\Admin;


use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Application\Form\Type\LocationType;
use Sonata\AdminBundle\Route\RouteCollection;

use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientBundle\Entity\ClientAlert;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class CoordonneesAdmin extends Admin
{
    /**
     * @var int
     */
    protected $maxPerPage = 100000;

    /**
     * @var string
     */
    protected $_prefix_label = 'coordonnees';


    /**
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'orders'
    );


    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with($this->getFieldLabel('title'))

            ->add('orders', 'hidden', array(
            'data' => null,
            'label' => $this->getFieldLabel('orders')
        ))
            ->add('nom', null, array('label' => $this->getFieldLabel('nom')))
            ->add('location', new LocationType(), array(
                'data_class' => 'Application\Sonata\ClientBundle\Entity\Coordonnees',
            ),
            array('type' => 'location'))
            ->add('no_de_compte', null, array('label' => $this->getFieldLabel('no_de_compte')))
            ->add('code_swift', null, array('label' => $this->getFieldLabel('code_swift')))
            ->add('IBAN', null, array('label' => $this->getFieldLabel('IBAN')));
    }

    /**
     * @return array
     */
    public function getFilterParameters()
    {
        $filter = $this->request->query->get('filter', array());
        unset($filter['_sort_by'], $filter['_sort_order']);

        $this->request->query->set('filter', $filter);
        $parameters = parent::getFilterParameters();

        return $parameters;
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('nom', null, array('label' => $this->getFieldLabel('nom')))
            ->add('code_swift', null, array('label' => $this->getFieldLabel('code_swift')))
            ->add('pays.name', null, array('label' => $this->getFieldLabel('pays_id')))
            ->add('IBAN', null, array('label' => $this->getFieldLabel('IBAN')))
            ->add('pays.sepa', null, array('label' => $this->getFieldLabel('SEPA')));
    }


    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientBundle\Entity\Contact */
        parent::validate($errorElement, $object);

        $this->_setupAlerts($errorElement, $object);
    }

    /**
     * @param $errorElement
     * @param $object \Application\Sonata\ClientBundle\Entity\Coordonnees
     */
    protected function _setupAlerts($errorElement, $object)
    {
        /** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = $this->getConfigurationPool()->getContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        /* @var $tab \Application\Sonata\ClientBundle\Entity\ListClientTabs */
        $tab = $em->getRepository('ApplicationSonataClientBundle:ListClientTabs')->findOneByAlias('coordinates');

        $em->getRepository('ApplicationSonataClientBundle:ClientAlert')
            ->createQueryBuilder('c')
            ->delete()
            ->where('c.client = :client')
            ->andWhere('c.tabs = :tab')
            ->setParameters(array(
            ':client' => $object->getClient(),
            ':tab' => $tab,
        ))->getQuery()->execute();


        $value = $object->getIBAN();
        if (0) {
            $alert = new ClientAlert();
            $alert->setClient($object->getClient());
            $alert->setTabs($tab);
            $alert->setIsBlocked(false);
            $alert->setText('Manque coordonnées bancaires pour remboursement TVA');

            $em->persist($alert);
        }
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
    	parent::configureRoutes($collection);
        $collection->add('sortable');
    }
}

