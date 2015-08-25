<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\I18n\DateTime;

use \Vhmis\Utils\DateTime as DateTimeUtils;

/**
 * I18n Datetime class
 *
 * Support many calendars with locale info
 *
 * @method \Vhmis\I18n\DateTime\DateTime setMillisecond(int $millisecond) Set millisecond
 * @method \Vhmis\I18n\DateTime\DateTime setSecond(int $second) Set second
 * @method \Vhmis\I18n\DateTime\DateTime setMinute(int $minute) Set minute
 * @method \Vhmis\I18n\DateTime\DateTime setHour(int $hour) Set hour
 * @method \Vhmis\I18n\DateTime\DateTime setDay(int $day) Set day
 * @method \Vhmis\I18n\DateTime\DateTime setIsLeapMont(int $day) Set isLeapMonth
 * @method \Vhmis\I18n\DateTime\DateTime setMonth(int $month) Set month
 * @method \Vhmis\I18n\DateTime\DateTime setLeapMonth(int $month) Set leap month
 * @method \Vhmis\I18n\DateTime\DateTime setYear(int $year) Set year
 * @method \Vhmis\I18n\DateTime\DateTime setEra(int $era) Set era
 *
 * @method \Vhmis\I18n\DateTime\DateTime gotoPreviousDay() Set previous day
 * @method \Vhmis\I18n\DateTime\DateTime gotoNextDay() Set next day
 * @method \Vhmis\I18n\DateTime\DateTime gotoTomorrow() Set tomorrow
 * @method \Vhmis\I18n\DateTime\DateTime gotoYesterday() Set yesterday
 * @method \Vhmis\I18n\DateTime\DateTime gotoFirstDayOfMonth() Set first day of month
 * @method \Vhmis\I18n\DateTime\DateTime gotoLastDayOfMonth() Set last day of month
 * @method \Vhmis\I18n\DateTime\DateTime gotoDayOfWeek() Set day of week
 * @method \Vhmis\I18n\DateTime\DateTime gotoFirstDayOfWeek() Set first day of week
 * @method \Vhmis\I18n\DateTime\DateTime gotoLastDayOfWeek() Set last day of week
 * @method \Vhmis\I18n\DateTime\DateTime gotoNthDayOfMonth(int $type, int $nth) Set Nth day of month
 *
 * @method \Vhmis\I18n\DateTime\DateTime addMillisecond(int $amount) Add millisecond
 * @method \Vhmis\I18n\DateTime\DateTime addSecond(int $amount) Add second
 * @method \Vhmis\I18n\DateTime\DateTime addMinute(int $amount) Add minute
 * @method \Vhmis\I18n\DateTime\DateTime addHour(int $amount) Add hour
 * @method \Vhmis\I18n\DateTime\DateTime addDay(int $amount) Add day
 * @method \Vhmis\I18n\DateTime\DateTime addWeek(int $amount) Add week
 * @method \Vhmis\I18n\DateTime\DateTime addMonth(int $amount) Add month
 * @method \Vhmis\I18n\DateTime\DateTime addYear(int $amount) Add year
 * @method \Vhmis\I18n\DateTime\DateTime addEra(int $amount) Add era
 *
 * @method string getDate() Get date as ISO style
 * @method string getDateWithExtendedYear() Get date with extended year as ISO style
 * @method string getDateWithRelatedYear() Get date with related year as ISO style
 * @method string getTime() Get time as ISO style
 * @method string getDateTime() Get datetime as ISO style
 * @method string getDateTimeWithExtendedYear() Get datetime with extended year as ISO style
 * @method string getDateTimeWithRelatedYear() Get datetime with related year as ISO style
 * @method int getMillsecond() Get second
 * @method int getSecond() Get second
 * @method int getMinute() Get minute
 * @method int getHour() Get hour
 * @method int getDay() Get day
 * @method int getWeek() Get week
 * @method int getMonth() Get month
 * @method int getYear() Get year
 * @method int getEra() Get era
 *
 * @method string formatFull() Format datetime in full style
 * @method string formatLong() Format datetime in long style
 * @method string formatMedium() Format datetime in medium style
 * @method string formatShort() Format datetime in short style
 *
 * @method array diff(\Vhmis\I18n\DateTime\DateTime $datetime) Get different
 * @method int diffEra(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by era
 * @method int diffYear(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by year
 * @method int diffMonth(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by month
 * @method int diffDay(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by day
 * @method int diffHour(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by hour
 * @method int diffMinute(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by minute
 * @method int diffSecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by second
 * @method int diffMillisecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get different by millisecond
 * @method array diffAbsolute(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different
 * @method int diffAbsoluteEra(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by era
 * @method int diffAbsoluteYear(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by year
 * @method int diffAbsoluteMonth(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by month
 * @method int diffAbsoluteWeek(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by week
 * @method int diffAbsoluteDay(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by day
 * @method int diffAbsoluteHour(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by hour
 * @method int diffAbsoluteMinute(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by minute
 * @method int diffAbsoluteSecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by second
 * @method double diffAbsoluteMillisecond(\Vhmis\I18n\DateTime\DateTime $datetime) Get absolute different by millisecond
 * @method bool[] diffCheck(\Vhmis\I18n\DateTime\DateTime $datetime) Find diff or same value of all fields
 *
 * @method array repeatByDay(string $fromDate, string $toDate, int $times, int $freg) Get repeated dates by day
 * @method array repeatByWeek(string $fromDate, string $toDate, int $times, int $freg) Get repeated dates by week
 * @method array repeatByMonth(string $fromDate, string $toDate, int $times, int $freg) Get repeated dates by month
 * @method array repeatByYear(string $fromDate, string $toDate, int $times, int $freg) Get repeated dates by year
 *
 * @property-read \Vhmis\I18n\DateTime\Helper\Convert $convert Convert helper
 * @property-read \Vhmis\I18n\DateTime\Helper\RelatedYear $relatedYear Related year helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Set $set Set helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Get $get Get helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Go $go Go helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Add $add Add helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Format $format Format helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Diff $diff Diff helper
 * @property-read \Vhmis\I18n\DateTime\Helper\Repeat $repeat Repeat helper
 */
class DateTime extends SimpleDateTime
{

    /**
     * Helper namespace
     *
     * @var string
     */
    protected $helperNamespace = '\Vhmis\I18n\DateTime\Helper';

    /**
     * Helper list
     *
     * @var array
     */
    protected $helperList = array(
        'convert' => 'Convert',
        'add' => 'Add',
        'set' => 'Set',
        'get' => 'Get',
        'format' => 'Format',
        'relatedYear' => 'RelatedYear',
        'diff' => 'Diff',
        'go' => 'Go',
        'repeat' => 'Repeat'
    );

    /**
     * Set date or/and time by ISO style datetime
     * Year is extended year
     *
     * @param string $string
     *
     * @return DateTime
     */
    public function modify($string)
    {
        $result = DateTimeUtils::praseFormat($string);

        if (isset($result['date'])) {
            $this->setDateWithExtenedYear(
                    (int) $result['date']['year'], (int) $result['date']['month'], (int) $result['date']['day']);
        }

        if (isset($result['time'])) {
            $this->setTime(
                    (int) $result['time']['hour'], (int) $result['time']['minute'], (int) $result['time']['second']
            );
        }

        return $this;
    }

    /**
     * Get day of week type
     *
     * Includes all weekdays, working days, weekend, and weekday type
     *
     * @return array
     */
    public function getDayOfWeekType()
    {
        $result = array();

        for ($i = 1; $i <= 7; $i++) {
            $type = $this->calendar->getDayOfWeekType($i);
            $transition = $this->calendar->getWeekendTransition($i);

            $result[0][] = $i;
            $result[$i] = array($type);

            if ($transition !== false) {
                $result[$i][] = $transition;
                $result[9][] = $i;
            } else {
                $result[8][] = $i;
            }
        }

        return $result;
    }

    /**
     * Get day of week postion
     *
     * @return array
     */
    public function getDayOfWeekPosition()
    {
        $sortedWeekday = $this->getSortedWeekday();
        $positions = array();

        for ($i = 0; $i < 7; $i++) {
            $positions[$sortedWeekday[$i]] = $i + 1;
        }

        return $positions;
    }

    /**
     * Get maximum values of field
     * - Greatest : greatest maxium
     * - Least : least maxium
     * - Actual : maxium based on current date/time values
     *
     * @param int $field
     *
     * @return array
     */
    public function getMaximumValueOfField($field)
    {
        $result = array(
            'greatest' => $this->calendar->getMaximum($field),
            'actual' => $this->calendar->getActualMaximum($field),
            'least' => $this->calendar->getLeastMaximum($field)
        );

        if ($field === 2) {
            $result['least']++;
            $result['actual']++;
            $result['greatest']++;
        }

        return $result;
    }

    /**
     * Get minimum values of field
     * - Greatest : greatest minimum
     * - Least : least minimum
     * - Actual : minimum based on current date/time values
     *
     * @param int $field
     *
     * @return array
     */
    public function getMinimumValueOfField($field)
    {
        $result = array(
            'least' => $this->calendar->getMinimum($field),
            'actual' => $this->calendar->getActualMinimum($field),
            'greatest' => $this->calendar->getGreatestMinimum($field)
        );

        if ($field === 2) {
            $result['least']++;
            $result['actual']++;
            $result['greatest']++;
        }

        return $result;
    }

    /**
     * Set now
     *
     * @return DateTime
     */
    public function setNow()
    {
        $this->setMilliTimestamp(\IntlCalendar::getNow());

        return $this;
    }

    /**
     * Magic __call method for some helpers
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $helperName = array('add', 'set', 'get', 'format', 'diff', 'go');

        foreach ($helperName as $helper) {
            if (strpos($name, $helper) === 0) {
                $helper = $this->getHelper($helper);

                return $helper($name, $arguments);
            }
        }

        return null;
    }

    /**
     * magic __get method for get helper object
     *
     * @param string $name
     *
     * @return \Vhmis\Utils\Std\AbstractDateTimeHelper
     */
    public function __get($name)
    {
        return $this->getHelper($name);
    }
}
