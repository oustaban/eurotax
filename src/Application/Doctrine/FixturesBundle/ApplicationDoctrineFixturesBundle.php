<?php

namespace Application\Doctrine\FixturesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;

class ApplicationDoctrineFixturesBundle extends Bundle
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
       return 'DoctrineFixturesBundle';
    }
}
