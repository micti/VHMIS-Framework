<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime\Helper;

use Vhmis\I18n\DateTime\DateTime;

class Diff extends AbstractHelper
{

    /**
     * Method list and param number
     *
     * @var array
     */
    protected $methodList = array(
        'diff' => 1,
        'diffEra' => 1,
        'diffYear' => 1,
        'diffMonth' => 1,
        'diffDay' => 1,
        'diffHour' => 1,
        'diffMinute' => 1,
        'diffSecond' => 1,
        'diffMillisecond' => 1,
        'diffAbsolute' => 1,
        'diffAbsoluteEra' => 1,
        'diffAbsoluteYear' => 1,
        'diffAbsoluteMonth' => 1,
        'diffAbsoluteWeek' => 1,
        'diffAbsoluteDay' => 1,
        'diffAbsoluteWeek' => 1,
        'diffAbsoluteHour' => 1,
        'diffAbsoluteMinute' => 1,
        'diffAbsoluteSecond' => 1,
        'diffAbsoluteMillisecond' => 1,
        'diffCheck' => 1
    );

    /**
     * Diff for all fields
     *
     * @param DateTime $date
     *
     * @return array
     */
    public function diff($date)
    {
        $milli = $this->date->getMilliTimestamp();

        $diff = array(
            'era' => $this->date->diffField($date, 0),
            'year' => $this->date->diffField($date, 1),
            'month' => $this->date->diffField($date, 2),
            'day' => $this->date->diffField($date, 5),
            'hour' => $this->date->diffField($date, 11),
            'minute' => $this->date->diffField($date, 12),
            'second' => $this->date->diffField($date, 13),
            'millisecond' => $this->date->diffField($date, 14),
        );

        $this->date->setMilliTimestamp($milli);

        return $diff;
    }

    /**
     * Diff for era
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffEra($date)
    {
        return $this->diffNotEffectValue($date, 0);
    }

    /**
     * Diff for year
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffYear($date)
    {
        return $this->diffNotEffectValue($date, 1);
    }

    /**
     * Diff for month
     *
     * @param DateTime $date
     */
    public function diffMonth($date)
    {
        return $this->diffNotEffectValue($date, 2);
    }

    /**
     * Diff for day
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffDay($date)
    {
        return $this->diffNotEffectValue($date, 5);
    }

    /**
     * Diff for hour
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffHour($date)
    {
        return $this->diffNotEffectValue($date, 11);
    }

    /**
     * Diff for minute
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffMinute($date)
    {
        return $this->diffNotEffectValue($date, 12);
    }

    /**
     * Diff for second
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffSecond($date)
    {
        return $this->diffNotEffectValue($date, 13);
    }

    /**
     * Diff for millisecond
     *
     * @param DateTime $date
     *
     * @return int|false
     */
    public function diffMillisecond($date)
    {
        return $this->diffNotEffectValue($date, 14);
    }

    /**
     * Absolute diff
     * - Not care about value of fields that are 'lower' than it
     * - Convert and add value of fields that are 'greater' than it
     *
     * @param DateTime $date
     */
    public function diffAbsolute($date)
    {
        return array(
            'era' => $this->diffAbsoluteEra($date),
            'year' => $this->diffAbsoluteYear($date),
            'month' => $this->diffAbsoluteMonth($date),
            'week' => $this->diffAbsoluteWeek($date),
            'day' => $this->diffAbsoluteDay($date),
            'hour' => $this->diffAbsoluteHour($date),
            'minute' => $this->diffAbsoluteMinute($date),
            'second' => $this->diffAbsoluteSecond($date),
            'millisecond' => $this->diffAbsoluteMillisecond($date),
        );
    }

    /**
     * Absolute diff for era
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteEra($date)
    {
        return $date->getField(0) - $this->date->getField(0);
    }

    /**
     * Absolute diff for year
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteYear($date)
    {
        return $date->getField(19) - $this->date->getField(19);
    }

    /**
     * Absolute diff for month
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteMonth($date)
    {
        $millisecondDate1 = $this->date->getMilliTimestamp();
        $millisecondDate2 = $date->getMilliTimestamp();

        $date->setField(5, 1);
        $date->setTime(0, 0, 0);
        $date->setField(14, 0);

        $this->date->setField(5, 1);
        $this->date->setTime(0, 0, 0);
        $this->date->setField(14, 0);

        $diff = $this->date->diffField($date, 2);

        $this->date->setMilliTimestamp($millisecondDate1);
        $date->setMilliTimestamp($millisecondDate2);

        return $diff;
    }

    /**
     * Absolute diff for week
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteWeek($date)
    {
        $millisecondDate1 = $this->date->getMilliTimestamp();
        $millisecondDate2 = $date->getMilliTimestamp();
        $firstDay2 = $date->getWeekFirstDay();

        $date->setTime(0, 0, 0);
        $date->setField(14, 0);
        $date->setWeekFirstDay($this->date->getWeekFirstDay());
        $date->gotoFirstDayOfWeek();

        $this->date->setTime(0, 0, 0);
        $this->date->setField(14, 0);
        $this->date->gotoFirstDayOfWeek();

        $diff = ($date->getTimestamp() - $this->date->getTimestamp()) / 24 / 60 / 60 / 7;

        $this->date->setMilliTimestamp($millisecondDate1);
        $date->setWeekFirstDay($firstDay2)->setMilliTimestamp($millisecondDate2);

        return $diff;
    }

    /**
     * Absolute diff for day
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteDay($date)
    {
        $millisecondDate1 = $this->date->getMilliTimestamp();
        $millisecondDate2 = $date->getMilliTimestamp();

        $date->setTime(0, 0, 0);
        $date->setField(14, 0);

        $this->date->setTime(0, 0, 0);
        $this->date->setField(14, 0);

        $diff = ($date->getTimestamp() - $this->date->getTimestamp()) / 24 / 60 / 60;

        $this->date->setMilliTimestamp($millisecondDate1);
        $date->setMilliTimestamp($millisecondDate2);

        return $diff;
    }

    /**
     * Absolute diff for hour
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteHour($date)
    {
        $millisecondDate1 = $this->date->getMilliTimestamp();
        $millisecondDate2 = $date->getMilliTimestamp();

        $date->setField(13, 0);
        $date->setField(12, 0);
        $date->setField(14, 0);

        $this->date->setField(13, 0);
        $this->date->setField(12, 0);
        $this->date->setField(14, 0);

        $diff = ($date->getTimestamp() - $this->date->getTimestamp()) / 60 / 60;

        $this->date->setMilliTimestamp($millisecondDate1);
        $date->setMilliTimestamp($millisecondDate2);

        return $diff;
    }

    /**
     * Absolute diff for minute
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteMinute($date)
    {
        $millisecondDate1 = $this->date->getMilliTimestamp();
        $millisecondDate2 = $date->getMilliTimestamp();

        $date->setField(13, 0);
        $date->setField(14, 0);

        $this->date->setField(13, 0);
        $this->date->setField(14, 0);

        $diff = ($date->getTimestamp() - $this->date->getTimestamp()) / 60;

        $this->date->setMilliTimestamp($millisecondDate1);
        $date->setMilliTimestamp($millisecondDate2);

        return $diff;
    }

    /**
     * Absolute diff for second
     *
     * @param DateTime $date
     *
     * @return int
     */
    public function diffAbsoluteSecond($date)
    {
        return $date->getTimestamp() - $this->date->getTimestamp();
    }

    /**
     * Find diff or same value of all fields.
     *
     * @param DateTime $date
     *
     * @return bool[]
     */
    public function diffCheck($date)
    {
        return [
            'era' => $this->isDiffField($date, 0),
            'year' => $this->isDiffField($date, 1),
            'month' => $this->isDiffField($date, 2),
            'week' => $this->isDiffField($date, 8),
            'day' => $this->isDiffField($date, 5),
            'am_pm' => $this->isDiffField($date, 9),
            'hour_am_pm' => $this->isDiffField($date, 10), // hour in am/pm
            'hour' => $this->isDiffField($date, 11),
            'minute' => $this->isDiffField($date, 12),
            'second' => $this->isDiffField($date, 13),
            'millisecond' => $this->isDiffField($date, 14)
        ];
    }

    /**
     * Absolute diff for millisecond
     *
     * @param DateTime $date
     *
     * @return double
     */
    public function diffAbsoluteMillisecond($date)
    {
        return $date->getMilliTimestamp() - $this->date->getMilliTimestamp();
    }

    /**
     * Find diff or same value of a field.
     *
     * @param DateTime $date
     * @param int $field
     *
     * @return bool
     */
    protected function isDiffField($date, $field)
    {
        return $this->date->getField($field) !== $date->getField($field);
    }

    /**
     * Get not affected diff value
     *
     * @param DateTime $date
     * @param int      $field
     *
     * @return int|false
     */
    protected function diffNotEffectValue($date, $field)
    {
        $milli = $this->date->getMilliTimestamp();

        $diff = $this->date->diffField($date, $field);

        $this->date->setMilliTimestamp($milli);

        return $diff;
    }
}
