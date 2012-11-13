<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Application\Sonata\ClientBundle\Entity\ListDevises
 *
 * @ORM\Table("et_list_devises")
 * @ORM\Entity
 */
class ListDevises
{
    const Device = 'EUR';

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
     * name => label
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * ORM\OneToMany(targetEntity="Garantie", mappedBy="devise")
     **/
    protected $garantie;

    /**
     * @var string $alias
     * alias => code
     *
     * @ORM\Column(name="alias", type="string", length=3)
     */
    private $alias;


    /**
     * @var string $symbol
     *
     * @ORM\Column(name="symbol", type="string", length=3)
     */
    private $symbol;

    /**
     * @return object
     */
    public static function getDefault()
    {
        /* @var $doctrine \Doctrine\Bundle\DoctrineBundle\Registry */
        $doctrine = \AppKernel::getStaticContainer()->get('doctrine');
        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $doctrine->getManager();

        return $em->getRepository('ApplicationSonataClientBundle:ListDevises')->findOneByAlias(static::Device);
    }

    /***
     *
     */
    public function __construct()
    {
        $this->garantie = new ArrayCollection();
    }

    /**
     * @return string
     */

    public function __toString()
    {
        return strtoupper($this->getAlias());
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
     * @return ListDevises
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

    /**
     * Set alias
     *
     * @param string $alias
     * @return ListDevises
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set symbol
     *
     * @param string $symbol
     * @return ListDevises
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * Get symbol
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}