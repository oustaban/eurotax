<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListClientTabs;


class LoadListClientsTabsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListClientTabs';

    /**
     * @var array
     */
    protected $_lists = array(
        'Général',
        'Contacts',
        'Documents légaux',
        'Garanties',
        'Coordonnées Bancaires',
        'Commentaires',
        'Tarif',
    );

    /**
     * @var array
     */
    protected $_aliases = array(
        'Général' => 'general',
        'Contacts' => 'contacts',
        'Documents légaux' => 'documents',
        'Garanties' => 'garanties',
        'Coordonnées Bancaires' => 'coordinates',
        'Commentaires' => 'commentaires',
        'Tarif' => 'tarif',
    );

    public function nameToAlias($name)
    {
        return $this->_aliases[$name];
    }
}