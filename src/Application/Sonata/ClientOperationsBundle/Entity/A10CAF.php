<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\A10CAF
 *
 * @ORM\Table("et_operations_A10CAF")
 * @ORM\Entity
 */
class A10CAF extends AbstractBuyEntity
{

    /**
     * @var float $HT
     *
     * @ORM\Column(name="HT", type="float", nullable=true)
     */
    private $HT;


    /**
     * Set HT
     *
     * @param float $hT
     * @return A10CAF
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


}