<?php

namespace Application\Sonata\ClientBundle\Admin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Symfony\Component\HttpFoundation\Request;

use Application\Sonata\ClientBundle\Admin\AbstractCompteAdmin as Admin;

use Sonata\AdminBundle\Route\RouteCollection;

class CompteAdmin extends Admin
{
	
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->add('virement', 'virement/{amount}/{coordonnees}');
		 
	}
}
