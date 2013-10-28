<?php

namespace Application\Sonata\ClientOperationsBundle\Admin\Validate;

use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\DevisesBundle\Entity\Devises;

use Application\Sonata\ClientOperationsBundle\Admin\Validate\ErrorElements;


class DEBErrorElements extends ErrorElements
{
    
	protected $_requiredFields = array(
		'DEBExped' => array(
			1 => array(
					21 => array(
						'niveauDobligationId' => 1,
						'regime' => 21,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							//'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							'mode_transport',
							'departement',
							//'pays_origine',
							'CEE',
						)	
					),
					29 => array(
						'niveauDobligationId' => 1,
						'regime' => 29,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							//'valeur_fiscale',
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							'mode_transport',
							'departement',
							//'pays_origine',
							//'CEE',
						)	
					), 
					25 => array(
						'niveauDobligationId' => 1,
						'regime' => 25,
						'fields' => array(
							'n_ligne',
			                //'nomenclature',
			                //'pays_destination',
			                'valeur_fiscale',
			                
			                //'valeur_statistique',
			                //'masse_mette',
			                //'unites_supplementaires',
			                //'nature_transaction',
			                //'conditions_livraison',
			                //'mode_transport',
			                //'departement',
			                //'pays_origine',
			                'CEE',
						)
					),				
					26 => array(
						'niveauDobligationId' => 1,
						'regime' => 26,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					31 => array(
						'niveauDobligationId' => 1,
						'regime' => 31,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
			),
			
			2 => array(
					21 => array(
						'niveauDobligationId' => 2,
						'regime' => 21,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)	
					),
					
					
					29 => array(
						'niveauDobligationId' => 2,
						'regime' => 29,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							//'valeur_fiscale',
							
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							//'CEE',
						)
					),
					25 => array(
						'niveauDobligationId' => 2,
						'regime' => 25,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					26 => array(
						'niveauDobligationId' => 2,
						'regime' => 26,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					31 => array(
						'niveauDobligationId' => 2,
						'regime' => 31,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
				),
				
				3 => array(
					21 => array(
						'niveauDobligationId' => 3,
						'regime' => 21,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					29 => array(
						'niveauDobligationId' => 3,
						'regime' => 29,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							//'CEE',
						)
					),
					25 => array(
						'niveauDobligationId' => 3,
						'regime' => 25,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					26 => array(
						'niveauDobligationId' => 3,
						'regime' => 26,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					31 => array(
						'niveauDobligationId' => 3,
						'regime' => 31,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),										
			),
			
			4 => array(
					21 => array(
						'niveauDobligationId' => 4,
						'regime' => 21,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					25 => array(
						'niveauDobligationId' => 4,
						'regime' => 25,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					26 => array(
						'niveauDobligationId' => 4,
						'regime' => 26,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),
					31 => array(
						'niveauDobligationId' => 4,
						'regime' => 31,
						'fields' => array(
							'n_ligne',
							//'nomenclature',
							//'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							'CEE',
						)
					),															
			)
			
		),

		'DEBIntro' => array(
			1 => array(
					11 => array(
						'niveauDobligationId' => 1,
						'regime' => 11,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							'mode_transport',
							'departement',
							'pays_origine',
							//'CEE',
						)
					),
					19 => array(
						'niveauDobligationId' => 1,
						'regime' => 19,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							//'valeur_fiscale',
							
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							'mode_transport',
							'departement',
							'pays_origine',
							//'CEE',
						)
					),					
				),
				
				
			2 => array(
					11 => array(
						'niveauDobligationId' => 2,
						'regime' => 11,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							'pays_origine',
							//'CEE',
						)
					),
					19 => array(
						'niveauDobligationId' => 2,
						'regime' => 19,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							//'valeur_fiscale',
							
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							'pays_origine',
							//'CEE',
						)
					),
			),
			3 => array(
					11 => array(
						'niveauDobligationId' => 3,
						'regime' => 11,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							'valeur_fiscale',
							
							//'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							//'CEE',
						)
					),
					19 => array(
						'niveauDobligationId' => 3,
						'regime' => 19,
						'fields' => array(
							'n_ligne',
							'nomenclature',
							'pays_destination',
							//'valeur_fiscale',
							
							'valeur_statistique',
							//'masse_mette',
							//'unites_supplementaires',
							//'nature_transaction',
							//'conditions_livraison',
							//'mode_transport',
							//'departement',
							//'pays_origine',
							//'CEE',
						)
					),
			)
					
		)
	);


	protected $_emptyFields = array(
		'DEBExped' => array(
			1 => array(
					21 => array(
							'niveauDobligationId' => 1,
							'regime' => 21,
							'fields' => array(
								'conditions_livraison',
								'valeur_statistique',
								'pays_origine',

							)
					),
					29 => array(
							'niveauDobligationId' => 1,
							'regime' => 29,
							'fields' => array(
									'valeur_fiscale',
									'conditions_livraison',
									'CEE',
									'pays_origine',
							)
					),
					25 => array(
							'niveauDobligationId' => 1,
							'regime' => 25,
							'fields' => array(

									'nomenclature',
									'pays_destination',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
									//'CEE',
							)
					),
					26 => array(
							'niveauDobligationId' => 1,
							'regime' => 26,
							'fields' => array(

									'nomenclature',
									'pays_destination',

									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					31 => array(
							'niveauDobligationId' => 1,
							'regime' => 31,
							'fields' => array(

									'nomenclature',
									'pays_destination',

									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',

							)
					),
			),

			2 => array(
					21 => array(
							'niveauDobligationId' => 2,
							'regime' => 21,
							'fields' => array(
									'valeur_statistique',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',

							)
					),


					29 => array(
							'niveauDobligationId' => 2,
							'regime' => 29,
							'fields' => array(
									'valeur_fiscale',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
									'CEE',
							)
					),
					25 => array(
							'niveauDobligationId' => 2,
							'regime' => 25,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					26 => array(
							'niveauDobligationId' => 2,
							'regime' => 26,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					31 => array(
							'niveauDobligationId' => 2,
							'regime' => 31,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
			),

			3 => array(
					21 => array(
							'niveauDobligationId' => 3,
							'regime' => 21,
							'fields' => array(
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					29 => array(
							'niveauDobligationId' => 3,
							'regime' => 29,
							'fields' => array(
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
									'CEE',
							)
					),
					25 => array(
							'niveauDobligationId' => 3,
							'regime' => 25,
							'fields' => array(
									'nomenclature',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					26 => array(
							'niveauDobligationId' => 3,
							'regime' => 26,
							'fields' => array(

									'nomenclature',
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',

							)
					),
					31 => array(
							'niveauDobligationId' => 3,
							'regime' => 31,
							'fields' => array(

									'nomenclature',
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',

							)
					),
			),

			4 => array(
					21 => array(
							'niveauDobligationId' => 4,
							'regime' => 21,
							'fields' => array(

									'nomenclature',
									'pays_destination',

									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					25 => array(
							'niveauDobligationId' => 4,
							'regime' => 25,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					26 => array(
							'niveauDobligationId' => 4,
							'regime' => 26,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
					
					
					29 => array(
							'niveauDobligationId' => 4,
							'regime' => 29,
							'fields' => array(
									'n_ligne',
									'nomenclature',
									'pays_destination',
									'valeur_fiscale',
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
									'CEE',
							)
					),					
					
					31 => array(
							'niveauDobligationId' => 4,
							'regime' => 31,
							'fields' => array(
									'nomenclature',
									'pays_destination',
									'valeur_statistique',
									'masse_mette',
									'unites_supplementaires',
									'nature_transaction',
									'conditions_livraison',
									'mode_transport',
									'departement',
									'pays_origine',
							)
					),
			)

		),

		'DEBIntro' => array(
			1 => array(
				11 => array(
					'niveauDobligationId' => 1,
					'regime' => 11,
					'fields' => array(
						'conditions_livraison',
						//'CEE',
						'valeur_statistique',
					)
				),
				19 => array(
				'niveauDobligationId' => 1,
				'regime' => 19,
				'fields' => array(
						'valeur_fiscale',
						'conditions_livraison',
						//'CEE',
					)
				),
		),


		2 => array(
			11 => array(
				'niveauDobligationId' => 2,
				'regime' => 11,
				'fields' => array(
					'conditions_livraison',
					'mode_transport',
					'departement',
					//'CEE',
					'valeur_statistique',
					)
			),
			19 => array(
				'niveauDobligationId' => 2,
				'regime' => 19,
				'fields' => array(
					'valeur_fiscale',
					'conditions_livraison',
					'mode_transport',
					'departement',
					//'CEE',
				)
			),
		),
		3 => array(
				11 => array(
					'niveauDobligationId' => 3,
					'regime' => 11,
					'fields' => array(
						'valeur_statistique',
						'masse_mette',
						'unites_supplementaires',
						'nature_transaction',
						'conditions_livraison',
						'mode_transport',
						'departement',
						'pays_origine',
						//'CEE',
					)
				),
				19 => array(
					'niveauDobligationId' => 3,
					'regime' => 19,
					'fields' => array(
						'valeur_fiscale',
						'masse_mette',
						'unites_supplementaires',
						'nature_transaction',
						'conditions_livraison',
						'mode_transport',
						'departement',
						'pays_origine',
						//'CEE',
					)
				),
			)

		)
	);
	
	public function validateDEB() {
		//if ($this->_is_validate_import) {
			$class = (explode('\\', get_class($this->_object)));
			$class = end($class);
			$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
			$em = $doctrine->getManager();
			$client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($this->_object->getClientId());
			
			if($class == 'DEBIntro') {
				$niveauDobligationId = $client->getNiveauDobligationId(); //INTRO
			} elseif($class == 'DEBExped') {
				$niveauDobligationId = $client->getNiveauDobligationExpedId(); //DEB
			}
			
			$regime = $this->_object->getRegime();
	
			
			
			
			
			
			/*
			  On deb Exped and Deb Intro... when the user put a code for 'Nomenclature" and 
			  we have a data on the colum "Unité supplémentaire" of the file 
			  ( http://outils.prodinternet.com/mantis/view.php?id=4210) ... 
			  check that the field "Unité supplémentaire" is mandatory
			 */
			
			
			$unitesSupplementairesRequired = null;

			$nomenclature = $this->_object->getNomenclature();
			if($nomenclature) {
				/* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
				$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
				 
				/* @var $em \Doctrine\ORM\EntityManager */
				$em = $doctrine->getManager();
				$findNomenclature = $em->getRepository('ApplicationSonataClientBundle:Nomenclature')->findOneBy(array('code' => ltrim(str_replace(' ', '', $nomenclature), 0)));
				 
				if($findNomenclature) {
					
					if($findNomenclature->getUnitesSupplementaires() && $this->_object->getUnitesSupplementaires()) {
						$unitesSupplementairesRequired = true;
					} else if($findNomenclature->getUnitesSupplementaires() && !$this->_object->getUnitesSupplementaires()) {
						$unitesSupplementairesRequired = true;
					} else if(!$findNomenclature->getUnitesSupplementaires() && $this->_object->getUnitesSupplementaires()) {
						$unitesSupplementairesRequired = false;
					} else if(!$findNomenclature->getUnitesSupplementaires() && !$this->_object->getUnitesSupplementaires()) {
						$unitesSupplementairesRequired = false;
					}
				}
			}
			
			$requiredFields = @$this->_requiredFields[$class][$niveauDobligationId][$regime]['fields'];
			
			if(!empty($requiredFields)) {
				$requiredFields = array_flip($requiredFields);
				
				if(isset($requiredFields['nomenclature'])) {
					$this->validateNomenclature2();
				}
				
				if(!is_null($unitesSupplementairesRequired)) {
					if($unitesSupplementairesRequired === false) {
						unset($requiredFields['unites_supplementaires']);
					} elseif($unitesSupplementairesRequired === true) {
						$requiredFields['unites_supplementaires'] = true;
					}
				}
				
				foreach($requiredFields as $field => $v) {
					$method = 'get' . strtoupper(\Doctrine\Common\Util\Inflector::camelize($field));
					
					
					if($field == 'masse_mette') {
					
						if($this->_object->$method() == '') {
						
							$this->_errorElement->with($field)->addViolation( 'La valeur de est obligatoire.' )->end();
						}
						
						
						
					} else {
						if(!$this->_object->$method()) {
							
							if($field == 'unites_supplementaires') {
								$this->_errorElement->with('unites_supplementaires')->addViolation( 'UNITES SUPPLEMENTAIRES devrait être rempli '. ($this->_object->getNomenclature() ? 'pour ' . $this->_object->getNomenclature() : '' ) )->end();
							} else {
								$this->_errorElement->with($field)->addViolation( 'La valeur de est obligatoire.' )->end();
							}
						}
					}
					
					
				}
			}
			
			$emptyFields = @$this->_emptyFields[$class][$niveauDobligationId][$regime]['fields'];
			
			if(!empty($emptyFields)) {
				
				$emptyFields = array_flip($emptyFields);
				if(!is_null($unitesSupplementairesRequired)) {
					if($unitesSupplementairesRequired === true) {
						unset($emptyFields['unites_supplementaires']);
					} elseif($unitesSupplementairesRequired === false) {
						$emptyFields['unites_supplementaires'] = true;
					}
				
				}
				
				
				foreach($emptyFields as $field => $v) {
					$hasViolation = false;
					$method = 'get' . strtoupper(\Doctrine\Common\Util\Inflector::camelize($field));
					$value = $this->_object->$method();
					
					if( (!is_object($value) && (int)$value != 0) || $value) {
						
						if($field == 'unites_supplementaires') {
							$this->_errorElement->with('unites_supplementaires')->addViolation( 'UNITES SUPPLEMENTAIRES devrait être vide '. ($this->_object->getNomenclature() ? 'pour ' . $this->_object->getNomenclature() : '' ) )->end();
						} else {
							$this->_errorElement->with($field)->addViolation( 'La cellule doit être vide.')->end();
						}
					}
				}
			}
		//}
		return $this;
	}
	
	
}

