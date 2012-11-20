<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application\Sonata\ClientBundle\Entity\ListCountries
 *
 * @ORM\Table("et_list_countries")
 * @ORM\Entity(repositoryClass="Application\Sonata\ClientBundle\Entity\ListCountriesRepository")
 */
class ListCountries
{
    const PaysCode = 'FR';

    /** @var array('Code' => 'Country') */
    protected static $_CountryEU = array(
        'AT' => 'Austria',
        'BE' => 'Belgium',
        'BG' => 'Bulgaria',
        'CY' => 'Cyprus',
        'CZ' => 'Czech Republic',
        'DK' => 'Denmark',
        'EE' => 'Estonia',
        'FI' => 'Finland',
        'FR' => 'France',
        'DE' => 'Germany',
        'GR' => 'Greece',
        'HU' => 'Hungary',
        'IE' => 'Ireland',
        'IT' => 'Italy',
        'LV' => 'Latvia',
        'LT' => 'Lithuania',
        'LU' => 'Luxembourg',
        'MT' => 'Malta',
        'NL' => 'Netherlands',
        'PL' => 'Poland',
        'PT' => 'Portugal',
        'RO' => 'Romania',
        'SK' => 'Slovakia',
        'SI' => 'Slovenia',
        'ES' => 'Spain',
        'SE' => 'Sweden',
        'GB' => 'United Kingdom',
    );


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

    /**
     * @return mixed
     */
    public static function getDefault()
    {
        return \AppKernel::getStaticContainer()->get('doctrine')->getRepository('ApplicationSonataClientBundle:ListCountries')->findOneByCode(static::PaysCode);
    }

    /**
     * @return array
     */
    public static function getCountryEU(){
        return self::$_CountryEU;
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