<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A02TVAAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('date_piece', null, array(
                'label' => $this->getFieldLabel('date_piece'),
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => $this->date_format_datetime)
        )
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise')))
            ->add('taux_de_TVA', null, array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise')))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC')))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant')))
            ->add('paiement_devise', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
            ->add('paiement_date', null, array('label' =>
        $this->getFieldLabel('paiement_date'),
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime)
        )
            ->add('mois', 'date', array(
            'label' => $this->getFieldLabel('mois'),
            'days' => range(1, 1),
            'format' => 'dd MMMM yyyy',
        ))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise_id', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('taux_de_TVA', null, array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise')))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC')))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant')))
            ->add('paiement_devise', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
            ->add('paiement_date', null, array(
            'label' => $this->getFieldLabel('paiement_date'),
            'template' => $this->_bundle_name . ':CRUD:list_paiement_date.html.twig'
        ))
            ->add('mois', null, array('label' => $this->getFieldLabel('mois')))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    protected function getPaiement_dateFormValue($value)
    {
        return $this->dateFormValue($value);
    }
}