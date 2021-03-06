<?php

namespace Application\Sonata\BehatBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class BehatCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('behat:bundle')
            ->setDescription('behat bundle @BundleName')
           ->setDefinition(array(new InputArgument('name', InputArgument::REQUIRED, 'bundle')))
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $input->getArgument('name');
        if ($bundle) {
            $text = '';
            $command = 'php ' . ROOT_PATH . '/vendor/behat/behat/bin/behat ' . $bundle;
            exec($command, $text);
            $output->writeln($text);
        }
    }
}