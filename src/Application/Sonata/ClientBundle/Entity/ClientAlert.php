<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\ClientAlert
 *
 * @ORM\Table("et_client_alert")
 * @ORM\Entity
 */
class ClientAlert
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="alertes")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;


    /**
     * @var integer $tabs
     *
     * @ORM\ManyToOne(targetEntity="ListClientTabs")
     * @ORM\JoinColumn(name="tabs_id",  referencedColumnName="id")
     */
    private $tabs;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;


    /**
     * @var boolean $is_blocked
     *
     * @ORM\Column(name="is_blocked", type="boolean", nullable=true)
     */
    private $is_blocked = NULL;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getText();
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
     * Set tabs
     *
     * @param string $tabs
     * @return ClientAlert
     */
    public function setTabs($tabs)
    {
        $this->tabs = $tabs;

        return $this;
    }

    /**
     * Get tabs
     *
     * @return string
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return ClientAlert
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set is_blocked
     *
     * @param boolean $isBlocked
     * @return ClientAlert
     */
    public function setIsBlocked($isBlocked)
    {
        $this->is_blocked = $isBlocked;

        return $this;
    }

    /**
     * Get is_blocked
     *
     * @return boolean
     */
    public function getIsBlocked()
    {
        return $this->is_blocked;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return ClientAlert
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