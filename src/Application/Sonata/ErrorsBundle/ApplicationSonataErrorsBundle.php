<?php

namespace Application\Sonata\ErrorsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bundle\TwigBundle\TwigBundle;

class ApplicationSonataErrorsBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'TwigBundle';
    }
}