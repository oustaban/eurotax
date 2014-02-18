<?php

namespace Application\Sonata\ClientBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Coordonnees
 *
 * @ORM\Table("et_coordonnees")
 * @ORM\Entity
 */
class Coordonnees
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
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="coordonnees")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string $adresse_1
     *
     * @ORM\Column(name="adresse_1", type="string", length=100,  nullable=true)
     */
    private $adresse_1;

    /**
     * @var string $adresse_2
     *
     * @ORM\Column(name="adresse_2", type="string", length=100,  nullable=true)
     */
    private $adresse_2;

    /**
     * @var string $code_postal
     *
     * @ORM\Column(name="code_postal", type="string", length=20,  nullable=true)
     */
    private $code_postal;

    /**
     * @var string $ville
     *
     * @ORM\Column(name="ville", type="string", length=50,  nullable=true)
     */
    private $ville;

    /**
     * @var string $pays_id
     *
     * @ORM\ManyToOne(targetEntity="ListCountries")
     * @ORM\JoinColumn(name="pays_id", referencedColumnName="code")
     */
    private $pays;


    /**
     * @var string $no_de_compte
     *
     * @ORM\Column(name="no_de_compte", type="string", length=100, nullable=true)
     */
    private $no_de_compte;


    /**
     * @var string $code_swift
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="code_swift", type="string", length=100)
     */
    private $code_swift;

    /**
     * @var string $IBAN
     *
     * @ORM\Column(name="IBAN", type="string", length=100)
     */
    private $IBAN;


    /**
     * @var string $orders
     *
     * @ORM\Column(name="orders", type="integer", nullable=true)
     */
    private $orders;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getNom() ? : '-';
    }


    public function __construct()
    {
        $this->pays = ListCountries::getDefault();
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
     * Set nom
     *
     * @param string $nom
     * @return Coordonnees
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
     * Set adresse_1
     *
     * @param string $adresse1
     * @return Coordonnees
     */
    public function setAdresse1($adresse1)
    {
        $this->adresse_1 = $adresse1;

        return $this;
    }

    /**
     * Get adresse_1
     *
     * @return string
     */
    public function getAdresse1()
    {
        return $this->adresse_1;
    }

    /**
     * Set adresse_2
     *
     * @param string $adresse2
     * @return Coordonnees
     */
    public function setAdresse2($adresse2)
    {
        $this->adresse_2 = $adresse2;

        return $this;
    }

    /**
     * Get adresse_2
     *
     * @return string
     */
    public function getAdresse2()
    {
        return $this->adresse_2;
    }

    /**
     * Set code_postal
     *
     * @param string $codePostal
     * @return Coordonnees
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get code_postal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Coordonnees
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set no_de_compte
     *
     * @param string $noDeCompte
     * @return Coordonnees
     */
    public function setNoDeCompte($noDeCompte)
    {
        $this->no_de_compte = $noDeCompte;

        return $this;
    }

    /**
     * Get no_de_compte
     *
     * @return string
     */
    public function getNoDeCompte()
    {
        return $this->no_de_compte;
    }

    /**
     * Set IBAN
     *
     * @param string $iBAN
     * @return Coordonnees
     */
    public function setIBAN($iBAN)
    {
        $this->IBAN = $iBAN;

        return $this;
    }

    /**
     * Get IBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }


    /**
     * Set code_swift
     *
     * @param string $codeSwift
     * @return Coordonnees
     */
    public function setCodeSwift($codeSwift)
    {
        $this->code_swift = $codeSwift;

        return $this;
    }

    /**
     * Get code_swift
     *
     * @return string
     */
    public function getCodeSwift()
    {
        return $this->code_swift;
    }


    /**
     * Set orders
     *
     * @param integer $orders
     * @return Coordonnees
     */
    public function setOrders($orders)
    {
        $this->orders = $orders;

        return $this;
    }

    /**
     * Get orders
     *
     * @return integer
     */
    public function getOrders()
    {
        return $this->orders;
    }

    /**
     * Set pays
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCountries $pays
     * @return Coordonnees
     */
    public function setPays(\Application\Sonata\ClientBundle\Entity\ListCountries $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCountries
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return Coordonnees
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