<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\A08IM
 *
 * @ORM\Table("et_operations_A08IM")
 * @ORM\Entity
 */
class A08IM extends AbstractBuyEntity
{

    /**
     * @var float $taux_de_TVA
     *
     * @ORM\Column(name="taux_de_TVA", type="float")
     */
    private $taux_de_TVA;

    /**
     * @var float $TVA
     *
     * @ORM\Column(name="TVA", type="float")
     */
    private $TVA;


    /**
     * Set taux_de_TVA
     *
     * @param float $tauxDeTVA
     * @return A08IM
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
     * @return A08IM
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