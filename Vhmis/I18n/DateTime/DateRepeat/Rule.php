<?php

namespace Vhmis\I18n\DateTime\DateRepeat;

use \Vhmis\I18n\DateTime\DateTime;
use \Vhmis\Utils\Exception\InvalidArgumentException;

class Rule
{
    /**
     * Repeate by, 4-7 for day, week, month, year
     * Default is 4 (repeat by day)
     *
     * @var int
     */
    protected $by = 4;
    protected $baseDate;
    protected $baseDay;
    protected $baseWeekday;
    protected $baseMonth;
    protected $endDate;

    /**
     * Times of repeat (including base date)
     * Default is 0 (no end)
     *
     * @var int
     */
    protected $times = 0;

    /**
     * Frequency of repeat
     * Default is 1
     *
     * @var int
     */
    protected $freq = 1;

    /**
     * Type of repeated date in month
     *
     * @var string
     */
    protected $type = 'day';

    /**
     *
     * @var int[]
     */
    protected $repeatedWeekdays = array();

    /**
     *
     * @var int
     */
    protected $repeatedDay;

    /**
     *
     * @var int
     */
    protected $repeatedDayPosition;

    /**
     *
     * @var int[]
     */
    protected $repeatedDays = array();

    /**
     *
     * @var int[]
     */
    protected $repeatedMonths = array();

    /**
     * DateTime helper object
     *
     * @var DateTime
     */
    protected $date;

    /**
     * Contruct
     */
    public function __construct()
    {

    }

    /**
     * Set repeat by day/week/month/year
     *
     * @param int $by
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setRepeatBy($by)
    {
        $this->by = $this->fixInt($by, 4, 7);

        return $this;
    }

    /**
     * Set base date
     *
     * @param DateTime $date
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setBaseDate($date)
    {
        if ($date instanceof DateTime === false) {
            throw new InvalidArgumentException('Date is not valid');
        }

        $this->baseDate = $date->getDateWithExtendedYear();

        $this->date = $date;
        $this->baseWeekday = $this->date->getField(7);
        $this->baseDay = $this->date->getField(5);
        $this->baseMonth = $this->date->getField(2);

        // auto
        $this->repeatedDay = $this->baseWeekday;
        $this->repeatedDayPosition = ceil($this->baseDay / 7);
        $this->repeatedDays = array($this->baseDay);
        $this->repeatedMonths = array($this->baseMonth);
        $this->repeatedWeekdays = array($this->baseWeekday);

        return $this;
    }

    /**
     *
     * @param string $date
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setEndDate($date)
    {
        if ($this->date == null) {
            $dateObject = new DateTime;
            $dateObject->modify($date);
            $dateEnd = $dateObject->getDateWithExtendedYear();
        } else {
            $this->date->modify($date);
            $dateEnd = $this->date->getDateWithExtendedYear();
            $this->date->modify($this->baseDate);
        }

        if ($dateEnd !== $date) {
            throw new InvalidArgumentException('End date is not valid');
        }

        $this->endDate = $date;

        return $this;
    }

    /**
     * Set times of repeat
     *
     * @param int $times
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setRepeatTimes($times)
    {
        $this->times = (int) $times;

        if ($this->times < 0) {
            throw new InvalidArgumentException('Must be int and greater than -1');
        }

        return $this;
    }

    /**
     * Set frequency
     *
     * @param int $freq
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setFrequency($freq)
    {
        $this->freq = (int) $freq;

        if ($this->freq < 1) {
            throw new InvalidArgumentException('Must be int and greater than 0');
        }

        return $this;
    }

    /**
     * Set repeated weekdays
     * Use 1-7 for sunday to staturday
     * Accept int array or int string spec by ','
     *
     * @param int[]|string $weekdays
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setRepeatWeekdays($weekdays)
    {
        $this->repeatedWeekdays = $this->fixIntArray($weekdays, 1, 7);

        return $this;
    }

    /**
     * Set type of date repeat
     *
     * - Repeat by day in month: 2nd, 3rd ....
     * - Repeat by relative day in month: first Monday, second Tuesday, last day ...
     */
    public function setType($type)
    {
        $this->type = 'day';

        if ($type === 'relative_day') {
            $this->type = 'relative_day';
        }

        return $this;
    }

    /**
     * Set day of repeated day in month
     * 0 common day, 1 - 7 for sunday to saturday, 8 working day, 9 weekdend
     *
     * @param string|int $day
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setRepeatedDay($day)
    {
        $this->repeatedDay = $this->fixInt($day, 0, 9);

        return $this;
    }

    /**
     * Set position of day of repeated day in month
     * (for type = relative_day)
     *
     * @param string|int position
     *
     * @return Rule
     *
     * @throws InvalidArgumentException
     */
    public function setRepeatedDayPosition($position)
    {
        // -10 - 10 ????
        $this->repeatedDayPosition = $this->fixInt($position, -10, 10);

        if ($this->repeatedDayPosition === 0) {
            throw new InvalidArgumentException('Position is not be 0');
        }

        return $this;
    }

    /**
     * Set repeated days, use 1-31, accept int array or int string spec by ','
     * (for type = month)
     *
     * @param int[]|string $days
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     *
     * @throws \InvalidArgumentException
     */
    public function setRepeatedDays($days)
    {
        $min = 1;
        $max = 31;

        if ($this->date !== null) {
            list($min, $max) = $this->getRangeOfField(5);
        }

        $this->repeatedDays = $this->fixIntArray($days, $min, $max);

        return $this;
    }

    /**
     * Set repeated months, use 1-12, accept int array or int string spec by ','
     *
     * @param int[]|string $months
     *
     * @return \Vhmis\DateTime\DateRepeat\Week
     *
     * @throws \InvalidArgumentException
     */
    public function setRepeatedMonths($months)
    {
        $min = 1;
        $max = 12;

        if ($this->date !== null) {
            list($min, $max) = $this->getRangeOfField(2);
        }

        $this->repeatedMonths = $this->fixIntArray($months, $min, $max);

        return $this;
    }

    /**
     * Get valid of rule
     *
     * @return boolean
     */
    public function isValid()
    {
        if ($this->baseDate === null) {
            return false;
        }

        $validMethods = array(
            5 => 'isValidRepeatByWeek',
            6 => 'isValidRepeatByMonth',
            7 => 'isValidRepeatByYear'
        );

        if (isset($validMethods[$this->by])) {
            $method = $validMethods[$this->by];

            return $this->$method();
        }

        return true;
    }

    /**
     * Get value of rule
     *
     * @return array
     */
    public function getInfo()
    {
        return array(
            'date'        => $this->date,
            'by'          => $this->by,
            'base'        => $this->baseDate,
            'baseDay'     => $this->baseDay,
            'baseWeekday' => $this->baseWeekday,
            'baseMonth'   => $this->baseMonth,
            'end'         => $this->endDate,
            'times'       => $this->times,
            'freq'        => $this->freq,
            'type'        => $this->type,
            'days'        => $this->repeatedDays,
            'weekdays'    => $this->repeatedWeekdays,
            'months'      => $this->repeatedMonths,
            'day'         => $this->repeatedDay,
            'position'    => $this->repeatedDayPosition
        );
    }
    
    /**
     * Reset rule
     *
     * @return \Vhmis\DateTime\DateRepeat\Rule
     */
    public function reset()
    {
        $this->baseDate = $this->endDate = null;
        $this->baseDay = $this->baseMonth = $this->baseWeekday = null;
        $this->repeatedDay = $this->repeatedDayPosition = null;
        $this->by = 4;
        $this->times = 0;
        $this->freq = 1;
        $this->type = 'day';
        $this->repeatedMonths = $this->repeatedWeekdays = $this->repeatedDays = array();

        return $this;
    }

    /**
     * Check valid of repeat by week
     *
     * @return boolean
     */
    protected function isValidRepeatByWeek()
    {
        if (array_search($this->baseWeekday, $this->repeatedWeekdays) === false) {
            return false;
        }

        return true;
    }

    /**
     * Check valid of repeat by month
     *
     * @return boolean
     */
    protected function isValidRepeatByMonth()
    {
        if ($this->type === 'day') {
            return $this->isValidDay();
        }

        return $this->isValidRelativeDay();
    }

    /**
     * Check valid of repeat by year
     *
     * @return boolean
     */
    protected function isValidRepeatByYear()
    {
        if (array_search($this->baseMonth, $this->repeatedMonths) === false) {
            return false;
        }

        if ($this->type === 'day') {
            return true;
        }

        return $this->isValidRelativeDay();
    }

    /**
     * Check day
     *
     * @return boolean
     */
    protected function isValidDay()
    {
        if (array_search($this->baseDay, $this->repeatedDays) === false) {
            return false;
        }

        return true;
    }

    /**
     * Check relative day
     *
     * @return boolean
     */
    protected function isValidRelativeDay()
    {
        $date = clone $this->date;
        $date->gotoFirstDayOfMonth();

        if ($date->getDateWithExtendedYear() === $this->baseDate) {
            $date->gotoLastDayOfMonth();
        }

        $date->gotoNthDayOfMonth($this->repeatedDay, $this->repeatedDayPosition);
        $currentDate = $date->getDateWithExtendedYear();

        if ($this->baseDate !== $currentDate) {
            return false;
        }

        return true;
    }

    /**
     * Check and fix int value
     *
     * @param int $number
     * @param int $min
     * @param int $max
     *
     * @return int
     *
     * @throws InvalidArgumentException
     */
    protected function fixInt($number, $min, $max)
    {
        $number = (int) $number;
        if ($number < $min || $number > $max) {
            throw new InvalidArgumentException('Only int from ' . $min . ' - ' . $max);
        }

        return $number;
    }

    /**
     * Check and fix int array
     *
     * @param int[]|string $data
     * @param int          $min
     * @param int          $max
     *
     * @return int[]
     *
     * @throws InvalidArgumentException
     */
    protected function fixIntArray($data, $min, $max)
    {
        $data = is_string($data) ? explode(',', $data) : $data;

        if (!is_array($data)) {
            throw new InvalidArgumentException('Only int array or string spec by `,`. From ' . $min . ' - ' . $max);
        }

        // Check
        foreach ($data as &$number) {
            $number = $this->fixInt($number, $min, $max);
        }

        $data = array_unique($data);
        sort($data);

        return $data;
    }

    /**
     * Get acceptable range of field of date
     *
     * @param int $field
     *
     * @return int[]
     */
    protected function getRangeOfField($field)
    {
        $field = (int) $field;

        $maxInfo = $this->date->getMaximumValueOfField($field);
        $max = $maxInfo['greatest'];
        $minInfo = $this->date->getMinimumValueOfField($field);
        $min = $minInfo['least'];

        return array($min, $max);
    }
}
