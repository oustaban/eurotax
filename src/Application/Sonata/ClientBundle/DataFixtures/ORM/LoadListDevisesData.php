<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListDevises;


class LoadListDevisesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListDevises';

    /**
     * @var array
     */
    protected $_lists = array(
        'EUR' => array(
            'name' => 'Euro',
            'symbol' => '€',
        ),

        'USD' => array(
            'name' => 'US Dollar',
            'symbol' => '$',
        ),

        'GBP' => array(
            'name' => 'British Pound',
            'symbol' => '$',
        ),

        'INR' => array(
            'name' => 'Indian Rupee',
            'symbol' => 'INR',
        ),

        'AUD' => array(
            'name' => 'Australian Dollar',
            'symbol' => '$',
        ),

        'CAD' => array(
            'name' => 'Canadian Dollar',
            'symbol' => '$',
        ),

        'AED' => array(
            'name' => 'Emirati Dirham',
            'symbol' => 'د.إ',
        ),
        'CHF' => array(
            'name' => 'Swiss Franc',
            'symbol' => 'CHF',
        ),
        'JPY' => array(
            'name' => 'Japanese Yen',
            'symbol' => '¥',
        ),
    );

    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        if ($this->_lists) {

            foreach ($this->_lists as $alias => $rows) {
                /** @var $entity ListDevises */
                $entity = $manager->getRepository($this->getRepositoryName())->findOneByAlias($alias);

                if (!$entity) {
                    /** @var $entity ListDevises */
                    $entity = new $class();

                    $entity->setName($rows['name']);
                    $entity->setSymbol($rows['symbol']);
                    $entity->setAlias($alias);

                    $manager->persist($entity);
                    $manager->flush();
                }
            }
        }
    }
}