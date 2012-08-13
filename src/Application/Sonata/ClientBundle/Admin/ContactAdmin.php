<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;

class ContactAdmin extends Admin
{

    protected $datagridValues = array(
        'client' => array(
            'type'=>1,
            'value' => 1,
        ),
    );

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected $_fields = array(
        'client',
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
        'client',
        'civilite',
        'nom',
        'prenom',
        'telephone_1',
        'telephone_2',
        'fax',
        'email',
        'affichage_facture',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('form.contact.contact');

        foreach($this->_fields as $field){

            $label = 'form.contact.'.$field;

            switch($field){
                case 'email':
                    $formMapper->add($field, 'email', array(
                        'label' => $label
                    ));
                break;

             /*   case 'client':
                    $formMapper->add($field, 'hidden', array(
                        'label' => $label
                    ));
                break;*/

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
        $datagridMapper->add('client', null, array('operator_type' => 'hidden'));

        /*foreach($this->_fields_list as $field){
            $datagridMapper->add($field, null, array('label' => 'filter.'.$field));
        }*/
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');

        foreach($this->_fields_list as $field){
            $listMapper->add($field, null, array('label' => 'list.'.$field));
        }
    }


//    /**
//     * @param string $name
//     *
//     * @return null|string
//     */
//    public function getTemplate($name)
//    {
//        switch($name){
//            case 'edit':
//            case 'create':
//                return 'ApplicationSonataClientBundle:CRUD:edit.html.twig';
//                break;
//        }
//
//        return parent::getTemplate($name);
//    }
}