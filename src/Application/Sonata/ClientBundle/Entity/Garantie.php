<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Garantie
 *
 * @ORM\Table("et_garantie")
 * @ORM\Entity
 */
class Garantie
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
     * @var integer $type_garantie
     *
     * @ORM\ManyToOne(targetEntity="ListTypeGaranties", inversedBy="garantie")
     * @ORM\JoinColumn(name="type_garantie_id", referencedColumnName="id")
     */
    private $type_garantie;

    /**
     * @var string $montant
     *
     * @ORM\Column(name="montant", type="float")
     */
    private $montant;

    /**
     * @var integer $devise
     *
     * @ORM\ManyToOne(targetEntity="ListDevises", inversedBy="garantie")
     * @ORM\JoinColumn(name="devise_id", referencedColumnName="id")
     */
    private $devise;

    /**
     * @var string $nom_de_la_banque
     *
     * @ORM\Column(name="nom_de_la_banque", type="string", length=100, nullable=true)
     */
    private $nom_de_la_banque;

    /**
     * @var integer $nom_de_la_banque_id
     *
     * @ORM\Column(name="nom_de_la_banques_id", type="integer", nullable=true)
     */
    private $nom_de_la_banques_id;

    /**
     * @var string $num_de_ganrantie
     *
     * @ORM\Column(name="num_de_ganrantie", type="string", length=50)
     */
    private $num_de_ganrantie;

    /**
     * @var \DateTime $date_demission
     *
     * @ORM\Column(name="date_demission", type="date")
     */
    private $date_demission;

    /**
     * @var \DateTime $date_decheance
     *
     * @ORM\Column(name="date_decheance", type="date")
     */
    private $date_decheance;


    public function __toString()
    {

        return $this->getTypeGarantie();
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
     * @return Garantie
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
     * Set montant
     *
     * @param string $montant
     * @return Garantie
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set nom_de_la_banque
     *
     * @param string $nomDeLaBanque
     * @return Garantie
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
     * Set num_de_ganrantie
     *
     * @param string $numDeGanrantie
     * @return Garantie
     */
    public function setNumDeGanrantie($numDeGanrantie)
    {
        $this->num_de_ganrantie = $numDeGanrantie;

        return $this;
    }

    /**
     * Get num_de_ganrantie
     *
     * @return string
     */
    public function getNumDeGanrantie()
    {
        return $this->num_de_ganrantie;
    }

    /**
     * Set date_demission
     *
     * @param \DateTime $dateDemission
     * @return Garantie
     */
    public function setDateDemission($dateDemission)
    {
        $this->date_demission = $dateDemission;

        return $this;
    }

    /**
     * Get date_demission
     *
     * @return \DateTime
     */
    public function getDateDemission()
    {
        return $this->date_demission;
    }

    /**
     * Set date_decheance
     *
     * @param \DateTime $dateDecheance
     * @return Garantie
     */
    public function setDateDecheance($dateDecheance)
    {
        $this->date_decheance = $dateDecheance;

        return $this;
    }

    /**
     * Get date_decheance
     *
     * @return \DateTime
     */
    public function getDateDecheance()
    {
        return $this->date_decheance;
    }


    /**
     * Set type_garantie
     *
     * @param Application\Sonata\ClientBundle\Entity\ListTypeGaranties $typeGarantie
     * @return Garantie
     */
    public function setTypeGarantie(\Application\Sonata\ClientBundle\Entity\ListTypeGaranties $typeGarantie = null)
    {
        $this->type_garantie = $typeGarantie;

        return $this;
    }

    /**
     * Get type_garantie
     *
     * @return Application\Sonata\ClientBundle\Entity\ListTypeGaranties
     */
    public function getTypeGarantie()
    {
        return $this->type_garantie;
    }

    /**
     * Set devise
     *
     * @param Application\Sonata\ClientBundle\Entity\ListDevises $devise
     * @return Garantie
     */
    public function setDevise(\Application\Sonata\ClientBundle\Entity\ListDevises $devise = null)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return Application\Sonata\ClientBundle\Entity\ListDevises
     */
    public function getDevise()
    {
        return $this->devise;
    }


    /**
     * Set nom_de_la_banques_id
     *
     * @param integer $nomDeLaBanquesId
     * @return Garantie
     */
    public function setNomDeLaBanquesId($nomDeLaBanquesId)
    {
        $this->nom_de_la_banques_id = $nomDeLaBanquesId;

        return $this;
    }

    /**
     * Get nom_de_la_banques_id
     *
     * @return integer
     */
    public function getNomDeLaBanquesId()
    {
        return $this->nom_de_la_banques_id;
    }
}