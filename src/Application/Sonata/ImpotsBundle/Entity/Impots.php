<?php
namespace Application\Sonata\ImpotsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ImpotsBundle\Entity\Impots
 *
 * @ORM\Table("et_impots")
 * @ORM\Entity
 */
class Impots
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
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_de_la_banque", type="string", length=100)
     */
    private $nom_de_la_banque;

    /**
     * @var string
     *
     * @ORM\Column(name="no_de_compte", type="string", length=200)
     */
    private $no_de_compte;

    /**
     * @var string
     *
     * @ORM\Column(name="code_swift", type="string", length=200)
     */
    private $code_swift;

    /**
     * @var string
     *
     * @ORM\Column(name="IBAN", type="string", length=200)
     */
    private $IBAN;

    /**
     * @var string
     *
     * @ORM\Column(name="SEPA", type="string", length=200)
     */
    private $SEPA;

    /**
     * @var string $adresse_1
     *
     * @ORM\Column(name="adresse_1", type="string", length=100)
     */
    private $adresse_1;

    /**
     * @var string $adresse_2
     *
     * @ORM\Column(name="adresse_2", type="string", length=100)
     */
    private $adresse_2;

    /**
     * @var string $code_postal
     *
     * @ORM\Column(name="code_postal", type="string", length=20)
     */
    private $code_postal;

    /**
     * @var string $ville
     *
     * @ORM\Column(name="ville", type="string", length=50)
     */
    private $ville;

    /**
     * @var integer $pays_id
     *
     * @ORM\Column(name="pays_id", type="string",  length=2)
     */
    private $pays_id;

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
     * @return Impots
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
     * Set nom_de_la_banque
     *
     * @param string $nomDeLaBanque
     * @return Impots
     */
    public function setNomDeLaBanque($nomDeLaBanque)
    {
        $this->nom_de_la_banque = $nomDeLaBanque;

        return $this;
    }

    /**
     * Get nom_de_la_banque
     *
     * @return string
     */
    public function getNomDeLaBanque()
    {
        return $this->nom_de_la_banque;
    }

    /**
     * Set no_de_compte
     *
     * @param string $noDeCompte
     * @return Impots
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
     * Set code_swift
     *
     * @param string $codeSwift
     * @return Impots
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
     * Set IBAN
     *
     * @param string $iBAN
     * @return Impots
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
     * @return Impots
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
     * Set adresse_1
     *
     * @param string $adresse1
     * @return Impots
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
     * @return Impots
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
     * @return Impots
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
     * @return Impots
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
     * @return Impots
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
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNom() ?: '-';
    }
}