<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V05LIC
 *
 * @ORM\Table("et_operations_V05LIC")
 * @ORM\Entity
 */
class V05LIC extends AbstractSellEntity
{

    /**
     * @var string $no_TVA_tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;


    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="string", length=255, nullable=true)
     */
    private $regime;

    /**
     * @var boolean $DEB
     *
     * @ORM\Column(name="DEB", type="boolean", nullable=true)
     */
    private $DEB;


    /**
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V05LIC
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
     * Set regime
     *
     * @param string $regime
     * @return V05LIC
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
     * Set DEB
     *
     * @param boolean $dEB
     * @return V05LIC
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
}