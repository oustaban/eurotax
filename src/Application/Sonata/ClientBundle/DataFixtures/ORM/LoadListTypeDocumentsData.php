<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListTypeDocuments;


class LoadListTypeDocumentsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListTypeDocuments';

    /**
     * @var array
     */
    protected $_lists = array(
        'Mandat',
        'Pouvoir',
        'Accord',
        'Lettre de désignation',
        'Attestation de TVA',
        'Mandat Spécifique',
    );
}