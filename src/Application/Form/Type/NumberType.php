<?php
namespace Application\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Application\Form\Extension\NumberToLocalizedStringTransformer;

use Symfony\Component\Form\Extension\Core\Type\NumberType as BaseType;

class NumberType extends BaseType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = $builder->getAttribute('sonata_admin');
        $attr['class'] .= ' number';
        $builder->setAttribute('sonata_admin', $attr);

        $builder->addViewTransformer(new NumberToLocalizedStringTransformer(
            $options['precision'],
            $options['grouping'],
            $options['rounding_mode']
        ));
    }

}
