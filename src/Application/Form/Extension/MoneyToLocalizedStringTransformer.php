<?php
namespace Application\Form\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\MoneyToLocalizedStringTransformer as BaseStringTransformer;

class MoneyToLocalizedStringTransformer extends BaseStringTransformer
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return parent::reverseTransform(str_replace('.', ',', $value));
    }
}
