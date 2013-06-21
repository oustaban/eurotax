<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V05LICAdmin extends Admin
{

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return \Sonata\AdminBundle\Form\FormMapper|void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        
        $id = $this->getRequest()->get($this->getIdParameter());
        $DEBDefaultValue = array();
        if (!$id){
        	$DEBDefaultValue = array('data' => 1);
        }
        
        
        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('no_TVA_tiers', null, array('label' => $this->getFieldLabel('no_TVA_tiers')))
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
            ->add('mois', 'mois', array(
            'label' => $this->getFieldLabel('mois'),
        ))
            ->add('taux_de_change', null, array(
            'label' => $this->getFieldLabel('taux_de_change'),
            'required' => false,
        ))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'required'=>false))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('DEB', 'choice', array('label' => 'DEB', 'choices' => array(1 => 'Oui', 0 => 'Non'),'multiple' => false,'expanded'=>true)+$DEBDefaultValue)
            
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')))
            ;
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
            ->add('devise.alias', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('regime', null, array('label' => $this->getFieldLabel('regime')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('DEB', null, array('label' => $this->getFieldLabel('DEB')));

        $this->postConfigureListFields($listMapper);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\V05LIC */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object, $this->import_file_year, $this->import_file_month);
        $error->setValidateImport($this->getValidateImport())
        	->validateRegime2(array(21, 25, 26, 29))
        	->validateDEB()
            ->validateDevise()
            ->validateHT()
            ->validateMois();
    }

   /*  protected function getDEBFormValue($value)
    {
        return !(!$value || strtolower($value) == 'non');
    } */
}