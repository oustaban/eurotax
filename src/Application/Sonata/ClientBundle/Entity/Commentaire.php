<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Commentaire
 *
 * @ORM\Table("et_commentaire")
 * @ORM\Entity
 */
class Commentaire
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
     *
     * @ORM\ManyToOne(targetEntity="ListCategorieCommentaires", inversedBy="commentaire")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @var string $note
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;


    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getNote()?:'-';
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
     * @return Commentaire
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
     * Set note
     *
     * @param string $note
     * @return Commentaires
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }


    /**
     * Set categorie
     *
     * @param Application\Sonata\ClientBundle\Entity\ListCategorieCommentaires $categorie
     * @return Commentaire
     */
    public function setCategorie(\Application\Sonata\ClientBundle\Entity\ListCategorieCommentaires $categorie = null)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return Application\Sonata\ClientBundle\Entity\ListCategorieCommentaires 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Commentaire
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
}