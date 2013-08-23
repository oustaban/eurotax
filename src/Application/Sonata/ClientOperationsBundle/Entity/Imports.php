<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\Imports
 *
 * @ORM\Table("et_operations_imports")
 * @ORM\Entity
 */
class Imports
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
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    

    /**
     * Timestamp
     * 
     * @var \DateTime $ts
     *
     * @ORM\Column(name="ts", type="datetime")
     */
    private $ts;
    
    
    /**
     * @var integer $user_id
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;

    /**
     * @var string $file_name
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $file_name;

    /**
     * @var boolean $is_deleted
     *
     * @ORM\Column(name="is_deleted", type="boolean")
     */
    private $is_deleted = false;


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     *
     */
    public function __construct(){
        $this->date = $this->ts = new \DateTime();
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
     * Set date
     *
     * @param \DateTime $date
     * @return Imports
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $date
     * @return Imports
     */
    public function setTs($date)
    {
    	$this->ts = $date;
    
    	return $this;
    }
    
    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTs()
    {
    	return $this->ts;
    }
    

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Imports
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Imports
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
     * Set file_name
     *
     * @param string $fileName
     * @return Imports
     */
    public function setFileName($fileName)
    {
        $this->file_name = $fileName;

        return $this;
    }

    /**
     * Get file_name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * Set is_deleted
     *
     * @param boolean $is_deleted
     * @return Imports
     */
    public function setIsDeleted($is_deleted)
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * Get is_deleted
     *
     * @return boolean
     */
    public function getIsDeleted()
    {
        return $this->is_deleted;
    }
}