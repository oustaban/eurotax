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

use Application\Sonata\ClientBundle\Admin\AbstractCompteAdmin as Admin;

class CompteDeDepotAdmin extends Admin
{
	
	
	
	public function validate(ErrorElement $errorElement, $object)
	{
		parent::validate($errorElement, $object);
	

		$value = $object->getMontant();
	
		if ($value >= 0) {
			$errorElement->with('montant')->addViolation('Le montant doit être négatif')->end();
		}
		
	}
	
	
}

