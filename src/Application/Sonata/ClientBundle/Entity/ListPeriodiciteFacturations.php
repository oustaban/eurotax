<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\Sonata\ClientBundle\Entity\ListPeriodiciteFacturations
 *
 * @ORM\Table("et_list_periodicite_facturations")
 * @ORM\Entity
 */
class ListPeriodiciteFacturations
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * ORM\OneToMany(targetEntity="Client", mappedBy="periodicite_facturation")
     **/
    protected $client;

    /***
     *
     */
    public function __construct()
    {

        $this->client = ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {

        return $this->getName();
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
     * Set name
     *
     * @param string $name
     * @return ListPeriodiciteFacturation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}