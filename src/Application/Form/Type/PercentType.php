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
        $builder->addViewTransformer(new PercentToLocalizedStringTransformer($options['precision'], $options['type']));
    }

}
