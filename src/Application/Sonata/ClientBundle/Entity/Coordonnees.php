<?php

namespace Application\Sonata\ClientBundle\Entity;

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
     * @var integer $client_id
     *
     * @ORM\Column(name="client_id", type="integer")
     */

    private $client_id;

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
     * @ORM\Column(name="pays_id", type="string", length=2, nullable=true)
     */
    private $pays_id;


    /**
     * @var string $no_de_compte
     *
     * @ORM\Column(name="no_de_compte", type="string", length=100, nullable=true)
     */
    private $no_de_compte;


    /**
     * @var string $code_swift
     *
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
     * @var string $SEPA
     *
     * @ORM\Column(name="SEPA", type="string", length=100, nullable=true)
     */
    private $SEPA;


    /**
     * @var string $orders
     *
     * @ORM\Column(name="orders", type="integer", nullable=true)
     */
    private $orders;

    /**
     * @return string
     */
    public function __toString(){

        return $this->getNom()?:'-';
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
     * Set pays_id
     *
     * @param string $paysId
     * @return Coordonnees
     */
    public function setPaysId($paysId)
    {
        $this->pays_id = $paysId;
    
        return $this;
    }

    /**
     * Get pays_id
     *
     * @return string 
     */
    public function getPaysId()
    {
        return $this->pays_id;
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
     * Set SEPA
     *
     * @param string $sEPA
     * @return Coordonnees
     */
    public function setSEPA($sEPA)
    {
        $this->SEPA = $sEPA;
    
        return $this;
    }

    /**
     * Get SEPA
     *
     * @return string 
     */
    public function getSEPA()
    {
        return $this->SEPA;
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
     * Set client_id
     *
     * @param integer $clientId
     * @return Coordonnees
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
}