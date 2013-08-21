<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Application\Sonata\ClientBundle\Entity\Contact
 *
 * @ORM\Table("et_contact")
 * @ORM\Entity
 */
class Contact
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="contacts")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;

    /**
     * @var integer $civilite_id
     *
     * @ORM\ManyToOne(targetEntity="ListCivilites")
     * @ORM\JoinColumn(name="civilite_id",  referencedColumnName="id")
     */
    private $civilite;

    /**
     * @var string $nom
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=100)
     */
    private $prenom;

    /**
     * @var string $telephone_1
     *
     * @ORM\Column(name="telephone_1", type="string", length=20)
     */
    private $telephone_1;

    /**
     * @var string $telephone_2
     *
     * @ORM\Column(name="telephone_2", type="string", length=20, nullable=true)
     */
    private $telephone_2;

    /**
     * @var string $fax
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;

    /**
     * @var string $email
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var integer $affichage_facture_id
     *
     * @ORM\Column(name="affichage_facture_id", type="integer", nullable=true)
     */
    private $affichage_facture_id;


    /**
     * @var string $raison_sociale_societe
     *
     *@ORM\Column(name="raison_sociale_societe", type="string", length=100)
     */
    private $raison_sociale_societe;


    
    /**
     * @var string $note
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;
    
    
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
     * Set nom
     *
     * @param string $nom
     * @return Contact
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Contact
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set telephone_1
     *
     * @param string $telephone1
     * @return Contact
     */
    public function setTelephone1($telephone1)
    {
        $this->telephone_1 = $telephone1;

        return $this;
    }

    /**
     * Get telephone_1
     *
     * @return string
     */
    public function getTelephone1()
    {
        return $this->telephone_1;
    }

    /**
     * Set telephone_2
     *
     * @param string $telephone2
     * @return Contact
     */
    public function setTelephone2($telephone2)
    {
        $this->telephone_2 = $telephone2;

        return $this;
    }

    /**
     * Get telephone_2
     *
     * @return string
     */
    public function getTelephone2()
    {
        return $this->telephone_2;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Contact
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Contact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * Set civilite
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCivilites $civilite
     * @return Contact
     */
    public function setCivilite(\Application\Sonata\ClientBundle\Entity\ListCivilites $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCivilites
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    public function __toString()
    {

        return $this->getNom() ? : '-';
    }

    /**
     * Set raison_sociale_societe
     *
     * @param string $raisonSocialeSociete
     * @return Contact
     */
    public function setRaisonSocialeSociete($raisonSocialeSociete)
    {
        $this->raison_sociale_societe = $raisonSocialeSociete;

        return $this;
    }

    /**
     * Get raison_sociale_societe
     *
     * @return string
     */
    public function getRaisonSocialeSociete()
    {
        return $this->raison_sociale_societe;
    }



    /**
     * Set affichage_facture_id
     *
     * @param integer $affichageFactureId
     * @return Contact
     */
    public function setAffichageFactureId($affichageFactureId)
    {
        $this->affichage_facture_id = $affichageFactureId;

        return $this;
    }

    /**
     * Get affichage_facture_id
     *
     * @return integer
     */
    public function getAffichageFactureId()
    {
        return $this->affichage_facture_id;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return Contact
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
    * Set commentaire
    *
    * @param string $commentaire
    * @return Contanct
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
    
}