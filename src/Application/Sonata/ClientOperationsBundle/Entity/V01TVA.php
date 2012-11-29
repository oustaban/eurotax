<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientBundle\Entity\ListDevises;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V01TVA
 *
 * @ORM\Table("et_operations_V01TVA")
 * @ORM\Entity
 */
class V01TVA extends AbstractSellEntity
{

    /**
     * @var float $taux_de_TVA
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="taux_de_TVA", type="float")
     */
    private $taux_de_TVA;

    /**
     * @var float $montant_TVA_francaise
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="montant_TVA_francaise", type="float")
     */
    private $montant_TVA_francaise;

    /**
     * @var float $montant_TTC
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="montant_TTC", type="float")
     */
    private $montant_TTC;

    /**
     * @var float $paiement_montant
     *
     * @ORM\Column(name="paiement_montant", type="float", nullable=true)
     */
    private $paiement_montant;

    /**
     * @var integer $paiement_devise
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListDevises", inversedBy="BaseListDevises")
     * @ORM\JoinColumn(name="paiement_devise_id", referencedColumnName="id")
     */
    private $paiement_devise;

    /**
     * @var \DateTime $paiement_date
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="paiement_date", type="date", nullable=true)
     */
    private $paiement_date;

    /**
     * @var float $TVA
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="TVA", type="float")
     */
    private $TVA;

    public function __construct(){
        $this->paiement_devise = ListDevises::getDefault();
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

    /**
     * Set paiement_devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $paiementDevise
     * @return V01TVA
     */
    public function setPaiementDevise(\Application\Sonata\ClientBundle\Entity\ListDevises $paiementDevise = null)
    {
        $this->paiement_devise = $paiementDevise;

        return $this;
    }

    /**
     * Get paiement_devise
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListDevises
     */
    public function getPaiementDevise()
    {
        return $this->paiement_devise;
    }
}