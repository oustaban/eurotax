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
     * Before add new Devise be sure You added it to \Application\Sonata\DevisesBundle\Entity\Devises
     * @var array
     */
    protected static $_static_lists = array(
        'EUR' => array(
            'name' => 'Euro',
            'symbol' => '€',
        ),

        'USD' => array(
            'name' => 'Dollar Américain',
            'symbol' => '$',
        ),

        'AUD' => array(
            'name' => 'Dollar Australien',
            'symbol' => '$',
        ),

        'CAD' => array(
            'name' => 'Dollar Canadien',
            'symbol' => '$',
        ),

        'CHF' => array(
            'name' => 'Franc Suisse',
            'symbol' => 'CHF',
        ),

        'DKK' => array(
            'name' => 'Couronne Danoise',
            'symbol' => 'kr',
        ),

        'GBP' => array(
            'name' => 'Livre Sterling RU',
            'symbol' => '£',
        ),

        'JPY' => array(
            'name' => 'Yen Japonais',
            'symbol' => '¥',
        ),

        'NOK' => array(
            'name' => 'Couronne Norvégienne',
            'symbol' => 'kr',
        ),

        'SEK' => array(
            'name' => 'Couronne Suédoise',
            'symbol' => 'kr',
        ),
        'CZK' => array(
        	'name' => 'Czech Koruna',
        	'symbol' => 'Kč',
        ),
        'HRK' => array(
        	'name' => 'Croatian Kuna',
        	'symbol' => 'kn',
        ),
        'HUF' => array(
        	'name' => 'Hungarian Forint',
        	'symbol' => 'Ft',
        ),
        'PLN' => array(
        	'name' => 'Polish Zloty',
        	'symbol' => 'zł',
        ),
        'RON' => array(
        	'name' => 'Romanian Leu',
        	'symbol' => 'le',
        ),
        'RUB' => array(
        	'name' => 'Russian Rouble',
        	'symbol' => 'ру,;',
        ),
        'TRY' => array(
        	'name' => 'Turkish Lira',
        	'symbol' => 'YTL',
        ),
        'ZAR' => array(
        	'name' => 'South African Rand',
        	'symbol' => 'R',
        ),
        
        
        
        // Before add new Devise be sure You added it to \Application\Sonata\DevisesBundle\Entity\Devises
    );

    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        if (self::$_static_lists) {

            foreach (self::$_static_lists as $alias => $rows) {
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

    /**
     * @return array
     */
    public static function getStaticList()
    {
        return self::$_static_lists;
    }
}