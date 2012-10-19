<?php

namespace Application\Symfony\FrameworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;

class ApplicationSymfonyFrameworkBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FrameworkBundle';
    }
}
