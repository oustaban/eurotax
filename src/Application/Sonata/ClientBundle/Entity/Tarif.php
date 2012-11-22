<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Tarif
 *
 * @ORM\Table("et_client_tarif")
 * @ORM\Entity()
 */
class Tarif
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Client $client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="tarifs")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var integer $mode_de_facturation
     *
     * @ORM\ManyToOne(targetEntity="ListModeDeFacturations")
     * @ORM\JoinColumn(name="mode_de_facturation_id",  referencedColumnName="id")
     */

    private $mode_de_facturation;


    /**
     * @var float $value
     *
     * @ORM\Column(name="value", type="float", nullable=true)
     */
    private $value;


    /**
     * @var float $value_percentage
     *
     * @ORM\Column(name="value_percentage", type="float", nullable=true)
     */

    private $value_percentage;




    /**
     * @return float
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set value
     *
     * @param float $value
     * @return Tarif
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set value_percentage
     *
     * @param float $valuePercentage
     * @return Tarif
     */
    public function setValuePercentage($valuePercentage)
    {
        $this->value_percentage = $valuePercentage;

        return $this;
    }

    /**
     * Get value_percentage
     *
     * @return float
     */
    public function getValuePercentage()
    {
        return $this->value_percentage;
    }

    /**
     * Set mode_de_facturation
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListModeDeFacturations $modeDeFacturation
     * @return Tarif
     */
    public function setModeDeFacturation(\Application\Sonata\ClientBundle\Entity\ListModeDeFacturations $modeDeFacturation = null)
    {
        $this->mode_de_facturation = $modeDeFacturation;

        return $this;
    }

    /**
     * Get mode_de_facturation
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListModeDeFacturations
     */
    public function getModeDeFacturation()
    {
        return $this->mode_de_facturation;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return Tarif
     */
    public function setClient(\Application\Sonata\ClientBundle\Entity\Client $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return \Application\Sonata\ClientBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }
}