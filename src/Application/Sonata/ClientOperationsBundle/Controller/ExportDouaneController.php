<?php
namespace Application\Sonata\ClientOperationsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ExportDouaneController extends Controller {

	
	/**
	 * @Template()
	 */
	public function indexAction() {
		
		$request = $this->get('request');
		
		if ($request->getMethod() == 'POST') {
			
			
			
		}
		
		return array();
	}
	
}