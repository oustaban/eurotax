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

class ContactAdmin extends Admin
{

    /* protected $datagridValues = array(
        'client' => array(
            'type'=>1,
            'value' => 1,
        ),
    );*/

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected $_fields = array(
        'client_id',
        'civilite',
        'nom',
        'prenom',
        'telephone_1',
        'telephone_2',
        'fax',
        'email',
        'affichage_facture',
    );


    protected $_fields_list = array(
        'nom',
        'prenom',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('form.contact.contact');

        $request = Request::createFromGlobals();
        $filter = $request->query->get('filter');

        foreach ($this->_fields as $field) {

            $label = 'form.contact.' . $field;

            switch ($field) {
                case 'email':
                    $formMapper->add($field, 'email', array(
                        'label' => $label
                    ));
                    break;

                case 'client_id':
                    $client_id = NULL;
                    if (!empty($filter[$field]) && $client = $filter[$field])
                        $client_id = $client['value'];

                    $formMapper->add($field, 'hidden', array(
                        'data' => $client_id,
                    ));
                    break;

                default:
                    $formMapper->add($field, null, array('label' => $label));
                    break;
            }
        }
    }

    //filter form
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('client_id');
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null);

        foreach ($this->_fields_list as $field) {
            $listMapper->add($field, null, array('label' => 'list.contact.' . $field));
        }

        /*$listMapper->add('_action', 'actions', array(
            'actions' => array(
                'view' => array(),
                'edit' => array(),
                'delete' => array(),
            )
        ));*/
    }

    /**
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */

    public function generateUrl($name, array $parameters = array(), $absolute = false)
    {
        switch ($name) {
            case 'list':
                $name = 'create';
            case 'create':
            case 'edit':
            case 'delete':
            case 'batch':
                $filter = Request::createFromGlobals()->query->get('filter');
                $parameters['filter']['client_id']['value'] = $filter['client_id']['value'];
                break;
        }
        return parent::generateUrl($name, $parameters, $absolute);
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
                return 'ApplicationSonataClientBundle:CRUD:list.html.twig';
        }
        return parent::getTemplate($name);
    }
}