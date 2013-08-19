<?php

namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\OptionsResolver\Options;

class AmountType extends AbstractType
{
    protected $_extension = '';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	
        $builder
            ->add('montant' . $this->_extension, 'money', array(
            'attr' => array('class' => 'montant'),
            'currency' => '',
        ))
            ->add('devise' . $this->_extension, 'entity', array(
            'class' => 'ApplicationSonataClientBundle:ListDevises',
            'attr' => array('class' => 'devise'),
            /* 'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('d')
                    ->orderBy('d.alias', 'ASC');
            }, */
            		
            'choices' => function(Options $options) {
	            $qb = $options['em']->getRepository('ApplicationSonataClientBundle:ListDevises')
	                    ->createQueryBuilder('d')->orderBy('d.alias', 'ASC');
	            return $qb->getQuery()->getResult();
	        }		
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'virtual' => true,
        	'grouping'  => true,
        ));
    }

    public function getName()
    {
        return 'amount';
    }
}