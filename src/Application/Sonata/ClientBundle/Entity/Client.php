<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Client
 *
 * @ORM\Table("et_client")
 * @ORM\Entity(repositoryClass="Application\Sonata\ClientBundle\Entity\ClientRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Client
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
     * @var string  $code_client
     *
     * @ORM\Column(name="code_client", type="string", length=20)
     */
    protected $code_client;

    /**
     * @var integer $user_id
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=200)
     */
    private $nom;

    /**
     * @var integer $nature_du_client_id
     *
     * @ORM\ManyToOne(targetEntity="ListNatureDuClients")
     * @ORM\JoinColumn(name="nature_du_client_id" , referencedColumnName="id")
     */
    protected $nature_du_client;

    /**
     * @var string $raison_sociale
     *
     * @ORM\Column(name="raison_sociale", type="string", length=100)
     */
    private $raison_sociale = "";

    /**
     * @var string $adresse_1_postal
     *
     * @ORM\Column(name="adresse_1_postal", type="string", length=100)
     */
    private $adresse_1_postal = "";

    /**
     * @var string $adresse_2_postal
     *
     * @ORM\Column(name="adresse_2_postal", type="string", length=100, nullable=true)
     */
    private $adresse_2_postal = "";

    /**
     * @var string $code_postal_postal
     *
     * @ORM\Column(name="code_postal_postal", type="string", length=20)
     */
    private $code_postal_postal = "";

    /**
     * @var string $ville_postal
     *
     * @ORM\Column(name="ville_postal", type="string", length=50)
     */
    private $ville_postal = "";

    /**
     * @var string $pays_id_postal
     *
     * @ORM\Column(name="pays_id_postal", type="string",  length=2)
     */
    private $pays_id_postal = "FR";

    /**
     * @var string $activite
     *
     * @ORM\Column(name="activite", type="string", length=200, nullable=true)
     */
    private $activite = "";

    /**
     * @var \DateTime $date_debut_mission
     *
     * @ORM\Column(name="date_debut_mission", type="date")
     */
    private $date_debut_mission;

    /**
     * @var integer $mode_denregistrement_id
     *
     * @ORM\ManyToOne(targetEntity="ListModeDenregistrements")
     * @ORM\JoinColumn(name="mode_denregistrement_id", referencedColumnName="id")
     */

    private $mode_denregistrement;

    /**
     * @var string $siret
     *
     * @ORM\Column(name="siret", type="string", length=100, nullable=true)
     */
    private $siret = "";

    /**
     * @var integer $periodicite_facturation_id
     *
     * @ORM\ManyToOne(targetEntity="ListPeriodiciteFacturations")
     * @ORM\JoinColumn(name="periodicite_facturation_id", referencedColumnName="id")
     */
    private $periodicite_facturation;

    /**
     * @var string $num_dossier_fiscal
     *
     * @ORM\Column(name="num_dossier_fiscal", type="string", length=255, nullable=true)
     */
    private $num_dossier_fiscal = "";

    /**
     * @var boolean $taxe_additionnelle
     *
     * @ORM\Column(name="taxe_additionnelle", type="boolean", nullable=true)
     */
    private $taxe_additionnelle = false;

    /**
     * @var integer $periodicite_CA3_id
     *
     * @ORM\ManyToOne(targetEntity="ListPeriodiciteFacturations")
     * @ORM\JoinColumn(name="periodicite_CA3_id", referencedColumnName="id")
     */
    private $periodicite_CA3;

    /**
     * @var integer $center_des_impots
     *
     * @ORM\ManyToOne(targetEntity="\Application\Sonata\ImpotsBundle\Entity\Impots")
     * @ORM\JoinColumn(name="center_des_impots_id", referencedColumnName="id")
     */
    private $center_des_impots;

    /**
     * @var integer $language
     *
     * @ORM\ManyToOne(targetEntity="ListLanguages")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;


    /**
     * @var string $autre_destinataire_de_facturation
     *
     * @ORM\Column(name="autre_destinataire_de_facturation", type="boolean", nullable=true)
     */
    private $autre_destinataire_de_facturation;

    /**
     * @var string $contact
     *
     * @ORM\Column(name="contact", type="string", length=200, nullable=true)
     */
    private $contact = "";

    /**
     * @var string $raison_sociale_2
     *
     * @ORM\Column(name="raison_sociale_2", type="string", length=200, nullable=true)
     */
    private $raison_sociale_2 = "";

    /**
     * @var string $N_TVA_CEE_facture
     *
     * @ORM\Column(name="N_TVA_CEE_facture", type="string", length=100, nullable=true)
     */
    private $N_TVA_CEE_facture;

    /**
     * @var string $adresse_1_facturation
     *
     * @ORM\Column(name="adresse_1_facturation", type="string", length=100, nullable=true)
     */
    private $adresse_1_facturation = "";

    /**
     * @var string $adresse_2_facturation
     *
     * @ORM\Column(name="adresse_2_facturation", type="string", length=100, nullable=true)
     */
    private $adresse_2_facturation = "";

    /**
     * @var string $code_postal_facturation
     *
     * @ORM\Column(name="code_postal_facturation", type="string", length=20, nullable=true)
     */
    private $code_postal_facturation = "";

    /**
     * @var string $ville_facturation
     *
     * @ORM\Column(name="ville_facturation", type="string", length=50, nullable=true)
     */
    private $ville_facturation = "";

    /**
     * @var string $pays_id_facturation
     *
     * @ORM\Column(name="pays_id_facturation", type="string", length=2, nullable=true)
     */
    private $pays_id_facturation = "FR";

    /**
     * @var \DateTime $date_fin_mission
     *
     * @ORM\Column(name="date_fin_mission", type="date", nullable=true)
     */
    private $date_fin_mission;


    /**
     * @var integer $date_de_depot_id
     *
     * @ORM\Column(name="date_de_depot_id", type="integer")
     */
    private $date_de_depot_id;

    /**
     * @var string $N_TVA_CEE
     *
     * @ORM\Column(name="N_TVA_CEE", type="string", length=100, nullable=true)
     */
    private $N_TVA_CEE;

    /**
     * @var integer $niveau_dobligation_id
     *
     * @ORM\Column(name="niveau_dobligation_id", type="integer", nullable=true)
     */
    private $niveau_dobligation_id;

    /**
     * @var integer $alert_count
     *
     * @ORM\Column(name="alert_count", type="integer", nullable=true)
     */
    private $alert_count = 0;


    /**
     * @var integer $blocked_count
     *
     * @ORM\Column(name="blocked_count", type="integer", nullable=true)
     */
    private $blocked_count = 0;

    /**
     * @var string $teledeclaration
     *
     * @ORM\Column(name="teledeclaration", type="boolean", nullable=true)
     */
    private $teledeclaration;

    /**
     * @var string  $files
     *
     * @ORM\Column(name="files", type="text")
     */
    protected $files = '[]';


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
     * @return Client
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
     * Set raison_sociale
     *
     * @param string $raisonSociale
     * @return Client
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raison_sociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raison_sociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raison_sociale;
    }

    /**
     * Set adresse_1_postal
     *
     * @param string $adressePostale1
     * @return Client
     */
    public function setAdresse1Postal($adressePostale1)
    {
        $this->adresse_1_postal = $adressePostale1;

        return $this;
    }

    /**
     * Get adresse_1_postal
     *
     * @return string
     */
    public function getAdresse1Postal()
    {
        return $this->adresse_1_postal;
    }

    /**
     * Set adresse_2_postal
     *
     * @param string $adressePostale2
     * @return Client
     */
    public function setAdresse2Postal($adressePostale2)
    {
        $this->adresse_2_postal = $adressePostale2;

        return $this;
    }

    /**
     * Get adresse_2_postal
     *
     * @return string
     */
    public function getAdresse2Postal()
    {
        return $this->adresse_2_postal;
    }

    /**
     * Set code_postal_postal
     *
     * @param string $codePostal
     * @return Client
     */
    public function setCodePostalPostal($codePostal)
    {
        $this->code_postal_postal = $codePostal;

        return $this;
    }

    /**
     * Get code_postal_postal
     *
     * @return string
     */
    public function getCodePostalPostal()
    {
        return $this->code_postal_postal;
    }

    /**
     * Set ville_postal
     *
     * @param string $villePostal
     * @return Client
     */
    public function setVillePostal($villePostal)
    {
        $this->ville_postal = $villePostal;

        return $this;
    }

    /**
     * Get ville_postal
     *
     * @return string
     */
    public function getVillePostal()
    {
        return $this->ville_postal;
    }

    /**
     * Set pays_id_postal
     *
     * @param integer $paysIdPostal
     * @return Client
     */
    public function setPaysIdPostal($paysIdPostal)
    {
        $this->pays_id_postal = $paysIdPostal;

        return $this;
    }

    /**
     * Get pays_id_postal
     *
     * @return integer
     */
    public function getPaysIdPostal()
    {
        return $this->pays_id_postal;
    }

    /**
     * Set activite
     *
     * @param string $activite
     * @return Client
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return string
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * Set date_debut_mission
     *
     * @param \DateTime $dateDebutMission
     * @return Client
     */
    public function setDateDebutMission($dateDebutMission)
    {
        $this->date_debut_mission = $dateDebutMission;

        return $this;
    }

    /**
     * Get date_debut_mission
     *
     * @return \DateTime
     */
    public function getDateDebutMission()
    {
        return $this->date_debut_mission;
    }


    /**
     * Set siret
     *
     * @param string $siret
     * @return Client
     */
    public function setSiret($siret)
    {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret()
    {
        return $this->siret;
    }


    /**
     * Set num_dossier_fiscal
     *
     * @param string $numDossierFiscal
     * @return Client
     */
    public function setNumDossierFiscal($numDossierFiscal)
    {
        $this->num_dossier_fiscal = $numDossierFiscal;

        return $this;
    }

    /**
     * Get num_dossier_fiscal
     *
     * @return string
     */
    public function getNumDossierFiscal()
    {
        return $this->num_dossier_fiscal;
    }

    /**
     * Set taxe_additionnelle
     *
     * @param boolean $taxeAdditionnelle
     * @return Client
     */
    public function setTaxeAdditionnelle($taxeAdditionnelle)
    {
        $this->taxe_additionnelle = $taxeAdditionnelle;

        return $this;
    }

    /**
     * Get taxe_additionnelle
     *
     * @return boolean
     */
    public function getTaxeAdditionnelle()
    {
        return $this->taxe_additionnelle;
    }


    /**
     * Set adresse_1_facturation
     *
     * @param string $adresse1Facturation
     * @return Client
     */
    public function setAdresse1Facturation($adresse1Facturation)
    {
        $this->adresse_1_facturation = $adresse1Facturation;

        return $this;
    }

    /**
     * Get adresse_1_facturation
     *
     * @return string
     */
    public function getAdresse1Facturation()
    {
        return $this->adresse_1_facturation;
    }

    /**
     * Set adresse_2_facturation
     *
     * @param string $adresse2Facturation
     * @return Client
     */
    public function setAdresse2Facturation($adresse2Facturation)
    {
        $this->adresse_2_facturation = $adresse2Facturation;

        return $this;
    }

    /**
     * Get adresse_2_facturation
     *
     * @return string
     */
    public function getAdresse2Facturation()
    {
        return $this->adresse_2_facturation;
    }

    /**
     * Set code_postal_facturation
     *
     * @param string $codePostalFacturation
     * @return Client
     */
    public function setCodePostalFacturation($codePostalFacturation)
    {
        $this->code_postal_facturation = $codePostalFacturation;

        return $this;
    }

    /**
     * Get code_postal_facturation
     *
     * @return string
     */
    public function getCodePostalFacturation()
    {
        return $this->code_postal_facturation;
    }

    /**
     * Set ville_facturation
     *
     * @param string $villeFacturation
     * @return Client
     */
    public function setVilleFacturation($villeFacturation)
    {
        $this->ville_facturation = $villeFacturation;

        return $this;
    }

    /**
     * Get ville_facturation
     *
     * @return string
     */
    public function getVilleFacturation()
    {
        return $this->ville_facturation;
    }

    /**
     * Set pays_id_facturation
     *
     * @param integer $paysIdFacturation
     * @return Client
     */
    public function setPaysIdFacturation($paysIdFacturation)
    {
        $this->pays_id_facturation = $paysIdFacturation;

        return $this;
    }

    /**
     * Get pays_id_facturation
     *
     * @return integer
     */
    public function getPaysIdFacturation()
    {
        return $this->pays_id_facturation;
    }

    /**
     * Set date_fin_mission
     *
     * @param \DateTime $dateFinMission
     * @return Client
     */
    public function setDateFinMission($dateFinMission)
    {
        $this->date_fin_mission = $dateFinMission;

        return $this;
    }

    /**
     * Get date_fin_mission
     *
     * @return \DateTime
     */
    public function getDateFinMission()
    {
        return $this->date_fin_mission;
    }

    /**
     * Set date_de_depot_id
     *
     * @param integer $dateDeDepot
     * @return Client
     */
    public function setDateDeDepot($dateDeDepot)
    {
        $this->date_de_depot_id = $dateDeDepot;

        return $this;
    }

    /**
     * Get date_de_depot
     *
     * @return integer
     */
    public function getDateDeDepot()
    {
        return $this->date_de_depot_id;
    }

    /**
     * Set N_TVA_CEE
     *
     * @param string $nTVACEE
     * @return Client
     */
    public function setNTVACEE($nTVACEE)
    {
        $this->N_TVA_CEE = $nTVACEE;

        return $this;
    }

    /**
     * Get N_TVA_CEE
     *
     * @return string
     */
    public function getNTVACEE()
    {
        return $this->N_TVA_CEE;
    }

    /**
     * Set niveau_dobligation_id
     *
     * @param integer $niveauDobligationId
     * @return Client
     */
    public function setNiveauDobligationId($niveauDobligationId)
    {
        $this->niveau_dobligation_id = $niveauDobligationId;

        return $this;
    }

    /**
     * Get niveau_dobligation_id
     *
     * @return integer
     */
    public function getNiveauDobligationId()
    {
        return $this->niveau_dobligation_id;
    }

    /**
     * Set date_de_depot_id
     *
     * @param integer $dateDeDepotId
     * @return Client
     */
    public function setDateDeDepotId($dateDeDepotId)
    {
        $this->date_de_depot_id = $dateDeDepotId;

        return $this;
    }

    /**
     * Get date_de_depot_id
     *
     * @return integer
     */
    public function getDateDeDepotId()
    {
        return $this->date_de_depot_id;
    }

    /**
     * Set nature_du_client
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListNatureDuClients $natureDuClient
     * @return Client
     */
    public function setNatureDuClient(\Application\Sonata\ClientBundle\Entity\ListNatureDuClients $natureDuClient = null)
    {
        $this->nature_du_client = $natureDuClient;

        return $this;
    }

    /**
     * Get nature_du_client
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListNatureDuClients
     */
    public function getNatureDuClient()
    {
        return $this->nature_du_client;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Client
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set mode_denregistrement
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListModeDenregistrements $modeDenregistrement
     * @return Client
     */
    public function setModeDenregistrement(\Application\Sonata\ClientBundle\Entity\ListModeDenregistrements $modeDenregistrement = null)
    {
        $this->mode_denregistrement = $modeDenregistrement;

        return $this;
    }

    /**
     * Get mode_denregistrement
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListModeDenregistrements
     */
    public function getModeDenregistrement()
    {
        return $this->mode_denregistrement;
    }

    /**
     * Set periodicite_facturation
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteFacturation
     * @return Client
     */
    public function setPeriodiciteFacturation(\Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteFacturation = null)
    {
        $this->periodicite_facturation = $periodiciteFacturation;

        return $this;
    }

    /**
     * Get periodicite_facturation
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations
     */
    public function getPeriodiciteFacturation()
    {
        return $this->periodicite_facturation;
    }

    /**
     * Set periodicite_CA3
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteCA3
     * @return Client
     */
    public function setPeriodiciteCA3(\Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations $periodiciteCA3 = null)
    {
        $this->periodicite_CA3 = $periodiciteCA3;

        return $this;
    }

    /**
     * Get periodicite_CA3
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations
     */
    public function getPeriodiciteCA3()
    {
        return $this->periodicite_CA3;
    }

    /**
     * Set center_des_impots
     *
     * @param \Application\Sonata\ImpotsBundle\Entity\Impots $centerDesImpots
     * @return Client
     */
    public function setCenterDesImpots(\Application\Sonata\ImpotsBundle\Entity\Impots $centerDesImpots = null)
    {
        $this->center_des_impots = $centerDesImpots;

        return $this;
    }

    /**
     * Get center_des_impots
     *
     * @return \Application\Sonata\ImpotsBundle\Entity\Impots
     */
    public function getCenterDesImpots()
    {
        return $this->center_des_impots;
    }

    /**
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNom() ? : '-';
    }


    /**
     * Set $teledeclaration
     *
     * @param boolean $autreDestinataireDeFacturation
     * @return Client
     */
    public function setAutreDestinataireDeFacturation($autreDestinataireDeFacturation)
    {
        $this->autre_destinataire_de_facturation = $autreDestinataireDeFacturation;

        return $this;
    }

    /**
     * Get autre_destinataire_de_facturation
     *
     * @return boolean
     */
    public function getAutreDestinataireDeFacturation()
    {
        return $this->autre_destinataire_de_facturation;
    }

    /**
     * Set contact
     *
     * @param string $contact
     * @return Client
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }


    /**
     * Set N_TVA_CEE_facture
     *
     * @param string $nTVACEEFacture
     * @return Client
     */
    public function setNTVACEEFacture($nTVACEEFacture)
    {
        $this->N_TVA_CEE_facture = $nTVACEEFacture;

        return $this;
    }

    /**
     * Get N_TVA_CEE_facture
     *
     * @return string
     */
    public function getNTVACEEFacture()
    {
        return $this->N_TVA_CEE_facture;
    }


    /**
     * Set language
     *
     * @param \Application\Sonata\ClientBundle\Entity\ListLanguages $language
     * @return Client
     */
    public function setLanguage(\Application\Sonata\ClientBundle\Entity\ListLanguages $language = null)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Application\Sonata\ClientBundle\Entity\ListLanguages
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set raison_sociale_2
     *
     * @param string $raisonSociale2
     * @return Client
     */
    public function setRaisonSociale2($raisonSociale2)
    {
        $this->raison_sociale_2 = $raisonSociale2;

        return $this;
    }

    /**
     * Get raison_sociale_2
     *
     * @return string
     */
    public function getRaisonSociale2()
    {
        return $this->raison_sociale_2;
    }

    /**
     * Set alert_count
     *
     * @param integer $alertCount
     * @return Client
     */
    public function setAlertCount($alertCount)
    {
        $this->alert_count = $alertCount;

        return $this;
    }

    /**
     * Get alert_count
     *
     * @return integer
     */
    public function getAlertCount()
    {
        return $this->alert_count;
    }

    /**
     * Set blocked_count
     *
     * @param integer $blockedCount
     * @return Client
     */
    public function setBlockedCount($blockedCount)
    {
        $this->blocked_count = $blockedCount;

        return $this;
    }

    /**
     * Get blocked_count
     *
     * @return integer
     */
    public function getBlockedCount()
    {
        return $this->blocked_count;
    }

    /**
     * Set teledeclaration
     *
     * @param boolean $teledeclaration
     * @return Client
     */
    public function setTeledeclaration($teledeclaration)
    {
        $this->teledeclaration = $teledeclaration;

        return $this;
    }

    /**
     * Get teledeclaration
     *
     * @return boolean
     */
    public function getTeledeclaration()
    {
        return $this->teledeclaration;
    }

    /**
     * Set code_client
     *
     * @param string $codeClient
     * @return Client
     */
    public function setCodeClient($codeClient)
    {
        $this->code_client = $codeClient;

        return $this;
    }

    /**
     * Get code_client
     *
     * @return string
     */
    public function getCodeClient()
    {
        return $this->code_client;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        list($code_client) = $em->getRepository('ApplicationSonataClientBundle:Client')
            ->createQueryBuilder('c')
            ->select('c.code_client as code_client')
            ->addOrderBy('c.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->execute();

        /**
         * UPDATE et_client SET code_client = id
         */
        $this->setCodeClient($code_client['code_client'] + 1);
    }

    /**
     * Get files
     *
     * @return string
     */
    public function getFiles()
    {
        return json_decode($this->files);
    }

    /**
     * Set files
     *
     * @param array $files
     * @return Client
     */
    public function setFiles($files)
    {
        $this->files = json_encode($files);

        return $this;
    }

    /**
     * Move file
     *
     * @param string $file
     * @return Client
     */
    public function moveFile($file)
    {
        //$files = $this->getFiles();

        return $this;
    }

    /**
     * @static
     * @param int|Client $client
     */
    public static function scanFilesTree($client){
    }
}