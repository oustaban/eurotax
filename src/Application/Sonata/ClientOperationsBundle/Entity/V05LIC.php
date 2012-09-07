<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\V05LIC
 *
 * @ORM\Table("et_operations_V05LIC")
 * @ORM\Entity
 */
class V05LIC
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
     * @var string $tiers
     *
     * @ORM\Column(name="tiers", type="string", length=255)
     */
    private $tiers;

    /**
     * @var string $no_TVA_tiers
     *
     * @ORM\Column(name="no_TVA_tiers", type="string", length=255)
     */
    private $no_TVA_tiers;

    /**
     * @var \DateTime $date_piece
     *
     * @ORM\Column(name="date_piece", type="date", nullable=true)
     */
    private $date_piece;

    /**
     * @var string $numero_piece
     *
     * @ORM\Column(name="numero_piece", type="string", length=255)
     */
    private $numero_piece;

    /**
     * @var integer $devise_id
     *
     * @ORM\Column(name="devise_id", type="integer")
     */
    private $devise_id;

    /**
     * @var float $montant_HT_en_devise
     *
     * @ORM\Column(name="montant_HT_en_devise", type="float")
     */
    private $montant_HT_en_devise;

    /**
     * @var float $mois
     *
     * @ORM\Column(name="mois", type="float")
     */
    private $mois;

    /**
     * @var string $taux_de_change
     *
     * @ORM\Column(name="taux_de_change", type="string", length=255)
     */
    private $taux_de_change;

    /**
     * @var float $HT
     *
     * @ORM\Column(name="HT", type="float")
     */
    private $HT;

    /**
     * @var string $regime
     *
     * @ORM\Column(name="regime", type="string", length=255)
     */
    private $regime;

    /**
     * @var boolean $DEB
     *
     * @ORM\Column(name="DEB", type="boolean", nullable=true)
     */
    private $DEB;

    /**
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text")
     */
    private $commentaires;

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
     * @var integer $pays_id_origine
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
     *
     * @ORM\ManyToOne(targetEntity="Imports", inversedBy="ao2tva")
     * @ORM\JoinColumn(name="import_id", referencedColumnName="id")
     */
    private $imports;

    public function __clone() {
        $this->id = null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getTiers();
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
     * @return V05LIC
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
     * Set tiers
     *
     * @param string $tiers
     * @return V05LIC
     */
    public function setTiers($tiers)
    {
        $this->tiers = $tiers;

        return $this;
    }

    /**
     * Get tiers
     *
     * @return string
     */
    public function getTiers()
    {
        return $this->tiers;
    }

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
     * Set date_piece
     *
     * @param \DateTime $datePiece
     * @return V05LIC
     */
    public function setDatePiece($datePiece)
    {
        $this->date_piece = $datePiece;

        return $this;
    }

    /**
     * Get date_piece
     *
     * @return \DateTime
     */
    public function getDatePiece()
    {
        return $this->date_piece;
    }

    /**
     * Set numero_piece
     *
     * @param string $numeroPiece
     * @return V05LIC
     */
    public function setNumeroPiece($numeroPiece)
    {
        $this->numero_piece = $numeroPiece;

        return $this;
    }

    /**
     * Get numero_piece
     *
     * @return string
     */
    public function getNumeroPiece()
    {
        return $this->numero_piece;
    }

    /**
     * Set devise_id
     *
     * @param integer $deviseId
     * @return V05LIC
     */
    public function setDeviseId($deviseId)
    {
        $this->devise_id = $deviseId;

        return $this;
    }

    /**
     * Get devise_id
     *
     * @return integer
     */
    public function getDeviseId()
    {
        return $this->devise_id;
    }

    /**
     * Set montant_HT_en_devise
     *
     * @param float $montantHTEnDevise
     * @return V05LIC
     */
    public function setMontantHTEnDevise($montantHTEnDevise)
    {
        $this->montant_HT_en_devise = $montantHTEnDevise;

        return $this;
    }

    /**
     * Get montant_HT_en_devise
     *
     * @return float
     */
    public function getMontantHTEnDevise()
    {
        return $this->montant_HT_en_devise;
    }

    /**
     * Set mois
     *
     * @param float $mois
     * @return V05LIC
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return float
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set taux_de_change
     *
     * @param string $tauxDeChange
     * @return V05LIC
     */
    public function setTauxDeChange($tauxDeChange)
    {
        $this->taux_de_change = $tauxDeChange;

        return $this;
    }

    /**
     * Get taux_de_change
     *
     * @return string
     */
    public function getTauxDeChange()
    {
        return $this->taux_de_change;
    }

    /**
     * Set HT
     *
     * @param float $hT
     * @return V05LIC
     */
    public function setHT($hT)
    {
        $this->HT = $hT;

        return $this;
    }

    /**
     * Get HT
     *
     * @return float
     */
    public function getHT()
    {
        return $this->HT;
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
     * Set commentaires
     *
     * @param string $commentaires
     * @return V05LIC
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string
     */
    public function getCommentaires()
    {
        return $this->commentaires;
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
     * Set pays_id_origine
     *
     * @param string $paysIdOrigine
     * @return V05LIC
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
     * Set imports
     *
     * @param Application\Sonata\ClientOperationsBundle\Entity\Imports $imports
     * @return V05LIC
     */
    public function setImports(\Application\Sonata\ClientOperationsBundle\Entity\Imports $imports = null)
    {
        $this->imports = $imports;

        return $this;
    }

    /**
     * Get imports
     *
     * @return Application\Sonata\ClientOperationsBundle\Entity\Imports
     */
    public function getImports()
    {
        return $this->imports;
    }
}