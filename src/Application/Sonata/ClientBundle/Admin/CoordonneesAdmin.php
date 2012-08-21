<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Application\Form\Type\LocationType;
use Sonata\AdminBundle\Route\RouteCollection;

class CoordonneesAdmin extends AbstractTabsAdmin
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

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.' . $this->_prefix_label . '.';
        $formMapper->with($label . 'title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('orders', 'hidden', array(
            'data' => null,
            'label' => $label . 'orders'
        ))
            ->add('nom', null, array('label' => $label . 'nom'))
            ->add('location', new LocationType(), array(
                'data_class' => 'Application\Sonata\ClientBundle\Entity\Coordonnees',
            ),
            array('type' => 'location'))
            ->add('no_de_compte', null, array('label' => $label . 'no_de_compte'))
            ->add('code_swift', null, array('label' => $label . 'code_swift'))
            ->add('IBAN', null, array('label' => $label . 'IBAN'))
            ->add('SEPA', null, array('label' => $label . 'SEPA'));

    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        $label = 'list.' . $this->_prefix_label . '.';
        $listMapper
            ->add('nom', null, array('label' => $label . 'nom'))
            ->add('no_de_compte', null, array('label' => $label . 'no_de_compte'))
            ->add('code_swift', null, array('label' => $label . 'code_swift'));
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection){

        $collection->add('sortable');
    }
}

