<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A04283IAdmin extends Admin
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
            ->add('mois', 'mois', array(
            'label' => $this->getFieldLabel('mois'),
        ))
            ->add('taux_de_change', null, array(
            'label' => $this->getFieldLabel('taux_de_change'),
            'required' => false,
        ))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'required'=>false))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA'), 'required'=>false))
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

    protected function getDate_pieceFormValue($value)
    {
        return $this->dateFormValue($value);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\A04283I */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object);
        $error->setValidateImport($this->getValidateImport())
            ->validateDevise()
            ->validateHT()
            ->validateMois()
        ;
    }
}