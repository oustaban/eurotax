<?php

namespace Application\Sonata\ClientOperationsBundle\Admin\Validate;

use Sonata\AdminBundle\Validator\ErrorElement;

class ErrorElements
{
    protected $_errorElement;
    protected $_object;


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
     * @param $value
     * @param int $precision
     * @return float
     */
    protected function getNumberRound($value, $precision = 2)
    {
        return round($value, $precision);
    }

    /**
     * @return ErrorElements
     */
    public function validateTauxDeChange()
    {
        if ($this->_object->getPaiementMontant() && !$this->_object->getTauxDeChange()) {

            $currency = $this->_object->getDevise()->getAlias();

            $taux_de_change = 0;
            if ($currency == 'euro') {
                $taux_de_change = 1;
            } else {
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
                $em = $doctrine->getManager();
                /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
                $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($this->_object->getDatePieceFormat());

                if ($devise) {
                    $method = 'getMoney' . ucfirst($currency);
                    if (method_exists($devise, $method)) {
                        $taux_de_change = $devise->$method();
                    }
                }
            }

            $this->_object->setTauxDeChange($taux_de_change);

            if (empty($taux_de_change)) {
                $this->_errorElement->with('taux_de_change')->addViolation('Wrong "Taux de change"')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateHT()
    {
        $value = $this->_object->getHT();
        if ($value) {
            if ($this->_object->getTauxDeChange() && !($value == $this->getNumberRound($this->_object->getMontantHTEnDevise() / $this->_object->getTauxDeChange()))) {
                $this->_errorElement->with('HT')->addViolation('Wrong "HT" (must be "Montant HT en devise" / "Taux de change")')->end();
            }
        }
        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMontantTVAFrancaise()
    {
        $value = $this->_object->getMontantTVAFrancaise();
        if ($value) {

            if (!($value == $this->getNumberRound($this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA()))) {
                $this->_errorElement->with('montant_TVA_francaise')->addViolation('Wrong "Montant TVA française" (must be "Montant HT en devise" * "Taux de TVA / 100")')->end();
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
            if (!($value == $this->getNumberRound($this->_object->getMontantHTEnDevise() + $this->_object->getMontantTVAFrancaise()))) {
                $this->_errorElement->with('montant_TTC')->addViolation('Wrong "Montant TTC" (must be "Montant HT en devise" + "Montant TVA française")')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMoisIsNotNULL()
    {
        $value = $this->_object->getMois();
        if (!$value) {
            $this->_errorElement->with('mois')->addViolation('"Mois" should not be null')->end();
        }

        return $this;
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
    public function validatePaiementMontantMois()
    {
        $value = $this->_object->getPaiementMontant();
        if ($value) {
            if (!$this->_object->getPaiementDevise()) {
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Devise" can\'t be empty')->end();
            }

            if (!$this->_object->getPaiementDate()) {
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Date" can\'t be empty')->end();
            }

            $this->validateMois();

        }

        return $this;
    }


    /**
     * @return ErrorElements
     */
    public function validateMois()
    {
        $value = $this->_object->getMois();
        if (!$value) {
            if ($value instanceof \DateTime) {
                $month = $value->format('n');
                $year = $value->format('Y');
            } else {
                $month = $value['month'];
                $year = $value['year'];
            }

            if ($year . '-' . $month != date('Y-n', strtotime('-1 month'))) {
                $this->_errorElement->with('mois')->addViolation('Wrong "Mois"')->end();
            }
        }

        return $this;
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
        if ($this->_object->getDevise()) {
            $value = $this->_object->getDevise()->getAlias();
            if ($value != 'euro') {
                /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
                $em = $doctrine->getManager();
                /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
                $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($this->_object->getDatePieceFormat());

                $error = true;
                if ($devise) {
                    $method = 'getMoney' . ucfirst($value);
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
}

