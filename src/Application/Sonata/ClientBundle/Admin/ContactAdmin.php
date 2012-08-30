<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class ContactAdmin extends Admin
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

        $filter = $this->getRequest()->query->get('filter');

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

                case 'telephone_1':
                case 'telephone_2':
                    $formMapper->add($field, null, array('label' => $label, 'required' => false,));

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