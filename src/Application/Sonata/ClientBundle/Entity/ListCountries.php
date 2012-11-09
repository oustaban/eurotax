<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\ListCountries
 *
 * @ORM\Table("et_list_countries")
 * @ORM\Entity
 */
class ListCountries
{
    const PaysCode = 'fr';

    /**
     * @var string $code
     *s
     * @ORM\Column(name="code", type="string", length=2)
     * @ORM\Id
     */
    private $code;

    /**
     * @var string $sepa
     *
     * @ORM\Column(name="sepa", type="string", length=3)
     */
    private $sepa;


    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    public static function getDefault()
    {

        return \AppKernel::getStaticContainer()->get('doctrine')->getRepository('ApplicationSonataClientBundle:ListCountries')->findOneByCode(static::PaysCode);
    }


    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName() ? : '';
    }


    /**
     * Set code
     *
     * @param string $code
     * @return ListCountries
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }


    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set sepa
     *
     * @param string $sepa
     * @return ListCountries
     */
    public function setSepa($sepa)
    {
        $this->sepa = $sepa;

        return $this;
    }

    /**
     * Get sepa
     *
     * @return string
     */
    public function getSepa()
    {
        return $this->sepa;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ListCountries
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