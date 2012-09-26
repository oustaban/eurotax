<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\ListModeDeFacturations
 *
 * @ORM\Table("et_list_mode_de_facturations")
 * @ORM\Entity
 */
class ListModeDeFacturations
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;


    /**
     * @var string $unit
     *
     * @ORM\Column(name="unit", type="string", length=2)
     */
    private $unit;


    /**
     * @var integer $invoice_type
     *
     * @ORM\ManyToOne(targetEntity="ListInvoiceTypes")
     * @ORM\JoinColumn(name="invoice_type_id",  referencedColumnName="id")
     */

    private $invoice_type;


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
     * Set name
     *
     * @param string $name
     * @return ListModeDeFacturations
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return ListModeDeFacturations
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set invoice_type
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListInvoiceTypes $invoiceType
     * @return ListModeDeFacturations
     */
    public function setInvoiceType(\Application\Sonata\ClientBundle\Entity\ListInvoiceTypes $invoiceType = null)
    {
        $this->invoice_type = $invoiceType;

        return $this;
    }

    /**
     * Get invoice_type
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListInvoiceTypes
     */
    public function getInvoiceType()
    {
        return $this->invoice_type;
    }
}