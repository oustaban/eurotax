<?php

namespace Application\Twig;

use Twig_Extension;
use Twig_Filter_Method;


class DateFmtFormatExtension extends Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            'datefmt_format' => new Twig_Filter_Method($this, 'datefmtFormatFilter'),
        );
    }

    /**
     * @param \DateTime $datetime
     * @param null|string $format
     * @return string
     */
    public function datefmtFormatFilter($datetime, $format = null)
    {
        if(empty($datetime)) return ;

        $dateFormat = is_int($format) ? $format : \IntlDateFormatter::MEDIUM;
        $timeFormat = \IntlDateFormatter::NONE;
        $calendar = \IntlDateFormatter::GREGORIAN;
        $pattern = is_string($format) ? $format : null;

        $formatter = new \IntlDateFormatter(
            \Locale::getDefault(),
            $dateFormat,
            $timeFormat,
            null, //$datetime->getTimezone()->getName(),
            $calendar,
            $pattern
        );
        $formatter->setLenient(false);
        $timestamp = $datetime->getTimestamp();

        return $formatter->format($timestamp);
    }

    public function getName()
    {
        return 'datefmt_extension';
    }
}