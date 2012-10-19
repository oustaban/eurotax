<?php

namespace Application\Symfony\FrameworkBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
//use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Sensio\Bundle\DistributionBundle\DependencyInjection\SensioDistributionExtension as Extension;

class ApplicationSymfonyFrameworkExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        parent::load($configs, $container);
    }

    public function getAlias()
    {
        return 'application_symfony_framework';
    }
}
