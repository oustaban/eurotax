<?php

namespace Application\Sonata\ClientOperationsBundle\DataFixtures\ORM;

use Application\Sonata\ClientOperationsBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientOperationsBundle\Entity\ListStatuses;


class LoadListStatusesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListStatuses';

    /**
     * @var array
     */
    protected $_lists = array(
        'verrouillé',
        'déverrouillé',
    );
}