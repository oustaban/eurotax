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
        'eur' => array(
            'name' => 'Euro',
            'symbol' => '€',
        ),

        'usd' => array(
            'name' => 'US Dollar',
            'symbol' => '$',
        ),

        'gbp' => array(
            'name' => 'British Pound',
            'symbol' => '$',
        ),

        'inr' => array(
            'name' => 'Indian Rupee',
            'symbol' => 'INR',
        ),

        'aud' => array(
            'name' => 'Australian Dollar',
            'symbol' => '$',
        ),

        'cad' => array(
            'name' => 'Canadian Dollar',
            'symbol' => '$',
        ),

        'aed' => array(
            'name' => 'Emirati Dirham',
            'symbol' => 'د.إ',
        ),
        'chf' => array(
            'name' => 'Swiss Franc',
            'symbol' => 'CHF',
        ),
        'jpy' => array(
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