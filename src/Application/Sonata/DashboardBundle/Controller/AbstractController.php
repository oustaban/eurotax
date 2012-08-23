<?php

namespace Application\Sonata\DashboardBundle\Controller;

use Sonata\AdminBundle\Controller\CoreController;


/**
 * AbstractController controller.
 *
 */
class AbstractController extends CoreController
{
    protected function _getDashboardAlias()
    {
        $controllerExploded = explode('\\', get_class($this));
        return preg_replace('/(.*)Controller/', '$1', array_pop($controllerExploded));
    }

    protected function _isGranted($serviceId)
    {
        $admin = $this->container->get($serviceId);
        if (isset($admin->dashboards))
        {
            $dashboards = is_array($admin->dashboards)?$admin->dashboards:array($admin->dashboards);
            return in_array($this->_getDashboardAlias(), $dashboards);
        }

        return false;
    }

    public function dashboardAction()
    {
        /* @var $pool Pool */
        $pool = $this->container->get('sonata.admin.pool');
        $adminGroups = $pool->getAdminGroups();

        foreach ($adminGroups as $name => &$adminGroup) {
            if (isset($adminGroup['items'])) {
                foreach ($adminGroup['items'] as $key => $id) {
                    if (!$this->_isGranted($id))
                    {
                        unset($adminGroup['items'][$key]);
                    }
                }
            }
        }
        $pool->setAdminGroups($adminGroups);

        return parent::dashboardAction();
    }
}
