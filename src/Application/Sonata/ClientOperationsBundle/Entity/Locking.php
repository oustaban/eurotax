<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\Locking
 *
 * @ORM\Table("et_operations_locking")
 * @ORM\Entity
 */
class Locking
{
    /**
     * @var integer $client_id
     *
     * @ORM\Id
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;

    /**
     * @var integer $month
     *
     * @ORM\Id
     * @ORM\Column(name="month", type="integer")
     */
    private $month;


    /**
     * @param $client_id
     * @param $month
     */
    public function __construct($client_id = null, $month = null)
    {
        $this->client_id = $client_id;
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getClientId().' x '.$this->getMonth();
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Locking
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

    /**
     * Set month
     *
     * @param integer $month
     * @return Locking
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }
}
