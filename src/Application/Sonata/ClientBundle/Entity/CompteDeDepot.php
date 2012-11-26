<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * namespace Application\Sonata\ClientBundle\Entity\CompteDeDepot
 *
 * @ORM\Table("et_compte_de_depot")
 * @ORM\Entity
 */
class CompteDeDepot extends AbstractCompteEntity
{
    /**
     * @var Client $client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="comptes_de_depot")
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