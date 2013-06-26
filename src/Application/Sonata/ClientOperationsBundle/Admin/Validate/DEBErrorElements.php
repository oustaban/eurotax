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
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							//'conditions_livraison',
							'mode_transport',
							'departement',
							'pays_origine',
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
							'pays_origine',
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

							)
					),
					29 => array(
							'niveauDobligationId' => 1,
							'regime' => 29,
							'fields' => array(
									'valeur_fiscale',
									'conditions_livraison',
									'CEE',
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
									'CEE',
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
						'CEE',
					)
				),
				19 => array(
				'niveauDobligationId' => 1,
				'regime' => 19,
				'fields' => array(
						'valeur_fiscale',
						'conditions_livraison',
						'CEE',
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
					'CEE',
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
					'CEE',
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
						'CEE',
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
						'CEE',
					)
				),
			)

		)
	);
	
	public function validateDEB() {
		
		
		static $hasTabError = array();
		
		
		if ($this->_is_validate_import) {
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
	
			
			if($niveauDobligationId == 0) {
				if(!isset($hasTabError[$class])) {
					$this->_errorElement->addViolation( 'Le niveau d\'obligation est à 0 : On ne devrait pas avoir de données' )->end();
					$hasTabError[$class] = true;
					return $this;
				}
			}
			
			
			
			$requiredFields = @$this->_requiredFields[$class][$niveauDobligationId][$regime]['fields'];
			if(!empty($requiredFields)) {
				foreach($requiredFields as $field) {
					$method = 'get' . strtoupper(\Doctrine\Common\Util\Inflector::camelize($field));
					//var_dump($method);
					if(!$this->_object->$method()) {
						$this->_errorElement->with($field)->addViolation( 'La valeur de est obligatoire.' )->end();
					}
				}
			}
			
			$emptyFields = @$this->_emptyFields[$class][$niveauDobligationId][$regime]['fields'];
			if(!empty($emptyFields)) {
				foreach($emptyFields as $field) {
					$method = 'get' . strtoupper(\Doctrine\Common\Util\Inflector::camelize($field));
					if($this->_object->$method()) {
						$this->_errorElement->with($field)->addViolation( 'La cellule doit être vide.' )->end();
					}
				}
			}
			
			
			
		}
		return $this;
	}
	
	
}

