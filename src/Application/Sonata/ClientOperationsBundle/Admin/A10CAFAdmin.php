<?php

namespace Application\Sonata\ClientOperationsBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;

use Application\Sonata\ClientOperationsBundle\Admin\AbstractTabsAdmin as Admin;

class A10CAFAdmin extends Admin
{

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
         parent::configureFormFields($formMapper);

        $formMapper
            ->add('tiers', null, array('label' => $this->getFieldLabel('tiers')))
            ->add('date_piece', null, array('label'=> $this->getFieldLabel('date_piece')))
            ->add('numero_piece', null, array('label'=> $this->getFieldLabel('numero_piece')))
            ->add('HT', 'money', array('label' => $this->getFieldLabel('HT')))
            ->add('mois', null, array('label' => $this->getFieldLabel('mois')))
            ->add('commentaires', null, array('label'=> $this->getFieldLabel('commentaires')));
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper->addIdentifier('id', null)
            ->add('tiers', null, array('label'=> $this->getFieldLabel('tiers')))
            ->add('date_piece', null, array('label'=> $this->getFieldLabel('date_piece')));
    }

    protected function getDate_pieceFormValue($value)
    {
        return $this->dateFormValue($value);
    }
}