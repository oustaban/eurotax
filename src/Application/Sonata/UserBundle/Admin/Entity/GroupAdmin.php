<?php
namespace Application\Sonata\UserBundle\Admin\Entity;

use Sonata\UserBundle\Admin\Entity\GroupAdmin as BaseGroupAdmin;

class GroupAdmin extends BaseGroupAdmin
{
    public $dashboards = array('Admin');
}
