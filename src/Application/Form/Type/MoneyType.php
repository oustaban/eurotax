<?php
namespace Application\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Application\Form\Extension\MoneyToLocalizedStringTransformer;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

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

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'decorator' => null,
            'precision' => 2,
            'grouping'  => false,
            'divisor'   => 1,
            'currency'  => 'EUR',
            'compound'  => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if (isset($options['decorator'])){
            $view->vars['money_pattern'] = $options['decorator']($view->vars['money_pattern']);
        }
    }

}
