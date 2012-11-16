<?php

namespace Application\Sonata\ClientBundle\Features\Context;

use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Application\Tools\MinkContext;

class FeatureContext extends MinkContext implements KernelAwareInterface
{
}
