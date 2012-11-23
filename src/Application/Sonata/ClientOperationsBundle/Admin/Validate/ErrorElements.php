<?php

namespace Application\Sonata\ClientOperationsBundle\Admin\Validate;

use Sonata\AdminBundle\Validator\ErrorElement;

class ErrorElements
{
    const Device = 'EUR';

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

        if ($_object->getPaiementMontant() && !$_object->getTauxDeChange()) {

            $listDevise = $_object->getDevise();
            if ($listDevise) {
                $currency = $listDevise->getAlias();

                $taux_de_change = 0;
                if ($currency == static::Device) {
                    $taux_de_change = 1;
                } else {
                    /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
                    $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
                    /* @var $em \Doctrine\ORM\EntityManager */
                    $em = $doctrine->getManager();
                    /* @var $devise \Application\Sonata\DevisesBundle\Entity\Devises */

                    $devise = $em->getRepository('ApplicationSonataDevisesBundle:Devises')->findOneByDate($_object->getDatePieceFormat());

                    if ($devise) {
                        $method = 'getMoney' . $currency;
                        if (method_exists($devise, $method)) {
                            $taux_de_change = $devise->$method();
                        } else {
                            #TODO send mail is not Devises
                            new \Exception('Currency is not found (Devises): ' . $method);
                        }
                    }
                }

                // setTauxDeChange
                $_object->setTauxDeChange($taux_de_change);

                if (!$taux_de_change) {
                    $this->_errorElement->with('taux_de_change')->addViolation('Wrong "Taux de change"')->end();
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

            //Import excel
            if ($this->getValidateImport()) {
                if ($_object->getTauxDeChange() && round((float)$value - $_object->getMontantHTEnDevise() / $_object->getTauxDeChange(), 9)) {
                    $this->_errorElement->with('HT')->addViolation('Wrong "HT" (must be "Montant HT en devise" / "Taux de change")')->end();
                }
            } //Add & Edit ajax form
            else if ($_object->getTauxDeChange() && $value - $this->round($_object->getMontantHTEnDevise() / $_object->getTauxDeChange())) {

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

            //Import excel
            if ($this->getValidateImport()) {
                if (!($value == $this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA())) {
                    $this->_errorElement->with('montant_TVA_francaise')->addViolation('Wrong "Montant TVA française" (must be "Montant HT en devise" * "Taux de TVA / 100")')->end();
                }
            } //Add & Edit ajax form
            elseif (!($value == $this->round(($this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA())))) {

                $this->_errorElement->with('montant_TVA_francaise')->addViolation('Wrong "Montant TVA française" (must be "Montant HT en devise" * "Taux de TVA / 100")' . $this->round($this->_object->getMontantHTEnDevise() * $this->_object->getTauxDeTVA()))->end();
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

            //Import excel
            if ($this->getValidateImport()) {
                if (!($value == $this->_object->getMontantHTEnDevise() + $this->_object->getMontantTVAFrancaise())) {
                    $this->_errorElement->with('montant_TTC')->addViolation('Wrong "Montant TTC" (must be "Montant HT en devise" + "Montant TVA française")')->end();
                }
            } elseif (!($value == $this->round(($this->_object->getMontantHTEnDevise() + $this->_object->getMontantTVAFrancaise())))) {
                $this->_errorElement->with('montant_TTC')->addViolation('Wrong "Montant TTC" (must be "Montant HT en devise" + "Montant TVA française")')->end();
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
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Devise" can\'t be empty in case if "Paiement Montant" is not empty')->end();
            }

            if (!$this->_object->getPaiementDate()) {
                $this->_errorElement->with('paiement_montant')->addViolation('"Paiement Date" can\'t be empty in case if "Paiement Montant" is not empty')->end();
            }
        }

        return $this;
    }


    /**
     * @return ErrorElements
     */
    public function validateMois()
    {
        $value = $this->_object->getMois();
        if ($value) {
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
     * @param $current_month
     * @param $current_year
     * @return ErrorElements
     */
    public function validateMoisImport($current_month, $current_year)
    {
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
     * @return bool
     */
    public function validateDatePiece()
    {

        $value = $this->_object->getDatePiece();
        if (!$value) {
            $this->_errorElement->with('date_piece')->addViolation('"Date pièce" should not be null')->end();
        }

        return true;
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

    /**
     * @return bool
     */
    protected function getValidateImport()
    {
        return $this->_is_validate_import;
    }
}

