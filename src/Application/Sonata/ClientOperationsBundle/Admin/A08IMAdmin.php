<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A08IMAdmin extends Admin
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
            ->add('taux_de_TVA', 'percent', array(
            'label' => $this->getFieldLabel('taux_de_TVA'),
            'precision' => 3,
        ))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA')))
            ->add('mois', 'date', array(
            'label' => $this->getFieldLabel('mois'),
            'days' => range(1, 1),
            'format' => 'dd MMMM yyyy',
        ))
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
            ->add('taux_de_TVA', 'percent', array('label' => $this->getFieldLabel('taux_de_TVA')))
            ->add('TVA', 'money', array('label' => $this->getFieldLabel('TVA'), 'template' => 'ApplicationSonataClientOperationsBundle:CRUD:TVA.html.twig'))
            ->add('mois', null, array(
            'label' => $this->getFieldLabel('mois'),
            'template' => $this->_bundle_name . ':CRUD:list_mois.html.twig',
        ))
            ->add('commentaires', null, array('label' => $this->getFieldLabel('commentaires')));
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
        /* @var $object \Application\Sonata\ClientOperationsBundle\Entity\A08IM */
        parent::validate($errorElement, $object);

        $error = new ErrorElements($errorElement, $object);
        $error->validateMois();
    }
}