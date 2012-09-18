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
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;

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
     * @ORM\Column(name="value", type="float")
     */

    private $value;


    /**
     * @var float $value_percentage
     *
     * @ORM\Column(name="value_percentage", type="float")
     */

    private $value_percentage;

    /**
     * @var integer $invoice_type
     *
     * @ORM\ManyToOne(targetEntity="ListInvoiceTypes")
     * @ORM\JoinColumn(name="invoice_type_id",  referencedColumnName="id")
     */

    private $invoice_type;


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
     * @param Application\Sonata\ClientBundle\Entity\ListModeDeFacturations $modeDeFacturation
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
     * @return Application\Sonata\ClientBundle\Entity\ListModeDeFacturations
     */
    public function getModeDeFacturation()
    {
        return $this->mode_de_facturation;
    }

    /**
     * Set invoice_type
     *
     * @param Application\Sonata\ClientBundle\Entity\ListInvoiceTypes $invoiceType
     * @return Tarif
     */
    public function setInvoiceType(\Application\Sonata\ClientBundle\Entity\ListInvoiceTypes $invoiceType = null)
    {
        $this->invoice_type = $invoiceType;

        return $this;
    }

    /**
     * Get invoice_type
     *
     * @return Application\Sonata\ClientBundle\Entity\ListInvoiceTypes
     */
    public function getInvoiceType()
    {
        return $this->invoice_type;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Tarif
     */
    public function setClientId($clientId)
    {
        $this->client_id = $clientId;
    
        return $this;
    }

    /**
     * Get client_id
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->client_id;
    }
}