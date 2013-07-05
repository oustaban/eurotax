<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Sonata\AdminBundle\Validator\ErrorElement;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

class NumeroTVAAdmin extends Admin
{

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);
        $formMapper->with($this->getFieldLabel('title'))
        
            ->add('code', null, array('label' => $this->getFieldLabel('code')))
            ->add('n_de_TVA', null, array('label' => $this->getFieldLabel('n_de_TVA'), ))
        	->add('date_de_verification', null, array(
        		'label' => $this->getFieldLabel('date_de_verification'),
        		'attr' => array('class' => 'datepicker'),
        		'widget' => 'single_text',
        		'input' => 'datetime',
        		'format' => $this->date_format_datetime,
        		'required' => false
        ))
            
            
            
      ;
    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $listMapper
            ->add('code', null, array('label' => $this->getFieldLabel('code')))
            ->add('n_de_TVA', null, array('label' => $this->getFieldLabel('n_de_TVA')))
            ->add('date_de_verification', null, array('label' => $this->getFieldLabel('date_de_verification')))
            
            
        ;
    }


   
}