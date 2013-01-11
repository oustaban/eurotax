<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A02TVAAdmin extends Admin
{

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return \Sonata\AdminBundle\Form\FormMapper|void
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
            ->add('devise', null, array('label' => $this->getFieldLabel('devise_id'), 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('d')
                ->orderBy('d.alias', 'ASC');
        },))
            ->add('montant_HT_en_devise', null, array('attr'=>array('class'=>'money'), 'label' => $this->getFieldLabel('montant_HT_en_devise')))
            ->add('taux_de_TVA', 'choice', array(
            'label' => $this->getFieldLabel('taux_de_TVA'),
            'choices' => array(
                '0.196' => '19.6%',
                '0.13' => '13%',
                '0.085' => '8.5%',
                '0.08' => '8%',
                '0.07' => '7%',
                '0.055' => '5.5%',
                '0.021' => '2.1%',
                '0.0105' => '1.05%',
                '0.009' => '0.9%',
            ),
            'empty_value' => '',
        ))
            ->add('montant_TVA_francaise', null, array('attr'=>array('class'=>'money'), 'label' => $this->getFieldLabel('montant_TVA_francaise')))
            ->add('montant_TTC', null, array('attr'=>array('class'=>'money'), 'label' => $this->getFieldLabel('montant_TTC')))
            ->add('paiement_montant', null, array('attr'=>array('class'=>'money'), 'required' => false, 'label' => $this->getFieldLabel('paiement_montant')))
            ->add('paiement_devise', null, array('label' => $this->getFieldLabel('paiement_devise_id'), 'query_builder' => function (EntityRepository $er)
        {
            return $er->createQueryBuilder('d')
                ->orderBy('d.alias', 'ASC');
        },))
            ->add('paiement_date', null, array(
                'label' => $this->getFieldLabel('paiement_date'),
                'required' => false,
                'attr' => array('class' => 'datepicker'),
                'widget' => 'single_text',
                'input' => 'datetime',
                'format' => $this->date_format_datetime)
        )
            ->add('mois', 'mois', array(
            'label' => $this->getFieldLabel('mois'),
        ))
            ->add('taux_de_change', null, array(
            'label' => $this->getFieldLabel('taux_de_change'),
            'required' => false,
        ))
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
            ->add('devise.alias', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('taux_de_TVA', 'percent', array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('montant_TVA_francaise', 'money', array('label' => $this->getFieldLabel('montant_TVA_francaise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_TVA_francaise.html.twig'))
            ->add('montant_TTC', 'money', array('label' => $this->getFieldLabel('montant_TTC'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_TTC.html.twig'))
            ->add('paiement_montant', 'money', array('label' => $this->getFieldLabel('paiement_montant'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:paiement_montant.html.twig'))
            ->add('paiement_devise.alias', null, array('label' => $this->getFieldLabel('paiement_devise_id')))
            ->add('paiement_date', null, array(
            'label' => $this->getFieldLabel('paiement_date'),
            'template' => $this->_bundle_name . ':CRUD:list_paiement_date.html.twig'
        ))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:TVA.html.twig'))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));

        $this->postConfigureListFields($listMapper);
    }

    protected function getPaiement_dateFormValue($value)
    {
        return $this->dateFormValue($value);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object);
        $error->setValidateImport($this->getValidateImport())
            ->validatePaiementDateCloneMois()
            ->validateMontantTVAFrancaise()
            ->validateMontantTTC()
            ->validateTauxDeChange()
            ->validateHT()
            ->validateMois()
        ;
    }
}