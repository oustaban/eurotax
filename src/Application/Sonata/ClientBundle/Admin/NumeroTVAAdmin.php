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
        		'required' => true,
        		'help' => 'Si site VIES non disponible : indiquer la date du 01/01/2000'
        ));
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
            ->add('date_de_verification', null, array('label' => $this->getFieldLabel('date_de_verification'), 
            	'template' => 'ApplicationSonataClientBundle:CRUD:list_date_de_verification.html.twig',))
            
            
        ;
    }

    
    /**
     * @param ErrorElement $errorElement
     * @param mixed $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {
    	parent::validate($errorElement, $object);
    	
    	$this->validateNDeTVA($errorElement, $object);
    	
    	if (is_null($object->getId()) && $object->getDateDeVerification()) {
    		$date = $object->getDateDeVerification();
    		if( $date->format('Ymd') > date('Ymd')) {
    			$errorElement->with('date_de_verification')->addViolation('La date ne peut pas être postérieure à la date du jour.')->end();
    		}
    	}
    }

    
    
    /**
     * GB + 5, 9 ou 12 caractères
     * 
     * 

CZ + 8, 9 ou 10 caractères

DK + 8 caractères
FI + 8 caractères
IE + 8 caractères
LU + 8 caractères
HU + 8 caractères
MT + 8 caractères
SI + 8 caractères


LT + 9 ou 12 caractères

ES + 9 caractères ou +A/B et 8 caractères
     * 
     * DE + 9 caractères
AT + 9 caractères
BE + 9 caractères
EL + 9 caractères
CY + 9 caractères
EE + 9 caractères
PT + 9 caractères

PL + 10 caractères
SK + 10 caractères
BG + 10 caractères
RO + 10 caractères

FR + 11 caractères
IT + 11 caractères
LV + 11 caractères
HR + 11 caractères

NL + 12 caractères
SE + 12 caractères



     * @param unknown $object
     */
    protected function validateNDeTVA(ErrorElement $errorElement, $object) {
    	
    	$validationDef = array(
    			'GB' => array(5,9,12),//GB + 5, 9 ou 12 caractères
    			'CZ' => array(8,9,10),//CZ + 8, 9 ou 10 caractères
    			
    			'DK' => array(8), //DK + 8 caractères
    			'FI' => array(8),//FI + 8 caractères
    			'IE' => array(8),//IE + 8 caractères
    			'LU' => array(8),//LU + 8 caractères
    			'HU' => array(8),//HU + 8 caractères
    			'MT' => array(8),//MT + 8 caractères
    			'SI' => array(8),//SI + 8 caractères
    			
    			'LT' => array(9, 12),//LT + 9 ou 12 caractères
    			
    			'ES' => array(9),//ES + 9 caractères ou +A/B et 8 caractères
    			'A/B' => array(8),
    			
    			'DE' => array(9),//DE + 9 caractères
    			'AT' => array(9),//AT + 9 caractères
    			'BE' => array(9),//BE + 9 caractères
    			'EL' => array(9),//EL + 9 caractères
    			'CY' => array(9),//CY + 9 caractères
    			'EE' => array(9),//EE + 9 caractères
    			'PT' => array(9),//PT + 9 caractères
    			
    			'PL' => array(10),//PL + 10 caractères
    			'SK' => array(10),//SK + 10 caractères
    			'BG' => array(10),//BG + 10 caractères
    			'RO' => array(10),//RO + 10 caractères
    			
    			'FR' => array(11),//FR + 11 caractères
    			'IT' => array(11),//IT + 11 caractères
    			'LV' => array(11),//LV + 11 caractères
    			'HR' => array(11),//HR + 11 caractères
    			
    			'NL' => array(12),//NL + 12 caractères
    			'SE' => array(12),//SE + 12 caractères
    	);
    	
    	$value = str_replace(' ', '', $object->getNDeTVA());
	    $key = substr($value, 0, 2);
	    $trail = substr($value, 2); //trailing characters
    	
    	// for 'A/B' key
	    if(strstr($key,'/')) {
	    	$key = substr($value, 0, 3);
	    	$trail = substr($value, 3); //trailing characters
	    }
    	
    	
    	$validated = function($key, $trail) use ($validationDef) {
	    	if(!array_key_exists($key,$validationDef)) {
	    		return false;
	    	}
	    	
	    	if(isset($validationDef[$key])) {
	    		$lengths = $validationDef[$key];
	    		if(!in_array(strlen($trail), $lengths)) {
	    			return false;
	    		}
	    	}
	    	return true;
    	};
    	
    	
    	if($validated($key, $trail) === false) {
    		$errorElement->with('n_de_TVA')->addViolation('Mauvais format de TVA.')->end();
    	}
    	
    }
    
    
   
}