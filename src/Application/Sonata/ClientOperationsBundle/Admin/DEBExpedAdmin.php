<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class DEBExpedAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('n_ligne', null, array('label' => $this->getFieldLabel('n_ligne')))
            ->add('date_piece', null, array('label' => $this->getFieldLabel('date_piece')))
            ->add('nomenclature', null, array('label' => $this->getFieldLabel('nomenclature')))
            ->add('pays_id_destination', 'country', array('label' => $this->getFieldLabel('pays_id_destination')))
            ->add('valeur_fiscale', null, array('label' => $this->getFieldLabel('valeur_fiscale')))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('valeur_statistique', null, array('label' => $this->getFieldLabel('valeur_statistique')))
            ->add('masse_mette', null, array('label' => $this->getFieldLabel('masse_mette')))
            ->add('unites_supplementaires', null, array('label' => $this->getFieldLabel('unites_supplementaires')))
            ->add('nature_transaction', null, array('label' => $this->getFieldLabel('nature_transaction')))
            ->add('conditions_livraison', null, array('label' => $this->getFieldLabel('conditions_livraison')))
            ->add('mode_transport', null, array('label' => $this->getFieldLabel('mode_transport')))
            ->add('departement', null, array('label' => $this->getFieldLabel('departement')))
            ->add('pays_id_origine', 'country', array('label' => $this->getFieldLabel('pays_id_origine')))
            ->add('CEE', null, array('label' => $this->getFieldLabel('CEE')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('n_ligne', null, array('label' => $this->getFieldLabel('n_ligne')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))->add('nomenclature', null, array('label' => $this->getFieldLabel('nomenclature')))
            ->add('pays_id_destination', null, array('label' => $this->getFieldLabel('pays_id_destination')))
            ->add('valeur_fiscale', null, array('label' => $this->getFieldLabel('valeur_fiscale')))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('valeur_statistique', null, array('label' => $this->getFieldLabel('valeur_statistique')))
            ->add('masse_mette', null, array('label' => $this->getFieldLabel('masse_mette')))
            ->add('unites_supplementaires', null, array('label' => $this->getFieldLabel('unites_supplementaires')))
            ->add('nature_transaction', null, array('label' => $this->getFieldLabel('nature_transaction')))
            ->add('conditions_livraison', null, array('label' => $this->getFieldLabel('conditions_livraison')))
            ->add('mode_transport', null, array('label' => $this->getFieldLabel('mode_transport')))
            ->add('departement', null, array('label' => $this->getFieldLabel('departement')))
            ->add('pays_id_origine', 'country', array('label' => $this->getFieldLabel('pays_id_origine')))
            ->add('CEE', null, array('label' => $this->getFieldLabel('CEE')));
    }


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('client_id');
    }
}