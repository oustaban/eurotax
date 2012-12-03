<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Application\Sonata\ClientBundle\Entity\ListDevises;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractSellEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractSellEntity extends AbstractAVEntity
{

    /**
     * @var float $HT
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="HT", type="float", nullable=true)
     */
    private $HT;

    /**
     * @var ListDevises $devise
     *
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListDevises")
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


    public function __construct(){
        parent::__construct();

        $this->devise = ListDevises::getDefault();
    }


    /**
     * Set HT
     *
     * @param float $hT
     * @return AbstractSellEntity
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
     * Set devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return AbstractSellEntity
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
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return AbstractSellEntity
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
     * @return AbstractSellEntity
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
}