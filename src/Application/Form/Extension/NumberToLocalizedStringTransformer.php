<?php
namespace Application\Form\Extension;

use Symfony\Component\Form\Extension\Core\DataTransformer\NumberToLocalizedStringTransformer as BaseStringTransformer;

class NumberToLocalizedStringTransformer extends BaseStringTransformer
{
    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return parent::reverseTransform(str_replace('.', ',', $value));
    }

    protected function getNumberFormatter()
    {
        $formatter = parent::getNumberFormatter();
        $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 100);

        return $formatter;
    }
}
