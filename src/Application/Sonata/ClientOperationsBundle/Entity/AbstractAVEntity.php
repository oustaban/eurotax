<?php

namespace Application\Sonata\ClientOperationsBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientOperationsBundle\Entity\AbstractAVEntity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractAVEntity extends AbstractBaseEntity
{

    const monthField = 'mois';

    /**
     * @var string $tiers
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tiers", type="string", length=255)
     */
    private $tiers;

    /**
     * @var string $numero_piece
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="numero_piece", type="string", length=255)
     */
    private $numero_piece;

    /**
     * @var float $mois
     *
     * @ORM\Column(name="mois", type="date", nullable=true)
     */
    private $mois;

    /**
     * @var string $commentaires
     *
     * @ORM\Column(name="commentaires", type="text", nullable=true)
     */
    private $commentaires;


    public function __construct(){
        parent::__construct();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getTiers();
    }

    /**
     * Set tiers
     *
     * @param string $tiers
     * @return AbstractBuyEntity
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
     * Set numero_piece
     *
     * @param string $numeroPiece
     * @return AbstractSellEntity
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
     * Set mois
     *
     * @param float $mois
     * @return AbstractSellEntity
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
     * Set commentaires
     *
     * @param string $commentaires
     * @return AbstractBuyEntity
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
}
/*[!Base(DEB+A+V)] => Array
    (
            [0] => id
            [1] => client_id
            [2] => date_piece
            [3] => imports
            [4] => status
        )

    [notDEB(V+A)] => Array
    (
            [0] => tiers
            [1] => numero_piece
            [2] => mois
            [3] => commentaires
        )

    [Sell(V)] => Array
    (
            [0] => HT
            [1] => devise
            [2] => montant_HT_en_devise
            [3] => taux_de_change
        )

    [Individual] => Array
    (
            [0] => taux_de_TVA
            [1] => montant_TVA_francaise
            [2] => montant_TTC
            [3] => paiement_montant
            [4] => paiement_devise
            [5] => paiement_date
            [6] => TVA
            [7] => no_TVA_tiers
            [8] => DEB
            [9] => mois_complementaire
        )

    [DEB] => Array
    (
            [0] => regime
            [1] => n_ligne
            [2] => nomenclature
            [3] => pays_destination
            [4] => valeur_fiscale
            [5] => valeur_statistique
            [6] => masse_mette
            [7] => unites_supplementaires
            [8] => nature_transaction
            [9] => conditions_livraison
            [10] => mode_transport
            [11] => departement
            [12] => pays_origine
            [13] => CEE
        )*/
