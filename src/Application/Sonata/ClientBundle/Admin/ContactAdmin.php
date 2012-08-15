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

class ContactAdmin extends AbstractTabsAdmin
{

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
        $formMapper->with('form.contact.title');

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
                    $filter = Request::createFromGlobals()->query->get('filter');
                    $formMapper->add($field, 'hidden', array(
                        'data' => $filter['client_id']['value'],
                    ));
                    break;

                default:
                    $formMapper->add($field, null, array('label' => $label));
                    break;
            }
        }
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
    }

}