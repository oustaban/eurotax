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
			
			
			if(!is_dir(DEB_A_ENVOYER_ABSPATH)) {
				mkdir(DEB_A_ENVOYER_ABSPATH, 0777, true);
			}
			
			if(!is_dir(DEB_A_COMPILER_BACKUP_ABSPATH)) {
				mkdir(DEB_A_COMPILER_BACKUP_ABSPATH, 0777, true);
			}
			
			
			$filesToConcat = array();
			$filesToConcatData = array();
			
			$filesToConcatData[] = $this->header();
			
			
			$iterator = new \DirectoryIterator( DEB_A_COMPILER_ABSPATH );
			foreach ( $iterator as $fileinfo ) {
				if ( $fileinfo->isFile() && preg_match( '/^(.+)\_transdeb\-([0-9]{4})\-([0-9]{1,2})\.txt$/i', $fileinfo->getFilename() ) ) {
					array_push($filesToConcat, $fileinfo->getPathname());
					array_push($filesToConcatData, file_get_contents($fileinfo->getPathname()));
				}
			}

			if(!empty($filesToConcatData)) {
				//name = "tansdeb-" + Year + Month "_fait-le-" dd "-" + mm
				$filename = 'transdeb-' . date('Y-m') . '_fait-le-' . date('d-m') . '.txt';
				$created = file_put_contents(DEB_A_ENVOYER_ABSPATH. '/' . $filename, implode(PHP_EOL, $filesToConcatData));
				if($created) {
					foreach($filesToConcat as $file) {
						rename($file, DEB_A_COMPILER_BACKUP_ABSPATH . '/' . basename($file));
					}
					
					$this->get('session')->setFlash('sonata_flash_info|raw', $filename . ' is created.');
				}
			} else {
				$this->get('session')->setFlash('sonata_flash_info|raw', 'Deb-à-Envoyer directory is empty.');
			}
			
			return $this->render(':redirects:back.html.twig');
		}
		
		return array();
	}
	
	
	protected function header() {
		//INTRACOMD0IO1131       1218332089218000410141984819          0141984818          Fabrice COCHET
		
		list($current_year, $current_month) = explode('-', date('Y-m', strtotime('now' . (date('d') < 25 ? ' -1 month' : ''))));
		$user = $this->get('security.context')->getToken()->getUser();
		return "INTRACOMD0IO" . $current_month . "31       ". date('dm') ."332089218000410141984819          0141984818           " . $user->getFullname();
	}
	
	
}