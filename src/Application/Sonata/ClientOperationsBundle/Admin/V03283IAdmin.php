<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V03283IAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('no_TVA_tiers', null, array('label' => $this->getFieldLabel('no_TVA_tiers')))
            ->add('date_piece', null, array(
                'label' => $this->getFieldLabel('date_piece'),
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => 'MM/dd/yyyy')
        )
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise_id', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', null, array('label' => $this->getFieldLabel('montant_HT_en_devise')))
            ->add('mois', null, array('label' => $this->getFieldLabel('mois')))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('no_TVA_tiers', null, array('label' => $this->getFieldLabel('no_TVA_tiers')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('devise_id', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', null, array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('mois', null, array('label' => $this->getFieldLabel('mois')))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'));
    }

    protected function getDate_pieceFormValue($value)
    {
        return $this->dateFormValue($value);
    }
}