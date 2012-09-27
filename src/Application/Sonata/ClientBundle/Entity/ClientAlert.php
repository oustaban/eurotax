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
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;


    /**
     * @var string $tabs
     *
     * @ORM\Column(name="tabs", type="string", length=100)
     */
    private $tabs;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

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
     * Set client_id
     *
     * @param integer $clientId
     * @return ClientAlert
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
}