<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Garantie
 *
 * @ORM\Table("et_garantie")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
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
     * @var \Application\Sonata\ClientBundle\Entity\Client $client
     *
     * @ORM\ManyToOne(targetEntity="Client", inversedBy="garantie")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $client;

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
     * @var string $expire
     *
     * @ORM\Column(name="expire", type="boolean", nullable=true)
     */
    private $expire;

    /**
     * @var string $nom_de_lemeteur
     *
     * @ORM\Column(name="nom_de_lemeteur", type="string", length=100, nullable=true)
     */
    private $nom_de_lemeteur;

    /**
     * @var integer $nom_de_la_banque_id
     *
     * @ORM\Column(name="nom_de_la_banques_id", type="integer", nullable=true)
     */
    private $nom_de_la_banques_id;

    /**
     * @var string $num_de_ganrantie
     *
     * @ORM\Column(name="num_de_ganrantie", type="string", length=50, nullable=true)
     */
    private $num_de_ganrantie;

    /**
     * @var \DateTime $date_demission
     *
     * @ORM\Column(name="date_demission", type="date", nullable=true)
     */
    private $date_demission;

    /**
     * @var \DateTime $date_decheance
     *
     * @ORM\Column(name="date_decheance", type="date", nullable=true)
     */
    private $date_decheance;

    /**
     * @var string $note
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var array
     */
    private static $nom_de_la_banques = array(
        0 => '',
        1 => 'A établir',
    );

    public function __construct()
    {
        $this->setDevise(ListDevises::getDefault());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTypeGarantie() ? : '-';
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
     * @param \Application\Sonata\ClientBundle\Entity\ListTypeGaranties $typeGarantie
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
     * @return \Application\Sonata\ClientBundle\Entity\ListTypeGaranties
     */
    public function getTypeGarantie()
    {
        return $this->type_garantie;
    }

    /**
     * Set devise
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListDevises $devise
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
     * @return \Application\Sonata\ClientBundle\Entity\ListDevises
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

    /**
     * Set expire
     *
     * @param boolean $expire
     * @return Garantie
     */
    public function setExpire($expire)
    {
        $this->expire = $expire;

        return $this;
    }

    /**
     * Get expire
     *
     * @return boolean
     */
    public function getExpire()
    {
        return $this->expire;
    }

    /**
     * Set nom_de_lemeteur
     *
     * @param string $nomDeLemeteur
     * @return Garantie
     */
    public function setNomDeLemeteur($nomDeLemeteur)
    {
        $this->nom_de_lemeteur = $nomDeLemeteur;

        return $this;
    }

    /**
     * Get nom_de_lemeteur
     *
     * @return string
     */
    public function getNomDeLemeteur()
    {
        return $this->nom_de_lemeteur;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Garantie
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
     * Get nom_de_la_banques
     *
     * @return array
     */
    public static function getNomDeLaBanques()
    {
        return self::$nom_de_la_banques;
    }

    /**
     * Set client
     *
     * @param \Application\Sonata\ClientBundle\Entity\Client $client
     * @return Garantie
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