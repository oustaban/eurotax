<?php

namespace Application\Sonata\ClientBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\NumeroTVA
 *
 * @ORM\Table("et_numero_TVA")
 * @ORM\Entity()
 */
class NumeroTVA
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="documents")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;
    
    
    
    /**
     * Client du Client
     * 
     * @var integer $code
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    

    /**
     * @var string $n_de_TVA
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="n_de_TVA", type="text", nullable=false)
     */
    private $n_de_TVA;


     /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date_de_verification", type="datetime", nullable=true)
     */
    private $date_de_verification;

    
    
    
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
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return Document
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
    
    /**
     * Set code
     *
     * @param float $value
     */
    public function setCode($value)
    {
    	$this->code = $value;
    
    	return $this;
    }
    
    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
    	return $this->code;
    }
    
    
    
    /**
     * Set libelle
     *
     * @param float $value
     */
    public function setNDeTVA($value)
    {
        $this->n_de_TVA = $value;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getNDeTVA()
    {
        return $this->n_de_TVA;
    }

    
    /**
     * Set date_de_verification
     *
     * @param \DateTime $date
     */
    public function setDateDeVerification($date)
    {
    	$this->date_de_verification = $date;
    
    	return $this;
    }
    
    /**
     * Get date_de_verification
     *
     * @return \DateTime
     */
    public function getDateDeVerification()
    {
    	return $this->date_de_verification;
    }
    
       
}