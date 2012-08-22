<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\DEBIntro
 *
 * @ORM\Table("et_operations_DEBIntro")
 * @ORM\Entity
 */
class DEBIntro
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
     * @var string $n_ligne
     *
     * @ORM\Column(name="n_ligne", type="string", length=255)
     */
    private $n_ligne;

    /**
     * @var string $nomenclature
     *
     * @ORM\Column(name="nomenclature", type="string", length=255)
     */
    private $nomenclature;

    /**
     * @var string $pays_id_destination
     *
     * @ORM\Column(name="pays_id_destination", type="string", length=2)
     */
    private $pays_id_destination;

    /**
     * @var float $valeur_fiscale
     *
     * @ORM\Column(name="valeur_fiscale", type="float")
     */
    private $valeur_fiscale;

    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="string", length=255)
     */
    private $regime;

    /**
     * @var float $valeur_statistique
     *
     * @ORM\Column(name="valeur_statistique", type="float")
     */
    private $valeur_statistique;

    /**
     * @var float $masse_mette
     *
     * @ORM\Column(name="masse_mette", type="float")
     */
    private $masse_mette;

    /**
     * @var float $unites_supplementaires
     *
     * @ORM\Column(name="unites_supplementaires", type="float")
     */
    private $unites_supplementaires;

    /**
     * @var string $nature_transaction
     *
     * @ORM\Column(name="nature_transaction", type="string", length=255)
     */
    private $nature_transaction;

    /**
     * @var string $conditions_livraison
     *
     * @ORM\Column(name="conditions_livraison", type="string", length=255)
     */
    private $conditions_livraison;

    /**
     * @var string $mode_transport
     *
     * @ORM\Column(name="mode_transport", type="string", length=255)
     */
    private $mode_transport;

    /**
     * @var string $departement
     *
     * @ORM\Column(name="departement", type="string", length=255)
     */
    private $departement;

    /**
     * @var string $pays_id_origine
     *
     * @ORM\Column(name="pays_id_origine", type="string", length=2)
     */
    private $pays_id_origine;

    /**
     * @var string $CEE
     *
     * @ORM\Column(name="CEE", type="string", length=255)
     */
    private $CEE;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getNLigne();
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
     * @return DEBExped
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
     * Set n_ligne
     *
     * @param string $nLigne
     * @return DEBExped
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
     * @return DEBExped
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
     * Set pays_id_destination
     *
     * @param string $paysIdDestination
     * @return DEBExped
     */
    public function setPaysIdDestination($paysIdDestination)
    {
        $this->pays_id_destination = $paysIdDestination;

        return $this;
    }

    /**
     * Get pays_id_destination
     *
     * @return string
     */
    public function getPaysIdDestination()
    {
        return $this->pays_id_destination;
    }

    /**
     * Set valeur_fiscale
     *
     * @param float $valeurFiscale
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * @return DEBExped
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
     * Set pays_id_origine
     *
     * @param string $paysIdOrigine
     * @return DEBExped
     */
    public function setPaysIdOrigine($paysIdOrigine)
    {
        $this->pays_id_origine = $paysIdOrigine;

        return $this;
    }

    /**
     * Get pays_id_origine
     *
     * @return string
     */
    public function getPaysIdOrigine()
    {
        return $this->pays_id_origine;
    }

    /**
     * Set CEE
     *
     * @param string $cEE
     * @return DEBExped
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
}
