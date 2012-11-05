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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getSepa();
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
}