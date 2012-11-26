<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * namespace Application\Sonata\ClientBundle\Entity\Compte
 *
 * @ORM\Table("et_compte")
 * @ORM\Entity
 */
class Compte extends AbstractCompteEntity
{
    /**
     * @var Client $client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="comptes")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;

    /**
     * Set client
     *
     * @param Client $client
     * @return Compte
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}