<?php

namespace Application\Sonata\UserBundle\DataFixtures;

use Application\Doctrine\FixturesBundle\AbstractLoadListData as AbstractLoadListDataMain;

abstract class AbstractLoadListData extends AbstractLoadListDataMain
{
    /**
     * @var string
     */
    protected $_budleName = 'ApplicationSonataUserBundle';

}