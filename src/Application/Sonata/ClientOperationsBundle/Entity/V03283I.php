<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V03283I
 *
 * @ORM\Table("et_operations_V03283I")
 * @ORM\Entity
 */
class V03283I extends AbstractSellEntity
{

    /**
     * @var string $no_TVA_tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;


    /**
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V03283I
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

}