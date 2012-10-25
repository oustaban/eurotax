<?php

namespace Application\Sonata\ClientOperationsBundle\Admin\Validate;

use Sonata\AdminBundle\Validator\ErrorElement;

class ErrorElements
{
    private static $_instance;
    protected $_errorElement;
    protected $_object;
    protected $_translator;

    private function __construct(ErrorElement $errorElement, $object)
    {
        $this->_errorElement = $errorElement;
        $this->_object = $object;

        $this->_translator = \AppKernel::getStaticContainer()->get('translator');
    }

    /**
     * @param \Sonata\AdminBundle\Validator\ErrorElement $errorElement
     * @param $object
     * @return ErrorElements
     */
    public static function getInstance(ErrorElement $errorElement, $object)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($errorElement, $object);
        }
        return self::$_instance;
    }

    /**
     * @return ErrorElements
     */
    public function validateTauxDeChange()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        if ($object->getPaiementMontant() && !$object->getTauxDeChange()) {

            $currency = $object->getDevise()->getAlias();

            $taux_de_change = 0;
            if ($currency == 'euro') {
                $taux_de_change = 1;
            } else {
                $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
                $em = $doctrine->getManager();
                /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */
                $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($object->getDatePieceFormat());

                if ($devise) {
                    $method = 'getMoney' . ucfirst($currency);
                    if (method_exists($devise, $method)) {
                        $taux_de_change = $devise->$method();
                    }
                }
            }

            $object->setTauxDeChange($taux_de_change);

            if (empty($taux_de_change)) {
                $errorElement->with('taux_de_change')->addViolation('Wrong "Taux de change"')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateHT()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getHT();
        if ($value) {
            if ($object->getTauxDeChange() && !($value == $this->getNumberRound($object->getMontantHTEnDevise() / $object->getTauxDeChange()))) {
                $errorElement->with('HT')->addViolation($this->trans('Wrong "HT" (must be "Montant HT en devise" / "Taux de change")'))->end();
            }
        }
        return $this;
    }


    /**
     * @return ErrorElements
     */
    public function validateMontantTVAFrancaise()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getMontantTVAFrancaise();
        if ($value) {

            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise() * $object->getTauxDeTVA()))) {
                $errorElement->with('montant_TVA_francaise')->addViolation('Wrong "Montant TVA Francaise" (must be "Montant HT en devise" * "Taux de TVA")')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMontantTTC()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getMontantTTC();
        if ($value) {
            if (!($value == $this->getNumberRound($object->getMontantHTEnDevise() + $object->getMontantTVAFrancaise()))) {
                $errorElement->with('montant_TTC')->addViolation('Wrong "Montant TTC" (must be "Montant HT en devise" + "Montant TVA française")')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validateMoisIsNotNULL()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getMois();
        if (!$value) {
            $errorElement->with('mois')->addViolation('"Mois" should not be null')->end();
        }

        return $this;
    }


    public function validateNoTVATiers()
    {

        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getNoTVATiers();
        if ($value) {
            if (!preg_match('/^FR.*/', $value)) {
                $errorElement->with('no_TVA_tiers')->addViolation('"N° TVA Tiers" should begin with "FR"')->end();
            }
        }

        return $this;
    }

    /**
     * @return ErrorElements
     */
    public function validatePaiementMontantMois()
    {
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getPaiementMontant();
        if ($value) {
            if (!$object->getPaiementDevise()) {
                $errorElement->with('paiement_montant')->addViolation('"Paiement Devise" can\'t be empty')->end();
            }

            if (!$object->getPaiementDate()) {
                $errorElement->with('paiement_montant')->addViolation('"Paiement Date" can\'t be empty')->end();
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
        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getMois();
        if (!$value) {
            if ($value instanceof \DateTime) {
                $month = $value->format('n');
                $year = $value->format('Y');
            } else {
                $month = $value['month'];
                $year = $value['year'];
            }

            if ($year . '-' . $month != date('Y-n', strtotime('-1 month'))) {
                $errorElement->with('mois')->addViolation('Wrong "Mois"')->end();
            }
        }

        return $this;
    }


    /**
     * @return ErrorElements
     */
    public function validateMoisComplementaire()
    {

        $errorElement = $this->_errorElement;
        $object = $this->_object;

        $value = $object->getMois();
        if ($value == $object->getMoisComplementaire()) {
            $errorElement->with('mois_complementaire')->addViolation('"Mois Complementaire" should be different that "Mois"')->end();
        }

        return $this;
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
     * @param $value
     */
    protected function trans($value)
    {
        $this->_translator->trans($value);
    }
}
