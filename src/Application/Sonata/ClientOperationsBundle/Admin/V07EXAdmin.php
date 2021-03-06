<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;
use Doctrine\ORM\EntityRepository;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class V07EXAdmin extends Admin
{

    /**
     * @param \Sonata\AdminBundle\Form\FormMapper $formMapper
     * @return \Sonata\AdminBundle\Form\FormMapper|void
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $id = $this->getRequest()->get($this->getIdParameter());
        
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
            ->add('mois', 'mois', array(
            'label' => $this->getFieldLabel('mois'),
          	'disabled' => ((!$id && $this->getLocking()) || $this->isObjectLocked()) ? true : false		
        ))
            ->add('taux_de_change', null, array(
            'label' => $this->getFieldLabel('taux_de_change'),
            'required' => false,
        ))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'required'=>false))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('numero_piece', null, array('label' => $this->getFieldLabel('numero_piece')))
            ->add('date_piece', null, array(
            'label' => $this->getFieldLabel('date_piece'),
            'template' => $this->_bundle_name . ':CRUD:list_date_piece.html.twig'
        ))
            ->add('devise.alias', null, array('label' => $this->getFieldLabel('devise_id')))
            ->add('montant_HT_en_devise', 'money', array('label' => $this->getFieldLabel('montant_HT_en_devise'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:montant_HT_en_devise.html.twig'))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('taux_de_change', null, array('label' => $this->getFieldLabel('taux_de_change')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:HT.html.twig'))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));

        $this->postConfigureListFields($listMapper);
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\V07EX */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object, $this->import_file_year, $this->import_file_month);
        $error->setAdmin($this)
        	->setValidateImport($this->getValidateImport())
        	->setMois2()
            ->validateDevise()
            ->validateHT()
            ->validateMois();
    }
}