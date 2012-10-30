<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V09DES
 *
 * @ORM\Table("et_operations_V09DES")
 * @ORM\Entity
 */
class V09DES extends AbstractSellEntity
{

    /**
     * @var string $no_TVA_tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;


    /**
     * @var string $mois_complementaire
     *
     * @ORM\Column(name="mois_complementaire", type="text", nullable=true)
     */
    private $mois_complementaire;


    /**
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V09DES
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
     * Set mois_complementaire
     *
     * @param string $moisComplementaire
     * @return V09DES
     */
    public function setMoisComplementaire($moisComplementaire)
    {
        $this->mois_complementaire = $moisComplementaire;

        return $this;
    }

    /**
     * Get mois_complementaire
     *
     * @return string
     */
    public function getMoisComplementaire()
    {
        return $this->mois_complementaire;
    }

}