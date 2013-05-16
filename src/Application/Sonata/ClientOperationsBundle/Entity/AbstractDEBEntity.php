<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractDEBEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractDEBEntity extends AbstractBaseEntity
{
    /**
     * @var string $n_ligne
     *
     * @ORM\Column(name="n_ligne", type="string", length=255, nullable=true)
     */
    private $n_ligne;

    /**
     * @var string $nomenclature
     *
     * @ORM\Column(name="nomenclature", type="string", length=255, nullable=true)
     */
    private $nomenclature;


    /**
     * @var string $pays_id_destination
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListCountries")
     * @ORM\JoinColumn(name="pays_id_destination", referencedColumnName="code")
     */
    private $pays_destination;

    /**
     * @var float $valeur_fiscale
     *
     * @Assert\NotBlank()
     * @Assert\Min(limit = "0", message = "Valeur fiscale doit être un nombre positif")
     * @ORM\Column(name="valeur_fiscale", type="float", nullable=true)
     */
    private $valeur_fiscale;

    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="float", length=255, nullable=true)
     */
    private $regime;

    /**
     * @var float $valeur_statistique
     *
     * @Assert\NotBlank()
     * @Assert\Min(limit = "0", message = "Valeur statistique doit être un nombre positif")
     * @ORM\Column(name="valeur_statistique", type="float", nullable=true)
     */
    private $valeur_statistique;

    /**
     * @var float $masse_mette
     *
     * @Assert\Min(limit = "0", message = "Masse Nette doit être un nombre positif")
     * @ORM\Column(name="masse_mette", type="integer", nullable=true)
     */
    private $masse_mette;


    /**
     * @var float $unites_supplementaires
     *
     * @Assert\Min(limit = "0", message = "Unité supplémentaires doit être un nombre positif")
     * @ORM\Column(name="unites_supplementaires", type="integer", nullable=true)
     */
    private $unites_supplementaires;

    /**
     * @var string $nature_transaction
     *
     * @ORM\Column(name="nature_transaction", type="string", length=255, nullable=true)
     */
    private $nature_transaction;

    /**
     * @var string $conditions_livraison
     *
     * @ORM\Column(name="conditions_livraison", type="string", length=255, nullable=true)
     */
    private $conditions_livraison;


    /**
     * @var string $mode_transport
     *
     * @Assert\Range(min = "1", max = "9", minMessage = "Mode de transport doit être compris entre 1 et 9", maxMessage = "Mode de transport doit être compris entre 1 et 9")
     * @ORM\Column(name="mode_transport", type="string", length=255, nullable=true)
     */
    private $mode_transport;

    /**
     * @var string $departement
     *
     * @Assert\Range(min = "1", max = "99", minMessage = "Le département n'est pas correct", maxMessage = "Le département n'est pas correct")
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    private $departement;

    /**
     * @var string $pays_id_origine
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ClientBundle\Entity\ListCountries")
     * @ORM\JoinColumn(name="pays_id_origine", referencedColumnName="code")
     */
    private $pays_origine;


    /**
     * @var string $CEE
     *
     * @ORM\Column(name="CEE", type="string", length=255, nullable=true)
     */
    private $CEE;



    public function __construct(){
        parent::__construct();

        $this->setMois(new \DateTime('now' . (date('d') < 25 ? ' -1 month' : '')));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getNLigne();
    }

    /**
     * Set n_ligne
     *
     * @param string $nLigne
     * @return AbstractDEBEntity
     */
    public function setNLigne($nLigne)
    {
        $this->n_ligne = $nLigne;

        return $this;
    }

    /**
     * Get n_ligne
     *
     * @return string
     */
    public function getNLigne()
    {
        return $this->n_ligne;
    }

    /**
     * Set nomenclature
     *
     * @param string $nomenclature
     * @return AbstractDEBEntity
     */
    public function setNomenclature($nomenclature)
    {
        $this->nomenclature = $nomenclature;

        return $this;
    }

    /**
     * Get nomenclature
     *
     * @return string
     */
    public function getNomenclature()
    {
        return $this->nomenclature;
    }

    /**
     * Set pays_destination
     *
     * @param string $paysIdDestination
     * @return AbstractDEBEntity
     */
    public function setPaysIdDestination($paysIdDestination)
    {
        $this->pays_destination = $paysIdDestination;

        return $this;
    }

    /**
     * Get pays_destination
     *
     * @return string
     */
    public function getPaysIdDestination()
    {
        return $this->pays_destination->getCode();
    }

    /**
     * Set valeur_fiscale
     *
     * @param float $valeurFiscale
     * @return AbstractDEBEntity
     */
    public function setValeurFiscale($valeurFiscale)
    {
        $this->valeur_fiscale = $valeurFiscale;

        return $this;
    }

    /**
     * Get valeur_fiscale
     *
     * @return float
     */
    public function getValeurFiscale()
    {
        return $this->valeur_fiscale;
    }

    /**
     * Set regime
     *
     * @param string $regime
     * @return AbstractDEBEntity
     */
    public function setRegime($regime)
    {
        $this->regime = $regime;

        return $this;
    }

    /**
     * Get regime
     *
     * @return string
     */
    public function getRegime()
    {
        return $this->regime;
    }

    /**
     * Set valeur_statistique
     *
     * @param float $valeurStatistique
     * @return AbstractDEBEntity
     */
    public function setValeurStatistique($valeurStatistique)
    {
        $this->valeur_statistique = $valeurStatistique;

        return $this;
    }

    /**
     * Get valeur_statistique
     *
     * @return float
     */
    public function getValeurStatistique()
    {
        return $this->valeur_statistique;
    }

    /**
     * Set masse_mette
     *
     * @param float $masseMette
     * @return AbstractDEBEntity
     */
    public function setMasseMette($masseMette)
    {
        $this->masse_mette = $masseMette;

        return $this;
    }

    /**
     * Get masse_mette
     *
     * @return float
     */
    public function getMasseMette()
    {
        return $this->masse_mette;
    }

    /**
     * Set unites_supplementaires
     *
     * @param float $unitesSupplementaires
     * @return AbstractDEBEntity
     */
    public function setUnitesSupplementaires($unitesSupplementaires)
    {
        $this->unites_supplementaires = $unitesSupplementaires;

        return $this;
    }

    /**
     * Get unites_supplementaires
     *
     * @return float
     */
    public function getUnitesSupplementaires()
    {
        return $this->unites_supplementaires;
    }

    /**
     * Set nature_transaction
     *
     * @param string $natureTransaction
     * @return AbstractDEBEntity
     */
    public function setNatureTransaction($natureTransaction)
    {
        $this->nature_transaction = $natureTransaction;

        return $this;
    }

    /**
     * Get nature_transaction
     *
     * @return string
     */
    public function getNatureTransaction()
    {
        return $this->nature_transaction;
    }

    /**
     * Set conditions_livraison
     *
     * @param string $conditionsLivraison
     * @return AbstractDEBEntity
     */
    public function setConditionsLivraison($conditionsLivraison)
    {
        $this->conditions_livraison = $conditionsLivraison;

        return $this;
    }

    /**
     * Get conditions_livraison
     *
     * @return string
     */
    public function getConditionsLivraison()
    {
        return $this->conditions_livraison;
    }

    /**
     * Set mode_transport
     *
     * @param string $modeTransport
     * @return AbstractDEBEntity
     */
    public function setModeTransport($modeTransport)
    {
        $this->mode_transport = $modeTransport;

        return $this;
    }

    /**
     * Get mode_transport
     *
     * @return string
     */
    public function getModeTransport()
    {
        return $this->mode_transport;
    }

    /**
     * Set departement
     *
     * @param string $departement
     * @return AbstractDEBEntity
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set pays_origine
     *
     * @param string $paysIdOrigine
     * @return AbstractDEBEntity
     */
    public function setPaysIdOrigine($paysIdOrigine)
    {
        $this->pays_origine = $paysIdOrigine;

        return $this;
    }

    /**
     * Get pays_origine
     *
     * @return string
     */
    public function getPaysIdOrigine()
    {
        return $this->pays_origine->getCode();
    }

    /**
     * Set CEE
     *
     * @param string $cEE
     * @return AbstractDEBEntity
     */
    public function setCEE($cEE)
    {
        $this->CEE = $cEE;

        return $this;
    }

    /**
     * Get CEE
     *
     * @return string
     */
    public function getCEE()
    {
        return $this->CEE;
    }

    /**
     * Set pays_destination
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCountries $paysDestination
     * @return AbstractDEBEntity
     */
    public function setPaysDestination(\Application\Sonata\ClientBundle\Entity\ListCountries $paysDestination = null)
    {
        $this->pays_destination = $paysDestination;

        return $this;
    }

    /**
     * Get pays_destination
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCountries
     */
    public function getPaysDestination()
    {
        return $this->pays_destination;
    }

    /**
     * Set pays_origine
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListCountries $paysOrigine
     * @return AbstractDEBEntity
     */
    public function setPaysOrigine(\Application\Sonata\ClientBundle\Entity\ListCountries $paysOrigine = null)
    {
        $this->pays_origine = $paysOrigine;

        return $this;
    }

    /**
     * Get pays_origine
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListCountries
     */
    public function getPaysOrigine()
    {
        return $this->pays_origine;
    }
}