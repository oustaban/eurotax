<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientBundle\Entity\ListDevises;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\A02TVA
 *
 * @ORM\Table("et_operations_A02TVA")
 * @ORM\Entity
 */
class A02TVA extends AbstractBuyEntity
{
    /**
     * @var ListDevises $devise
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListDevises", inversedBy="BaseListDevises")
     * @ORM\JoinColumn(name="devise_id", referencedColumnName="id")
     */

    private $devise;

    /**
     * @var float $montant_HT_en_devise
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="montant_HT_en_devise", type="float")
     */
    private $montant_HT_en_devise;

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
     * @var ListDevises $paiement_devise
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListDevises", inversedBy="A02TVA")
     * @ORM\JoinColumn(name="paiement_devise_id", referencedColumnName="id")
     */
    private $paiement_devise;

    /**
     * @var \DateTime $paiement_date
     *
     * @ORM\Column(name="paiement_date", type="date", nullable=true)
     */
    private $paiement_date;


    /**
     * @var string $taux_de_change
     *
     * @ORM\Column(name="taux_de_change", type="float", nullable=true)
     */
    private $taux_de_change;

    /**
     * @var float $HT
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="HT", type="float", nullable=true)
     */
    private $HT;

    /**
     * @var float $TVA
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="TVA", type="float")
     */
    private $TVA;



    public function __construct(){
        parent::__construct();

        $this->devise = ListDevises::getDefault();
        $this->paiement_devise = ListDevises::getDefault();
    }


    /**
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return A02TVA
     */
    public function setMontantHTEnDevise($montantHTEnDevise)
    {
        $this->montant_HT_en_devise = $montantHTEnDevise;

        return $this;
    }

    /**
     * Get montant_HT_en_devise
     *
     * @return float
     */
    public function getMontantHTEnDevise()
    {
        return $this->montant_HT_en_devise;
    }

    /**
     * Set taux_de_TVA
     *
     * @param float $tauxDeTVA
     * @return A02TVA
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
     * @return A02TVA
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
     * @return A02TVA
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
     * @return A02TVA
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
     * @return A02TVA
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
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return A02TVA
     */
    public function setTauxDeChange($tauxDeChange)
    {
        $this->taux_de_change = $tauxDeChange;

        return $this;
    }

    /**
     * Get taux_de_change
     *
     * @return string
     */
    public function getTauxDeChange()
    {
        return $this->taux_de_change;
    }

    /**
     * Set HT
     *
     * @param float $hT
     * @return A02TVA
     */
    public function setHT($hT)
    {
        $this->HT = $hT;

        return $this;
    }

    /**
     * Get HT
     *
     * @return float
     */
    public function getHT()
    {
        return $this->HT;
    }

    /**
     * Set TVA
     *
     * @param float $tVA
     * @return A02TVA
     */
    public function setTVA($tVA)
    {
        $this->TVA = $tVA;

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
     * @return A02TVA
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

    /**
     * Set devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return A02TVA
     */
    public function setDevise(\Application\Sonata\ClientBundle\Entity\ListDevises $devise = null)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListDevises
     */
    public function getDevise()
    {
        return $this->devise;
    }
}