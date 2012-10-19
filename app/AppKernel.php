<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppKernel extends Kernel
{
    protected static $_container;

    protected function initializeContainer()
    {
        parent::initializeContainer();
        static::$_container = $this->getContainer();
    }

    /**
     * @return ContainerInterface
     */
    public static function getStaticContainer()
    {
        return static::$_container;
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),

            new Sonata\AdminBundle\SonataAdminBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),

            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            new Application\Sonata\DevisesBundle\ApplicationSonataDevisesBundle(),
            new Application\Sonata\ImpotsBundle\ApplicationSonataImpotsBundle(),
            new Application\Sonata\ClientBundle\ApplicationSonataClientBundle(),

            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Application\Doctrine\FixturesBundle\ApplicationDoctrineFixturesBundle(),
            new Application\Symfony\FrameworkBundle\ApplicationSymfonyFrameworkBundle(),
            new Application\Sonata\ClientOperationsBundle\ApplicationSonataClientOperationsBundle(),

            new Application\Sonata\DashboardBundle\ApplicationSonataDashboardBundle(),
        );

        if (in_array($this->getEnvironment(), array('prod'))) {
            $bundles[] = new Application\Sonata\ErrorsBundle\ApplicationSonataErrorsBundle();
        }

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Acme\DemoBundle\AcmeDemoBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
