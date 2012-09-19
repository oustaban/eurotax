<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListPaiements;


class LoadListPaiementsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListPaiements';

    /**
     * @var array
     */
    protected $_lists = array(
        'A réception de facture',
        'a 30 jour fin de mois',
        'a 60 jours',
    );
}