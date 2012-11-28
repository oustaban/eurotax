<?php
namespace Application\Form\Type;

use Symfony\Component\Form\AbstractType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToLocalizedStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToArrayTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;


class MoisType extends BaseType
{
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $choices = array();
        $default_year = range(date('Y') - 5, date('Y') + 5);
        $default_month = range(1, 12);

        foreach ($default_year as $year) {
            foreach ($default_month as $month) {
                $key = $year . '-' . sprintf($month, '%02d');
                $choices[$key] = $this->formatTimestamps(new \DateTime('01-' . $month . '-' . $year), 'YYYY') . ' ' . $this->formatTimestamps(new \DateTime('01-' . $month . '-' . $year), 'MMMM');
            }
        }
        $resolver->replaceDefaults(array(
            'day' => array(1),
            'required' => false,
            'choices' => $choices,
            'attr' => array(
                'class' => 'mois'
            ),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('mois', 'choice',
            array(
                'required' => $options['required'],
                'choices' => $options['choices'],
                'attr' => array(
                    'class' => 'select-mois'
                )
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'mois';
    }

    /**
     * @return null|string|\Symfony\Component\Form\FormTypeInterface
     */
    public function getParent()
    {
        return 'date';
    }

    /**
     * @param $datetime
     * @param null $format
     * @return string
     */
    private function formatTimestamps($datetime, $format = null)
    {
        if (empty($datetime)) return;

        $dateFormat = is_int($format) ? $format : \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($format) ? $format : null;

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            $dateFormat,
            $timeFormat,
            null,
            $calendar,
            $pattern
        );
        $formatter->setLenient(false);
        $timestamp = $datetime->getTimestamp();

        return ucfirst($formatter->format($timestamp));
    }
}