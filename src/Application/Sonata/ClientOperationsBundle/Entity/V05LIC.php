<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V05LIC
 *
 * @ORM\Table("et_operations_V05LIC")
 * @ORM\Entity
 */
class V05LIC extends AbstractSellEntity
{

    /**
     * @var string $no_TVA_tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;


    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="string", length=255, nullable=true)
     */
    private $regime;

    /**
     * @var boolean $DEB
     *
     * @ORM\Column(name="DEB", type="boolean", nullable=true)
     */
    private $DEB;


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
     * @ORM\Column(name="valeur_fiscale", type="float", nullable=true)
     */
    private $valeur_fiscale;

    /**
     * @var float $valeur_statistique
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="valeur_statistique", type="float")
     */
    private $valeur_statistique;

    /**
     * @var float $masse_mette
     *
     * @ORM\Column(name="masse_mette", type="float", nullable=true)
     */
    private $masse_mette;

    /**
     * @var float $unites_supplementaires
     *
     * @ORM\Column(name="unites_supplementaires", type="float", nullable=true)
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
     * @ORM\Column(name="mode_transport", type="string", length=255, nullable=true)
     */
    private $mode_transport;

    /**
     * @var string $departement
     *
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

    /**
     * Set no_TVA_tiers
     *
     * @param string $noTVATiers
     * @return V05LIC
     */
    public function setNoTVATiers($noTVATiers)
    {
        $this->no_TVA_tiers = $noTVATiers;

        return $this;
    }

    /**
     * Get no_TVA_tiers
     *
     * @return string
     */
    public function getNoTVATiers()
    {
        return $this->no_TVA_tiers;
    }


    /**
     * Set regime
     *
     * @param string $regime
     * @return V05LIC
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
     * Set DEB
     *
     * @param boolean $dEB
     * @return V05LIC
     */
    public function setDEB($dEB)
    {
        $this->DEB = $dEB;

        return $this;
    }

    /**
     * Get DEB
     *
     * @return boolean
     */
    public function getDEB()
    {
        return $this->DEB;
    }


    /**
     * Set n_ligne
     *
     * @param string $nLigne
     * @return V05LIC
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
     * @return V05LIC
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
     * Set valeur_fiscale
     *
     * @param float $valeurFiscale
     * @return V05LIC
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
     * Set valeur_statistique
     *
     * @param float $valeurStatistique
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * Set pays_id_destination
     *
     * @param string $paysIdDestination
     * @return V05LIC
     */
    public function setPaysIdDestination($paysIdDestination)
    {
        $this->pays_destination = $paysIdDestination;

        return $this;
    }

    /**
     * Get pays_id_destination
     *
     * @return string
     */
    public function getPaysIdDestination()
    {
        return $this->pays_destination->getCode();
    }

    /**
     * Set pays_origine
     *
     * @param string $paysIdOrigine
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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
     * @return V05LIC
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