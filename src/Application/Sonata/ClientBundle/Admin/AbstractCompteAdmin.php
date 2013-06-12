<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Sonata\AdminBundle\Validator\ErrorElement;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Application\Sonata\ClientBundle\Admin\AbstractTabsAdmin as Admin;

abstract class AbstractCompteAdmin extends Admin
{
    protected $datagridValues = array(
        '_sort_order' => 'ASC',
        '_sort_by' => 'date'
    );

    //create & edit form
    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper->with($this->getFieldLabel('title'))
            ->add('date', null, array(
            'attr' => array('class' => 'datepicker'),
            'widget' => 'single_text',
            'input' => 'datetime',
            'format' => $this->date_format_datetime,
            'label' => $this->getFieldLabel('date'),
        ))
            ->add('operation', null, array('label' => $this->getFieldLabel('operation')))
            ->add('montant', 'money', array('label' => $this->getFieldLabel('montant')))
            ->add('commentaire', null, array('label' => $this->getFieldLabel('commentaire')))
            ->add('statut', null, array(
            'label' => $this->getFieldLabel('statut'),
            'empty_value' => '',
            'required' => true, 
        ));

    }

    //list
    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        parent::configureListFields($listMapper);

        $filterParameters = $this->getFilterParameters();
        if ($filterParameters['_sort_order'] == $this->datagridValues['_sort_order'] && $filterParameters['_sort_by'] == $this->datagridValues['_sort_by']){
            global $SonataAdminBundle_Compte_list_solde;
            $SonataAdminBundle_Compte_list_solde = 0;
        }

        $listMapper->add('date', null, array(
            'label' => $this->getFieldLabel('date'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_date_compte.html.twig'
        ))
            ->add('operation', null, array('label' => $this->getFieldLabel('operation')))
            ->add('montant', 'money', array(
            'label' => $this->getFieldLabel('montant'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_montant.html.twig',
        ))
            ->add('solde', 'money', array(
            'label' => $this->getFieldLabel('solde'),
            'template' => 'ApplicationSonataClientBundle:CRUD:list_solde.html.twig'
        ))
            ->add('commentaire', null, array('label' => $this->getFieldLabel('commentaire')))
            ->add('statut.name', null, array('label' => $this->getFieldLabel('statut')));
    }

    /**
     * @param ErrorElement $errorElement
     * @param mixed|\Application\Sonata\ClientBundle\Entity\AbstractCompteEntity $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
        parent::validate($errorElement, $object);

        /* @var $value \DateTime */
        $value = $object->getDate();
        if ($value && !$this->getValidateImport()) {
            if ($value->getTimestamp() < strtotime('-10 days')) {
                $errorElement->with('date')->addViolation('La "date" ne doit pas dépasser les 10 jours dans le passé')->end();
            }
        }
    }
    
    
    
    public function hasAjouterAccess() {
    	
    	/** @var $doctrine  \Doctrine\Bundle\DoctrineBundle\Registry */
    	$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
    	$garantie = $doctrine->getManager()->getRepository('ApplicationSonataClientBundle:Garantie')->findOneBy(
   			array(
   				'client' => $this->client_id, 'type_garantie' => 2 // Dépôt de Garantie
   			)
    	);
    	//var_dump($garantie->getNomDeLaBanquesId()); exit;
    	//If  status is "A établir" for "Dépôt de Garantie"
    	if($garantie && $garantie->getNomDeLaBanquesId() == 1) {
    		return false;
    	}
    	
    	return true;
    }
    
    
    
    
    
    
}

