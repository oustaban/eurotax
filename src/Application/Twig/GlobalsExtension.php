<?php

namespace Application\Twig;

use Twig_Extension;
use Twig_Function_Method;


class GlobalsExtension extends Twig_Extension
{
    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'globalsGet' => new Twig_Function_Method($this, 'get'),
            'globalsInc' => new Twig_Function_Method($this, 'inc'),
            'globalsDec' => new Twig_Function_Method($this, 'dec'),
        );
    }

    public function get($varName) {
        global $$varName;

        return $$varName;
    }

    function inc($varName, $step = 1){
        global $$varName;

        return $$varName += $step;
    }

    function dec($varName, $step = 1){
        global $$varName;

        return $$varName -= $step;
    }

    public function getName()
    {
        return 'globals_extension';
    }
}