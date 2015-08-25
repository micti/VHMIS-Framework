<?php

namespace Vhmis\I18n\Formatter;

use Vhmis\I18n\Resource\Resource;
use Vhmis\I18n\Plural\Plural;

/**
 * Relative string of 2 datetimes.
 *
 * Example: 2 hours ago, in 2 hours...
 */
class DateTimeRelative
{

    /**
     * Relative string of 2 datetimes.
     *
     * @param Vhmis\I18n\DateTime\DateTime $date
     * @param Vhmis\I18n\DateTime\DateTime|null $rootDate
     * @param string $locale
     *
     * @return string
     */
    public function relative($date, $rootDate = null, $locale = '')
    {
        if($rootDate === null) {
            $rootDate = $date->createNewWithSameI18nInfo();
            $rootDate->setNow();
        }

        $diff = $date->diff->diff($rootDate);
        $fields = ['era', 'year', 'month', 'day', 'hour', 'minute', 'second'];
        $direction = 'past';

        foreach ($fields as $field) {
            if ($diff[$field]) {
                break;
            }
        }

        if ($diff[$field] < 0) {
            $direction = 'future';
            $diff[$field] *= -1;
        }

        $pruralRule = Plural::getCardinalType($diff[$field], $locale);
        $patternData = $dateFieldData = Resource::getDateField($field, $locale);

        return str_replace('{0}', $diff[$field], $patternData['relativeTime-type-' . $direction]['relativeTimePattern-count-' . $pruralRule]);
    }
}
