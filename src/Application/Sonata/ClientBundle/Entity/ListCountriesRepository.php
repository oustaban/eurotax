<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Application\Sonata\ClientBundle\Entity\ListCountriesRepository

 */
class ListCountriesRepository extends EntityRepository
{
    protected $_sortBy = array('name' => 'ASC');

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        return $this->findBy(array(), $this->_sortBy);
    }

    /**
     * @return array
     */
    public function findEU()
    {
        $EU = $this->findBy(array('EU' => 1), $this->_sortBy);

        $rows = array();
        foreach ($EU as $country) {
            $rows[$country->getCode()] = (string)$country;
        }
        return $rows;
    }
}