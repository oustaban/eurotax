<?php
namespace Application\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Application\Form\Extension\MoneyToLocalizedStringTransformer;

use Symfony\Component\Form\Extension\Core\Type\MoneyType as BaseType;

class MoneyType extends BaseType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = $builder->getAttribute('sonata_admin');
        if (isset($attr['class'])){
            $attr['class'] .= ' money';
            $builder->setAttribute('sonata_admin', $attr);
        }

        $builder->addViewTransformer(new MoneyToLocalizedStringTransformer(
            $options['precision'],
            $options['grouping'],
            null,
            $options['divisor']
        ))
        ;
    }

}
