<?php

namespace Application\Doctrine\FixturesBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand as DoctrineCommand;

class LoadDataFixturesDoctrineCommand extends DoctrineCommand
{

    protected function configure()
    {
        parent::configure();
        $this->addOption('fixture', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The file to load data fixture from.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $input->setOption('append', true);

        $this->fixFixturesPath($input);
        $this->fixtureOption($input);

        parent::execute($input, $output);
    }


    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    protected function fixFixturesPath(InputInterface $input)
    {
        $fixtures = $input->getOption('fixtures');
        if (!is_array($fixtures)) {
            $fixtures = array($fixtures);
        }

        array_walk($fixtures, function (&$item, $key) {
            $item = SRC_PATH . '/' . $item;
        });

        $input->setOption('fixtures', $fixtures);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    protected function fixtureOption(InputInterface $input)
    {
        $fixtures = $input->getOption('fixtures');
        if (!is_array($fixtures)) {
            $fixtures = array($fixtures);
        }

        $fixture = $input->getOption('fixture');
        if (!is_array($fixture)) {
            $fixture = array($fixture);
        }

        foreach ($fixture as $file) {
            $new_dir = dirname(sys_get_temp_dir() . '/' . $file);
            if (!is_dir($new_dir)) {
                mkdir($new_dir, 0777, true);
            }
            copy(SRC_PATH . '/' . $file, $new_dir . '/' . basename($file));
            $fixtures[] = $new_dir;
        }

        $input->setOption('fixtures', $fixtures);
    }
}
