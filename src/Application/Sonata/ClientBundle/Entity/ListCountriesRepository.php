<?php

namespace Application\Sonata\ClientBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Application\Sonata\ClientBundle\Entity\ListCountriesRepository

 */
class ListCountriesRepository extends EntityRepository
{
    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        return $this->findBy(array(), array('name' => 'ASC'));
    }
}