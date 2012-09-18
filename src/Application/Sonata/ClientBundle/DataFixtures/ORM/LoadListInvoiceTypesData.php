<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCivilites;


class LoadListInvoiceTypesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListInvoiceTypes';

    /**
     * @var array
     */
    protected $_lists = array(
        'Récurrent',
        'Récurrent',
        'Ponctuel',
    );
}