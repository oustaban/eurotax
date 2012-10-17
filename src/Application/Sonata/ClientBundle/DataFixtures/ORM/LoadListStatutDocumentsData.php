<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListTypeDocuments;


class LoadListStatutDocumentsData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListStatutDocuments';

    /**
     * @var array
     */
    protected $_lists = array(
        'A optenir',
        'Non demandé',
    );
}