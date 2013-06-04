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
							'valeur_statistique',
							'masse_mette',
							'unites_supplementaires',
							'nature_transaction',
							'mode_transport',
							'departement',
							'pays_origine',
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
			                //'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
							//'regime',
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
	
	
	
	
	public function validateDEB() {
		if ($this->_is_validate_import) {
			$class = (explode('\\', get_class($this->_object)));
			$class = end($class);
			$doctrine = \AppKernel::getStaticContainer()->get('doctrine');
			$em = $doctrine->getManager();
	
			$client = $em->getRepository('ApplicationSonataClientBundle:Client')->find($this->_object->getClientId());
			$niveauDobligationId = $client->getNiveauDobligationId();
			$regime = $this->_object->getRegime();
	
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
		}
		return $this;
	}
	
	
}

