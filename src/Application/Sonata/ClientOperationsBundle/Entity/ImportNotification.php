<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Storage of large data of excel import use to flag notification when the background process is finished.
 * 
 * Application\Sonata\ClientOperationsBundle\Entity\ImportNotification
 *
 * @ORM\Table("et_operations_import_notification")
 * @ORM\Entity
 */
class ImportNotification
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
     * Process id
     * 
     * @var integer $pid
     *
     * @ORM\Column(name="pid", type="integer")
     */
    private $pid;

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
     * @var boolean $notification_sent
     *
     * @ORM\Column(name="notification_sent", type="boolean")
     */
    private $notification_sent = false;


    
    /**
     * @var integer $data
     *
     *
     * @ORM\Column(name="data", type="text", nullable=true)
     */
    private $data;
    
    
    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getPid();
    }

    /**
     *
     */
    public function __construct(){
        $this->ts = new \DateTime();
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
     * Set pid
     *
     * @param int $pid
     * @return ImportNotification
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $date
     * @return ImportNotification
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
     * @return ImportNotification
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
     * @return ImportNotification
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
     * Set notification_sent
     *
     * @param boolean $notification_sent
     * @return ImportNotification
     */
    public function setNotificationSent($notification_sent)
    {
        $this->notification_sent = $notification_sent;

        return $this;
    }

    /**
     * Get notification_sent
     *
     * @return boolean
     */
    public function getNotificationSent()
    {
        return $this->notification_sent;
    }
    
    
    
    /**
     * Set data
     *
     * @param string $data
     * @return ImportNotification
     */
    public function setData($data)
    {
    	$this->data = serialize($data);
    
    	return $this;
    }
    
    /**
     * Get data
     *
     * @return mixed
     */
    public function getData()
    {
    	return unserialize($this->data);
    }
}