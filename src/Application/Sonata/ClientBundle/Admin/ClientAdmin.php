<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Application\Form\Type\LocationPostalType;
use Application\Form\Type\LocationFacturationType;

class ClientAdmin extends Admin
{
    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected $_fields = array(
        'user',
        'nom',
        'nature_du_client',
        'raison_sociale',
        'location_postal',
        'date_debut_mission',
        'activite',
        'mode_denregistrement',
        'avance_contractuelle',
        'siret',
        'periodicite_facturation',
        'num_dossier_fiscal',
        'taxe_additionnelle',
        'periodicite_CA3',
        'location_facturation',
        'center_des_impots',
        /*TODO*/
        'date_fin_mission',
        'libelle_avance',

        /*TODO*/
        'date_de_depot_id',
        'N_TVA_CEE',

        /*TODO*/
        'niveau_dobligation_id',
    );


    protected $_fields_list = array(
        'raison_sociale',
        'nature_du_client',
        'date_de_depot_id',
        'date_debut_mission',
        'date_fin_mission',
        'user',
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->with('form.client');

        foreach($this->_fields as $field){

            switch($field){

                case 'date_de_depot_id':
                    $formMapper->add($field, 'choice', array(
                        'label' => 'form.'.$field,
                        'choices' => array(15,19, 24, 31)
                    ));
                break;

                case 'niveau_dobligation_id':
                    $formMapper->add($field, 'choice', array(
                    'label' => 'form.'.$field,
                        'choices' => array(0, 1, 4)
                    ));
                break;

                case 'location_facturation':
                 $formMapper->add($field, new LocationFacturationType(), array(
                        'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                        'label' => 'Location',
                 ), array('type'=>'location'));

                break;
                case 'location_postal':
                    $formMapper->add($field, new LocationPostalType(), array(
                        'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                        'label' => 'Location',
                    ),
                    array('type'=>'postal'));
                break;

                case 'date_debut_mission':
                case 'date_fin_mission':
                    $formMapper->add($field, 'date', array('label' => 'form.'.$field));

                    /*$formMapper->add($field,'date',
                        array(
                            'attr' => array('class' => 'datapicker'),
                            'widget' => 'single_text',
                            //'input' => 'datetime',
                            'format' => 'dd/MM/yyyy'
                        )
                    );*/
                 break;

                default:
                    $formMapper->add($field, null, array('label' => 'form.'.$field));
                break;
            }
            //$formMapper->add('location_postal', new LocationPostalType(), array(), array('type'=>'text'));
        }
    }

    //filter form
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        foreach($this->_fields_list as $field){
            $datagridMapper->add($field, null, array('label' => 'filter.'.$field));
        }
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

    /**
     * @param string $name
     *
     * @return null|string
     */
    public function getTemplate($name)
    {
        switch($name){
            case 'edit':
            case 'create':
                return 'ApplicationSonataClientBundle:CRUD:edit.html.twig';
                break;
        }

        return parent::getTemplate($name);
    }


}