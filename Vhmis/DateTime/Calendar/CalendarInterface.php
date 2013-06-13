<?php

namespace Vhmis\DateTime\Calendar;

interface CalendarInterface
{
    public function setStartDate($date);

    public function setEndDate($date);

    public function getTotalMonth();

    public function getTotalWeek();

    public function getTotalDay();
}

