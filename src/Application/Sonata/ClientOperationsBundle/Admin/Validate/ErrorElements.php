<?php

namespace Application\Sonata\ClientOperationsBundle\Admin\Validate;

use Sonata\AdminBundle\Validator\ErrorElement;
use Application\Sonata\DevisesBundle\Entity\Devises;

class ErrorElements
{
    const Device = 'EUR';
    const EPSILON = 0.000000001;

    protected $_errorElement;
    /**
     * @var \Application\Sonata\ClientOperationsBundle\Entity\AbstractBaseEntity
     */
    protected $_object;
    protected $_is_validate_import = false;


    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param $object
     */
    public function __construct(ErrorElement $errorElement, $object)
    {
        $this->_errorElement = $errorElement;
        $this->_object = $object;
    }

    /**
     * @return ErrorElements
     */
    public function validateTauxDeChange()
    {
        /** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\V01TVA */
        $_object = $this->_object;

        //-- http://redmine.testenm.com/issues/1364#note-9
        //if ($_object->getPaiementMontant() && !$_object->getTauxDeChange()) {
        if (!$_object->getTauxDeChange()) {
        	
        	$listDevise = null;
        	if(method_exists($_object, 'getPaiementDevise')) {
            	$listDevise = $_object->getPaiementDevise();
        	} else if(method_exists($_object, 'getDevise')) {
            	$listDevise = $_object->getDevise();
        	}
            
            $date = null;
            
            
            if(method_exists($_object, 'getPaiementDate')) {
            	$date = $_object->getPaiementDate();
            } else if(method_exists($_object, 'getDatePiece')) {
            	$date = $_object->getDatePiece();
            }
            
            
            
            if ($listDevise && $date) {
                $currency = $listDevise->getAlias();

                $taux_de_change = 0;
                if ($currency == static::Device) {
                    $taux_de_change = 1;
                } else {

//                     /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
//                     $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

//                     /* @var $em \Doctrine\ORM\EntityManager */
//                     $em = $doctrine->getManager();

//                     /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
//                     $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($_object->getPaiementDate());

                    
                    
                    
                    
//                     if ($devise) {
//                         $method = 'getMoney' . $currency;
//                         if (method_exists($devise, $method)) {
//                             $taux_de_change = $devise->$method();
//                         } else {
//                             new \Exception('Currency is not found (Devises): ' . $method);
//                         }
//                     }
                    
                    
                    $taux_de_change = Devises::getDevisesValue($listDevise->getId(), $date);
                }

                // setTauxDeChange
                $_object->setTauxDeChange($taux_de_change);

                if (!$taux_de_change) {
                    $this->_errorElement->with('taux_de_change')->addViolation('"Taux de change" non valide ' . $taux_de_change)->end();
                }
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateHT()
    {
        /** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\A04283I|\Application\Sonata\ClientOperationsBundle\Entity\A06AIB|\Application\Sonata\ClientOperationsBundle\Entity\A10CAF|\Application\Sonata\ClientOperationsBundle\Entity\AbstractSellEntity */
        $_object = $this->_object;
        $value = $_object->getHT();
        
        if ($value) {
        	if(method_exists($_object, 'getMontantTTC')) {
				if ($_object->getPaiementMontant() && $_object->getTauxDeTVA() && $_object->getTauxDeChange()) {
					//var HT = montant_TTC / (1 + parseFloat(taux_de_TVA)) / taux_de_change;
					$calcValue = $this->round( $_object->getPaiementMontant() / (1+$_object->getTauxDeTVA() ) / $_object->getTauxDeChange(), 2);
					if($value != $calcValue) {
	                	$this->_errorElement->with('HT')->addViolation('"HT" non valide (doit etre "TTC" / (1+Taux de TVA) / "Taux de change" )')->end();
					}
	            }
        	} else {
        		if ( $_object->getMontantHTEnDevise() && $_object->getTauxDeChange()) {
        			$calcValue = $this->round( $_object->getMontantHTEnDevise() / $_object->getTauxDeChange(), 2);
        			if($value != $calcValue) {
        				$this->_errorElement->with('HT')->addViolation('"HT" non valide (doit etre "HT en devise" / "Taux de change" )')->end();
        			}
        		}
        	}
        }
        return $this;
    }

    
    
    public function setHT()
    {
    	/** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\A04283I|\Application\Sonata\ClientOperationsBundle\Entity\A06AIB|\Application\Sonata\ClientOperationsBundle\Entity\A10CAF|\Application\Sonata\ClientOperationsBundle\Entity\AbstractSellEntity */
    	$_object = $this->_object;
    	
    
    	if ($this->_is_validate_import && $_object->getPaiementDate()) {
    		$calcValue = 0;
    		if(method_exists($_object, 'getPaiementMontant')) {
    			if ($_object->getPaiementMontant() && $_object->getTauxDeTVA() && $_object->getTauxDeChange()) {
    				//var HT = montant_TTC / (1 + parseFloat(taux_de_TVA)) / taux_de_change;
    				$calcValue = $this->round( $_object->getPaiementMontant() / (1+$_object->getTauxDeTVA() ) / $_object->getTauxDeChange(), 2);
    				
    			}
    		} else {
    			if ( $_object->getMontantHTEnDevise() && $_object->getTauxDeChange()) {
    				$calcValue = $this->round( $_object->getMontantHTEnDevise() / $_object->getTauxDeChange(), 2);
    				
    			}
    		}
    		
    		$_object->setHT($calcValue);
    	}
    	return $this;
    }
    
    
    public function setHT_02()
    {
    	/** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\A04283I|\Application\Sonata\ClientOperationsBundle\Entity\A06AIB|\Application\Sonata\ClientOperationsBundle\Entity\A10CAF|\Application\Sonata\ClientOperationsBundle\Entity\AbstractSellEntity */
    	$_object = $this->_object;
    	 
    	
    	//_montant_HT_en_devise / _taux_de_change
    
    	if ($this->_is_validate_import) {
    		$calcValue = 0;
    		if ( $_object->getMontantHTEnDevise() && $_object->getTauxDeChange()) {
    			$calcValue = $this->round( $_object->getMontantHTEnDevise() / $_object->getTauxDeChange(), 2);
    		}

    		$_object->setHT($calcValue);
    	}
    	return $this;
    }
    
    
    public function setTVA() {
    	$_object = $this->_object;
    	if ($this->_is_validate_import && $_object->getPaiementDate()) {
    		$_object->setTVA( $this->round( $_object->getHT() * $_object->getTauxDeTVA(), 2 ) );
    	}
    	return $this;
    }
    
    public function formatTauxDeTVA() {
    	
    	
    	$_object = $this->_object;
    	//if ($this->_is_validate_import) {
    		$_object->setTauxDeTVA( $this->round( $_object->getTauxDeTVA(), 4 ) );
    		
    		
    	//}
    	return $this;
    	
    }
    
    
    
    /**
     * @return ErrorElements
     */
    public function validateHT_V05()
    {
        /** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\V05LIC */
        $_object = $this->_object;
        $value = $_object->getHT();
        $deb = $_object->getDEB();


        //Import excel
        if ($this->getValidateImport()) {
            if ($_object->getDEB()){
                /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

                /* @var $em \Doctrine\ORM\EntityManager */
                $em = $doctrine->getManager();

                /* @var $debExped \Application\Sonata\ClientOperationsBundle\Entity\DEBExped */
                $debExped = $em->getRepository('ApplicationSonataClientOperationsBundle:DEBExped')->findOneByClientId($_object->getClientId());

                if ($value != $debExped->getValeurFiscale()){
                    $this->_errorElement->with('HT')->addViolation('Le total DEB dans "V05-LIC" ne correspond pas à celui de "DEB Exped"')->end();
                }
            }
        }
    }

    /**
     * @return ErrorElements
     */
    public function validateMontantTVAFrancaise()
    {
        $value = $this->round($this->_object->getMontantTVAFrancaise());
        if ($value) {
        	
        	// var montant_TVA_francaise = (montant_HT_en_devise_X_m * taux_de_TVA_X_m) / m / m;

           /*  if (($value - $this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA()) > static::EPSILON) {
                $this->_errorElement->with('montant_TVA_francaise')->addViolation('"Montant TVA française" non valide (doit etre "Montant HT en devise" * "Taux de TVA / 100")')->end();
            } */
			
        	//Montant HT en devise * 100000" * "Taux de TVA * 100000") / 100000 / 100000 = Montant HT en devise * "Taux de TVA
        	
        	$m = 100000;
        	$calcValue = $this->round( ( ($this->_object->getMontantHTEnDevise()*$m) * ($this->_object->getTauxDeTVA()*$m) ) / $m / $m, 2);
        	$calcValue2 = $this->round($this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA(), 2);
        	if ($calcValue2 != $calcValue) {
        		$this->_errorElement->with('montant_TVA_francaise')->addViolation('"Montant TVA française" non valide (doit etre ("Montant HT en devise * 100000" * "Taux de TVA * 100000") / 100000 / 100000")')->end();
        	}
        	
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMontantTTC()
    {
        $value = $this->_object->getMontantTTC();
        if ($value) {
            if (($value - ($this->_object->getMontantHTEnDevise() + $this->_object->getMontantTVAFrancaise())) > static::EPSILON) {
                $this->_errorElement->with('montant_TTC')->addViolation('"Montant TTC" non valide (doit etre "Montant HT en devise" + "Montant TVA française")')->end();
            }
        }

        return $this;
    }

    /**
     * @param $value
     * @param int $precision
     * @return float
     */
    protected function round($value, $precision = 2)
    {
        return round($value, $precision);
    }

    /**
     * @return ErrorElements
     */
    public function validateNoTVATiers()
    {
        $value = $this->_object->getNoTVATiers();
        if ($value) {
            if (!preg_match('/^FR.*/', $value)) {
                $this->_errorElement->with('no_TVA_tiers')->addViolation('"N° TVA Tiers" should begin with "FR"')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validatePaiementMontant()
    {
        $value = $this->_object->getPaiementMontant();
        if ($value) {
            if (!$this->_object->getPaiementDevise() && !$this->getValidateImport()) {
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Devise" can\'t be empty in case if "Montant payé" is not empty')->end();
            }

            if (!$this->_object->getPaiementDate()) {
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Date" can\'t be empty in case if "Montant payé" is not empty')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMois()
    {
        list($current_year, $current_month) = explode('-', date('Y-m', strtotime('now' . (date('d') < 25 ? ' -1 month' : ''))));

        $value = $this->_object->getMois();
        if ($value) {
            if ($value instanceof \DateTime) {
                $month = $value->format('n');
                $year = $value->format('Y');
            } else {
                $month = $value['month'];
                $year = $value['year'];
            }

            if (!($year == $current_year && $month == $current_month)) {

                $this->_errorElement->with('mois')->addViolation('Mois TVA = ' . $this->formatMonth($month) . '-' . $this->formatYear($year) . ' au lieu de ' . $this->formatMonth($current_month) . '-' . $this->formatYear($current_year))->end();
            }
        }
        return $this;
    }


    /**
     * @param $index
     * @return ErrorElements
     */
    public function validateNLigne($index)
    {
        $value = $this->_object->getNLigne();
        if ($value && $value != $index) {

            $this->_errorElement->with('n_ligne')->addViolation('Numérotation illogique (Should be sequential: 1, 2, 3, 4, ...)')->end();
        }

        return $this;
    }

    /**
     * @param $value
     * @return string
     */
    public function formatYear($value)
    {
        return substr($value, -2);
    }

    /**
     * @param $value
     * @return string
     */
    public function formatMonth($value)
    {
        return sprintf('%02d', $value);
    }

    /**
     * @return ErrorElements
     */
    public function validateMoisComplementaire()
    {
        $value = $this->_object->getMois();
        if ($value == $this->_object->getMoisComplementaire()) {
            $this->_errorElement->with('mois_complementaire')->addViolation('"Mois Complementaire" should be different that "Mois"')->end();
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateDevise()
    {
        /** @var $_object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\V01TVA */
        $_object = $this->_object;

        if ($_object->getDevise()) {
            $value = $_object->getDevise()->getAlias();
            if ($value != static::Device) {
                /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
                $em = $doctrine->getManager();
                /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
                $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($_object->getDatePieceFormat());

                $error = true;
                if ($devise) {
                    $method = 'getMoney' . $value;
                    if (method_exists($devise, $method)) {
                        $error = !$devise->$method();
                    }
                }
                if ($error) {
                    $this->_errorElement->with('devise')->addViolation('No Devise for this month')->end();
                }
            }
        }

        return $this;
    }


    /**
     * http://redmine.testenm.com/issues/1376
     * @return ErrorElements
     */
    public function validatePaiementDateCloneMois()
    {
        /** @var $object \Application\Sonata\ClientOperationsBundle\Entity\A02TVA|\Application\Sonata\ClientOperationsBundle\Entity\V01TVA */
        $object = $this->_object;

        if ($object->getPaiementDate() && !$object->getMois()) {
            $object->setMois($object->getPaiementDate());
        }
        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateNomenclature()
    {
        $regime = $this->_object->getRegime();
        if ($regime == 31 && $this->_object->getNomenclature()) {

            $this->_errorElement->with('nomenclature')->addViolation('Cellules devant être vide car régime = ' . $regime)->end();
        }

        return $this;
    }

    /**
     * @param bool $value
     * @return ErrorElements
     */
    public function setValidateImport($value = true)
    {
        $this->_is_validate_import = $value;
        return $this;
    }
    
    
    
    public function setDatePiece() {
    	
    	static $date = null;
    	
    	//_ If day(now) >= 1 and < 25 .... Date Pièce = 01/ Month(Now) / Year(Now)
		//_ If day(now) >= 25   Date Pièce = 01/Month(Now) + 1 / Year(Now) ( If Month(Now + 1 = 13 ... Put Month = 1 and Year = Year + 1

    	if(!$this->_object->getDatePiece()) {
    		if(date('d') >= 1 && date('d') < 25) {
    			$ts = date('Y-m') . '-01';
    		} else if(date('d' >= 25)) {
    			$ts = 'now +1 month';
    		}
    		if(is_null($date)) {
    			$date = new \DateTime($ts);
    		}
    		$this->_object->setDatePiece($date);
    	}
    	
    	return $this;
    }
    

    /**
     * @return bool
     */
    protected function getValidateImport()
    {
        return $this->_is_validate_import;
    }
}

