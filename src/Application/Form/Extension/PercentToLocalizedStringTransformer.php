<?php
namespace Application\Form\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\PercentToLocalizedStringTransformer as BaseStringTransformer;

class PercentToLocalizedStringTransformer extends BaseStringTransformer
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return parent::reverseTransform(str_replace('.', ',', $value));
    }
}
