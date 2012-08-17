<?php

namespace Application\Doctrine\FixturesBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand as DoctrineCommand;

class LoadDataFixturesDoctrineCommand extends DoctrineCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('append', true);
        parent::execute($input, $output);
    }
}
