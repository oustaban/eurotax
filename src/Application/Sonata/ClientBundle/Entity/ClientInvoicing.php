<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\ClientInvoicing
 *
 * @ORM\Table("et_client_invoicing")
 * @ORM\Entity()
 */
class ClientInvoicing
{

    /**
     * @var Client $client
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="Client", inversedBy="invoicing")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;


    /**
     * @var integer $facturation_du_client
     *
     * @ORM\ManyToOne(targetEntity="ListFacturationDuClients")
     * @ORM\JoinColumn(name="facturation_du_client_id",  referencedColumnName="id")
     */
    private $facturation_du_client;


    /**
     * @var string $min
     *
     * @ORM\Column(name="min", type="integer", nullable=true)
     */
    private $min;

    /**
     * @var string $max
     *
     * @ORM\Column(name="max", type="integer", nullable=true)
     */
    private $max;

    /**
     * @var string $facturation_davance_value
     *
     * @ORM\Column(name="facturation_davance_value", type="float", nullable=true)
     */
    private $facturation_davance_value;

    /**
     * @var integer $facturation_davance
     *
     * @ORM\ManyToOne(targetEntity="ListFacturationDavances")
     * @ORM\JoinColumn(name="facturation_davance_id",  referencedColumnName="id")
     */
    private $facturation_davance;

    /**
     * @var integer $paiement
     *
     * @ORM\ManyToOne(targetEntity="ListPaiements")
     * @ORM\JoinColumn(name="paiement_id",  referencedColumnName="id")
     */
    private $paiement;

    /**
     * @var float $libelle
     *
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;


    /**
     * @return int
     */
    public function __toString()
    {
        return $this->getClient()->getId();
    }


    /**
     * Set min
     *
     * @param integer $min
     * @return ClientInvoicing
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return integer
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param integer $max
     * @return ClientInvoicing
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set facturation_du_client
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListFacturationDuClients $facturationDuClient
     * @return ClientInvoicing
     */
    public function setFacturationDuClient(\Application\Sonata\ClientBundle\Entity\ListFacturationDuClients $facturationDuClient = null)
    {
        $this->facturation_du_client = $facturationDuClient;

        return $this;
    }

    /**
     * Get facturation_du_client
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListFacturationDuClients
     */
    public function getFacturationDuClient()
    {
        return $this->facturation_du_client;
    }

    /**
     * Set facturation_davance
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListFacturationDavances $facturationDavance
     * @return ClientInvoicing
     */
    public function setFacturationDavance(\Application\Sonata\ClientBundle\Entity\ListFacturationDavances $facturationDavance = null)
    {
        $this->facturation_davance = $facturationDavance;

        return $this;
    }

    /**
     * Get facturation_davance
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListFacturationDavances
     */
    public function getFacturationDavance()
    {
        return $this->facturation_davance;
    }

    /**
     * Set facturation_davance_value
     *
     * @param float $facturationDavanceValue
     * @return ClientInvoicing
     */
    public function setFacturationDavanceValue($facturationDavanceValue)
    {
        $this->facturation_davance_value = $facturationDavanceValue;

        return $this;
    }

    /**
     * Get facturation_davance_value
     *
     * @return float
     */
    public function getFacturationDavanceValue()
    {
        return $this->facturation_davance_value;
    }

    /**
     * Set paiement
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListPaiements $paiement
     * @return ClientInvoicing
     */
    public function setPaiement(\Application\Sonata\ClientBundle\Entity\ListPaiements $paiement = null)
    {
        $this->paiement = $paiement;

        return $this;
    }

    /**
     * Get paiement
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListPaiements
     */
    public function getPaiement()
    {
        return $this->paiement;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return ClientInvoicing
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return ClientInvoicing
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