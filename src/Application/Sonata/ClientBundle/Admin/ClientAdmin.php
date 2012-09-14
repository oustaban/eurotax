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
    public $dashboards = array('Admin');
    public $date_format_datetime = 'dd/MM/yyyy';
    public $date_format_php = 'd/m/Y';

    protected $_bundle_name = 'ApplicationSonataClientBundle';

    protected $_fields_list = array(
        'raison_sociale' => array(),
        'nature_du_client' => array(),
        'date_de_depot_id' => array(),
        'date_debut_mission' => array('template' => 'ApplicationSonataClientBundle:CRUD:list_date_debut_mission.html.twig'),
        'date_fin_mission' => array('template' => 'ApplicationSonataClientBundle:CRUD:list_date_fin_mission.html.twig'),
        'user' => array(),
    );

    /**
     * @return array
     */
    public function getBatchActions()
    {

        return array();
    }

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.client')
            ->add('user', null, array('label' => 'form.user',))
            ->add('nom', null, array('label' => 'form.nom'))
            ->add('nature_du_client', null, array('label' => 'form.nature_du_client'))
            ->add('raison_sociale', null, array('label' => 'form.raison_sociale', 'required' => false,))
            ->add('location_postal', new LocationPostalType(), array(
                'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
                'label' => 'Location',
                'required' => false,
            ),
            array('type' => 'location'))
            ->add('activite', null, array('label' => 'form.activite', 'required' => false,))
            ->add('date_debut_mission', 'date', array(
            'label' => 'form.date_debut_mission',
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('mode_denregistrement', null, array('label' => 'form.mode_denregistrement'))
            ->add('avance_contractuelle', null, array('label' => 'form.avance_contractuelle', 'required' => false,))
            ->add('siret', null, array('label' => 'form.siret', 'required' => false,))
            ->add('periodicite_facturation', null, array('label' => 'form.periodicite_facturation'))
            ->add('num_dossier_fiscal', null, array('label' => 'form.num_dossier_fiscal', 'required' => false,))
            ->add('taxe_additionnelle', 'choice',
            array('expanded' => true,
                'label' => 'form.taxe_additionnelle',
                'choices' => array(1 => 'Oui', 0 => 'Non'),
                'required' => false,)
        )
            ->add('periodicite_CA3', null, array('label' => 'form.periodicite_CA3'))
            ->add('center_des_impots', null, array('label' => 'form.center_des_impots'))
            ->with(' ')
            ->add('location_facturation', new LocationFacturationType(), array(
            'data_class' => 'Application\Sonata\ClientBundle\Entity\Client',
            'label' => 'Location',
            'required' => false,
        ), array('type' => 'location'))
            ->add('date_fin_mission', 'date', array(
            'label' => 'form.date_fin_mission',
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime
        ))
            ->add('libelle_avance', null, array('label' => 'form.libelle_avance', 'required' => false,))
            ->add('date_de_depot_id', 'choice', array(
            'label' => 'form.date_de_depot_id',
            'choices' => array(15, 19, 24, 31)
        ))
            ->add('N_TVA_CEE', null, array('label' => 'form.N_TVA_CEE', 'required' => false,))
            ->add('niveau_dobligation_id', 'choice', array(
            'label' => 'form.niveau_dobligation_id',
            'choices' => array(0, 1, 4)
        ));
    }

    //filter form
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        foreach ($this->_fields_list as $field => $options) {
            $options['label'] = 'filter.' . $field;
            $datagridMapper->add($field, null, $options);
        }
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id');

        foreach ($this->_fields_list as $field => $options) {
            $options['label'] = 'filter.' . $field;
            $listMapper->add($field, null, $options);
        }
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
}