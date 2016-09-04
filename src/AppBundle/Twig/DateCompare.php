<?php
/**
 * Created by PhpStorm.
 * User: LARRY
 * Date: 3/30/2016
 * Time: 5:14 PM
 */

namespace AppBundle\Twig;

class DateCompare extends \Twig_Extension
{

    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('dateCompare', array($this, 'dateCompareFilter')),);
    }

    public function dateCompareFilter($date1, $date2 = 'now')
    {
        if ($date2 == 'now') {
            $date2 = new \DateTime('now');
        } elseif (is_string($date2)) {
            if (date_create_from_format('d/m/Y', $date2)) {
                $date2 = date_create_from_format('d/m/Y', $date2);
            }
        }

        if (is_a($date1, 'DateTime') && is_a($date2, 'DateTime')) {
            $diff = date_diff($date1, $date2);
            if ($diff->invert) {
                $days = $diff->days;
            } else {
                if ($diff->days == 0) {
                    $days = $diff->days;
                } else {
                    $days = '-' . $diff->days;
                }
            }
        } else {
            $days = 'Error: neet it DateTime Obj';
        }

        return $days;
    }

    public function getName()
    {
        return 'date_compare';
    }
}