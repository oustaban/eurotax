<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */
    private $client_id;

    /**
     * @var integer $civilite_id
     *
     * @ORM\ManyToOne(targetEntity="ListCivilites", inversedBy="client")
     * @ORM\JoinColumn(name="civilite_id",  referencedColumnName="id")
     */
    private $civilite;

    /**
     * @var string $nom
     *
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
     * @ORM\Column(name="telephone_2", type="string", length=20)
     */
    private $telephone_2;

    /**
     * @var string $fax
     *
     * @ORM\Column(name="fax", type="string", length=20)
     */
    private $fax;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=100)
     */
    private $email;

    /**
     * @var boolean $affichage_facture
     *
     * @ORM\Column(name="affichage_facture", type="boolean", nullable=true)
     */
    private $affichage_facture;


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
     * Set affichage_facture
     *
     * @param boolean $affichageFacture
     * @return Contact
     */
    public function setAffichageFacture($affichageFacture)
    {
        $this->affichage_facture = $affichageFacture;

        return $this;
    }

    /**
     * Get affichage_facture
     *
     * @return boolean
     */
    public function getAffichageFacture()
    {
        return $this->affichage_facture;
    }


    /**
     * Set civilite
     *
     * @param Application\Sonata\ClientBundle\Entity\ListCivilites $civilite
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
     * @return Application\Sonata\ClientBundle\Entity\ListCivilites
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    public function __toString(){

        return $this->getNom() ? : '-';
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Contact
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