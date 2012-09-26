<?php

namespace Application\Sonata\ClientOperationsBundle\DataFixtures;

use Application\Doctrine\FixturesBundle\AbstractLoadListData as AbstractLoadListDataMain;

abstract class AbstractLoadListData extends AbstractLoadListDataMain
{
    protected $_budleName = 'ApplicationSonataClientOperationsBundle';
}