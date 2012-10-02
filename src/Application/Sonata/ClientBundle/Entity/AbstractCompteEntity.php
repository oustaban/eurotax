<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\AbstractCompteEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractCompteEntity
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
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;


    /**
     * @var string $operation
     *
     * @ORM\Column(name="operation", type="string", length=50)
     */
    private $operation;

    /**
     * @var float $montant
     *
     * @ORM\Column(name="montant", type="float", nullable=true)
     */
    private $montant;


    /**
     * @var string $commentaire
     *
     * @ORM\Column(name="commentaire", type="string", length=155)
     */
    private $commentaire;


    /**
     * @var integer $statut_id
     *
     * @ORM\ManyToOne(targetEntity="ListCompteStatuts")
     * @ORM\JoinColumn(name="statut_id" , referencedColumnName="id")
     */
    private $statut;



    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getId()?:'-';
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
     * @return AbstractCompteEntity
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
     * Set date
     *
     * @param \DateTime $date
     * @return AbstractCompteEntity
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
     * Set operation
     *
     * @param string $operation
     * @return AbstractCompteEntity
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
    
        return $this;
    }

    /**
     * Get operation
     *
     * @return string 
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set montant
     *
     * @param float $montant
     * @return AbstractCompteEntity
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return float 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return AbstractCompteEntity
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }



    /**
     * Set statut
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCompteStatuts $statut
     * @return AbstractCompteEntity
     */
    public function setStatut(\Application\Sonata\ClientBundle\Entity\ListCompteStatuts $statut = null)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCompteStatuts
     */
    public function getStatut()
    {
        return $this->statut;
    }
}