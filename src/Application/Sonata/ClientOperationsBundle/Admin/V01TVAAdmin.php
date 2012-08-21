<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V01TVAAdmin extends Admin
{
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $filter = $this->getRequest()->query->get('filter');

        $label = 'form.V01TVA';
        $formMapper->with($label . '.title')
            ->add('client_id', 'hidden', array('data' => $filter['client_id']['value']))
            ->add('tiers')
            ->add('no_TVA_tiers')
            ->add('date_piece')
            ->add('numero_piece')
            ->add('devise_id')
            ->add('montant_HT_en_devise')
            ->add('taux_de_TVA')
            ->add('montant_TVA_francaise')
            ->add('montant_TTC')
            ->add('paiement_montant')
            ->add('paiement_devise_id')
            ->add('paiement_date')
            ->add('mois')
            ->add('taux_de_change')
            ->add('HT')
            ->add('TVA')
            ->add('commentaires');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('id', null)
            ->add('tiers', null)
            ->add('no_TVA_tiers');
    }
}