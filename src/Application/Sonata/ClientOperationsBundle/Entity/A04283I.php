<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientBundle\Entity\ListDevises;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\A04283I
 *
 * @ORM\Table("et_operations_A04283I")
 * @ORM\Entity
 */
class A04283I extends AbstractBuyEntity
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
    }

    /**
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return A04283I
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
     * @return A04283I
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
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return A04283I
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
     * @return A04283I
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
     * @return A04283I
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
     * Set devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return A04283I
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