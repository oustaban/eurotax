<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V01TVA
 *
 * @ORM\Table("et_operations_V01TVA")
 * @ORM\Entity
 */
class V01TVA extends AbstractSellEntity
{

    /**
     * @var string $no_TVA_tiers
     *
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;


    /**
     * @var float $taux_de_TVA
     *
     * @ORM\Column(name="taux_de_TVA", type="float")
     */
    private $taux_de_TVA;

    /**
     * @var float $montant_TVA_francaise
     *
     * @ORM\Column(name="montant_TVA_francaise", type="float")
     */
    private $montant_TVA_francaise;

    /**
     * @var float $montant_TTC
     *
     * @ORM\Column(name="montant_TTC", type="float")
     */
    private $montant_TTC;

    /**
     * @var float $paiement_montant
     *
     * @ORM\Column(name="paiement_montant", type="float")
     */
    private $paiement_montant;

    /**
     * @var integer $paiement_devise_id
     *
     * @ORM\Column(name="paiement_devise_id", type="integer")
     */
    private $paiement_devise_id;

    /**
     * @var \DateTime $paiement_date
     *
     * @ORM\Column(name="paiement_date", type="date")
     */
    private $paiement_date;

    /**
     * @var float $TVA
     *
     * @ORM\Column(name="TVA", type="float")
     */
    private $TVA;


    /**
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V01_TVA
     */
    public function setNoTVATiers($noTVATiers)
    {
        $this->no_TVA_tiers = $noTVATiers;

        return $this;
    }

    /**
     * Get no_TVA_tiers
     *
     * @return string
     */
    public function getNoTVATiers()
    {
        return $this->no_TVA_tiers;
    }


    /**
     * Set taux_de_TVA
     *
     * @param float $tauxDeTVA
     * @return V01_TVA
     */
    public function setTauxDeTVA($tauxDeTVA)
    {
        $this->taux_de_TVA = $tauxDeTVA;

        return $this;
    }

    /**
     * Get taux_de_TVA
     *
     * @return float
     */
    public function getTauxDeTVA()
    {
        return $this->taux_de_TVA;
    }

    /**
     * Set montant_TVA_francaise
     *
     * @param float $montantTVAFrancaise
     * @return V01_TVA
     */
    public function setMontantTVAFrancaise($montantTVAFrancaise)
    {
        $this->montant_TVA_francaise = $montantTVAFrancaise;

        return $this;
    }

    /**
     * Get montant_TVA_francaise
     *
     * @return float
     */
    public function getMontantTVAFrancaise()
    {
        return $this->montant_TVA_francaise;
    }

    /**
     * Set montant_TTC
     *
     * @param float $montantTTC
     * @return V01_TVA
     */
    public function setMontantTTC($montantTTC)
    {
        $this->montant_TTC = $montantTTC;

        return $this;
    }

    /**
     * Get montant_TTC
     *
     * @return float
     */
    public function getMontantTTC()
    {
        return $this->montant_TTC;
    }

    /**
     * Set paiement_montant
     *
     * @param float $paiementMontant
     * @return V01_TVA
     */
    public function setPaiementMontant($paiementMontant)
    {
        $this->paiement_montant = $paiementMontant;

        return $this;
    }

    /**
     * Get paiement_montant
     *
     * @return float
     */
    public function getPaiementMontant()
    {
        return $this->paiement_montant;
    }

    /**
     * Set paiement_devise_id
     *
     * @param integer $paiementDeviseId
     * @return V01_TVA
     */
    public function setPaiementDeviseId($paiementDeviseId)
    {
        $this->paiement_devise_id = $paiementDeviseId;

        return $this;
    }

    /**
     * Get paiement_devise_id
     *
     * @return integer
     */
    public function getPaiementDeviseId()
    {
        return $this->paiement_devise_id;
    }

    /**
     * Set paiement_date
     *
     * @param \DateTime $paiementDate
     * @return V01_TVA
     */
    public function setPaiementDate($paiementDate)
    {
        $this->paiement_date = $paiementDate;

        return $this;
    }

    /**
     * Get paiement_date
     *
     * @return \DateTime
     */
    public function getPaiementDate()
    {
        return $this->paiement_date;
    }

    /**
     * Set TVA
     *
     * @param float $TVA
     * @return V01_TVA
     */
    public function setTVA($TVA)
    {
        $this->TVA = $TVA;

        return $this;
    }

    /**
     * Get TVA
     *
     * @return float
     */
    public function getTVA()
    {
        return $this->TVA;
    }
}