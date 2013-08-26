<?php

namespace Application\Sonata\ClientBundle\Controller;

use Application\Sonata\ClientBundle\Controller\AbstractTabsController as Controller;
use Application\Sonata\ClientBundle\Entity\ListCountries;


/**
 * Document controller.
 *
 */
class DocumentController extends Controller
{
    /**
     * @var string
     */
    protected  $_tabAlias = 'document';
    
    
    
    public function configure()
    {
    	parent::configure();
      	
    	$this->jsSettingsJson(array(
    		'client' =>  array(
    			'nature_du_client' => $this->getClient()->getNatureDuClient()->getName(),
    			'pays_code' => $this->getClient()->getPaysPostal()->getCode(),
    			'country_eu' => ListCountries::getCountryEU()
    				
    		)
    	));
    	
    }
}
