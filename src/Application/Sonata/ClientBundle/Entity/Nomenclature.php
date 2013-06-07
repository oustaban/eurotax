<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\Tarif
 *
 * @ORM\Table("et_nomenclature")
 * @ORM\Entity()
 */
class Nomenclature
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
     * @var integer $code
     *
     * @ORM\Column(name="code", type="integer")
     */
    private $code;

    

    /**
     * @var string $libelle
     *
     * @ORM\Column(name="libelle", type="text", nullable=true)
     */
    private $libelle;


    /**
     * @var float $value_percentage
     *
     * @ORM\Column(name="unites_supplementaires", type="text", nullable=true)
     */
    private $unites_supplementaires;


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
     * Set libelle
     *
     * @param float $value
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set unites_supplementaires
     *
     * @param string $unitesSupplementaires
     */
    public function setUnitesSupplementaires($unitesSupplementaires)
    {
        $this->unites_supplementaires = $unitesSupplementaires;

        return $this;
    }

    /**
     * Get unites_supplementaires
     *
     * @return string
     */
    public function getUnitesSupplementaires()
    {
        return $this->unites_supplementaires;
    }

    
}