<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientBundle\Entity\ListDevises;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\A06AIB
 *
 * @ORM\Table("et_operations_A06AIB")
 * @ORM\Entity
 */
class A06AIB extends AbstractBuyEntity
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
     * @var string $taux_de_change
     *
     * @ORM\Column(name="taux_de_change", type="float", nullable=true)
     */
    private $taux_de_change;

    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="float", nullable=true)
     */
    private $regime;

    /**
     * @var float $HT
     *
     * @ORM\Column(name="HT", type="float")
     */
    private $HT;

    /**
     * @var boolean $DEB
     *
     * @ORM\Column(name="DEB", type="boolean", nullable=true)
     */
    private $DEB;

    /**
     * @var float $taux_de_TVA
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="taux_de_TVA", type="float")
     */
    private $taux_de_TVA;

    /**
     * @var float $TVA
     *
     * @ORM\Column(name="TVA", type="float", nullable=true)
     */
    private $TVA;



    public function __construct(){
        parent::__construct();

        $this->devise = ListDevises::getDefault();
    }

    /**
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return A06AIB
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
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return A06AIB
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
     * Set regime
     *
     * @param string $regime
     * @return A06AIB
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;

        return $this;
    }

    /**
     * Get regime
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * Set HT
     *
     * @param float $hT
     * @return A06AIB
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
     * Set DEB
     *
     * @param boolean $dEB
     * @return A06AIB
     */
    public function setDEB($dEB)
    {
        $this->DEB = $dEB;

        return $this;
    }

    /**
     * Get DEB
     *
     * @return boolean
     */
    public function getDEB()
    {
        return $this->DEB;
    }

    /**
     * Set devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return A06AIB
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

    /**
     * Set taux_de_TVA
     *
     * @param float $tauxDeTVA
     * @return A06AIB
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
     * Set TVA
     *
     * @param float $tVA
     * @return A06AIB
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
}