<?php

namespace Application\Symfony\FrameworkBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand as BaseCacheClearCommand;

class CacheClearCommand extends BaseCacheClearCommand
{

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $this->saveParameterAssets();
    }

    protected function saveParameterAssets()
    {
        $version = trim(exec('git rev-parse HEAD'));

        /** @var $configurator \Sensio\Bundle\DistributionBundle\Configurator\Configurator */
        $configurator = $this->getContainer()->get('sensio.distribution.webconfigurator');

        $configurator->mergeParameters(array(
            'assets_version' => $version
        ));

        $configurator->write();
    }
}
