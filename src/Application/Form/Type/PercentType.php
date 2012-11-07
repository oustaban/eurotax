<?php
namespace Application\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Application\Form\Extension\PercentToLocalizedStringTransformer;

use Symfony\Component\Form\Extension\Core\Type\PercentType as BaseType;

class PercentType extends BaseType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = $builder->getAttribute('sonata_admin');
        if (isset($attr['class'])){
            $attr['class'] .= ' percent';
            $builder->setAttribute('sonata_admin', $attr);
        }

        $builder->addViewTransformer(new PercentToLocalizedStringTransformer($options['precision'], $options['type']));
    }

}
