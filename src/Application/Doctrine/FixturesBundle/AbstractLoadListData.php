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

    protected $_repositoryName;

    /**
     * @var array
     */
    protected $_lists = array();

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->setRepositoryName($this->_budleName . ':' . $this->_className);

        $this->saveFixtures($manager);
    }

    /**
     * @param $manager
     */
    protected function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        $setAlias = method_exists($class, 'setAlias') && method_exists($this, 'nameToAlias');

        if ($this->_lists) {
            foreach ($this->_lists as $name) {

                $is_name = $manager->getRepository($this->getRepositoryName())->findOneByName($name);

                if (!$is_name) {
                    $list = new $class();
                    $list->setName($name);
                    if ($setAlias) {
                        $list->setAlias($this->nameToAlias($name));
                    }
                    $manager->persist($list);
                }
            }
            $manager->flush();
        }
    }

    /**
     * @param $name
     */
    protected function setRepositoryName($name)
    {
        $this->_repositoryName = $name;
    }

    /**
     * @return mixed
     */
    protected function getRepositoryName()
    {
        return $this->_repositoryName;
    }

    /**
     * @param $manager
     * @return mixed
     */
    protected function getClass($manager)
    {
        return $manager->getClassMetadata($this->getRepositoryName())->getName();
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