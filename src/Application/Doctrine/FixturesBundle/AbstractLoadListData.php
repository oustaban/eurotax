<?php

namespace Application\Doctrine\FixturesBundle;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;


abstract class AbstractLoadListData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @var string
     */
    protected $_budleName = 'ApplicationSonataClientOperationsBundle';

    /*
     * @var string
     */
    protected $_className = '';

    /**
     * @var array
     */
    protected $_lists = array();

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        if ($this->_lists) {

            $repositoryName = $this->_budleName . ':' . $this->_className;

            $class = $manager->getClassMetadata($repositoryName)->getName();

            foreach ($this->_lists as $name) {

                $is_name = $manager->getRepository($repositoryName)->findOneByName($name);

                if (!$is_name) {
                    $list = new $class();
                    $list->setName($name);
                    $manager->persist($list);
                }
            }

            $manager->flush();
        }

    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param ObjectManager $manager
     * @param $repositoryName
     */
    function truncateTable(ObjectManager $manager, $repositoryName)
    {
        $tbl = $manager->getClassMetadata($repositoryName)->getTableName();

        $platform = $manager->getConnection()->getDatabasePlatform();

        $manager->getConnection()->executeUpdate($platform->getTruncateTableSQL($tbl, true));
    }

}