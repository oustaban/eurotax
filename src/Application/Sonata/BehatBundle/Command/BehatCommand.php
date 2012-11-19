<?php

namespace Application\Sonata\BehatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;


class BehatCommand extends ContainerAwareCommand
{
    protected $bundles = array(
        'ClientBundle',
        'ClientOperationsBundle',
        'DashboardBundle',
        'DevisesBundle',
        //'ErrorsBundle',
        'ImpotsBundle',
        'UserBundle',
    );

    protected function configure()
    {
        $this
            ->setName('behat:bundle-all')
            ->setDescription('behat bundle all');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->bundles as $bundle) {
            $text = '';
            $command = 'php '.ROOT_PATH.'/bin/behat @ApplicationSonata'.$bundle;
            exec($command, $text);
            $output->writeln($text);
        }
    }
}