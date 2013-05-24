<?php
namespace Application\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Application\Form\Extension\NumberToLocalizedStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\NumberType as BaseType;

class NumberType extends BaseType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $attr = $builder->getAttribute('sonata_admin');
        if (isset($attr['class'])){
            $attr['class'] .= ' number';
            $builder->setAttribute('sonata_admin', $attr);
        }

        $builder->addViewTransformer(new NumberToLocalizedStringTransformer(
            $options['precision'],
            $options['grouping'],
            $options['rounding_mode']
        ));
    }

    
    
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(array(
    		'precision' => 2,
    		'grouping'  => true,
        	'rounding_mode' => \NumberFormatter::ROUND_HALFUP,
    		'compound'      => false,
    	));
    }
    
}
