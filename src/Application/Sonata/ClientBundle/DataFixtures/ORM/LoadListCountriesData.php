<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCountries;


class LoadListCountriesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListCountries';

    /**
     * @var array
     */
    protected $_lists = array(
        'fr' => array(
            'sepa' => 'fr',
        ),
    );

    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        if ($this->_lists) {

            foreach ($this->_lists as $code => $rows) {
                /** @var $entity ListCountries */
                $entity = $manager->getRepository($this->getRepositoryName())->findOneByCode($code);

                if (!$entity) {
                    /** @var $entity ListCountries */
                    $entity = new $class();

                    $entity->setCode($this->upper($code));
                    $entity->setSepa($this->upper($rows['sepa']));

                    $manager->persist($entity);
                    $manager->flush();
                }
            }
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function upper($value)
    {
        return strtoupper($value);
    }
}