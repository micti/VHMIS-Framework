<?php

require '../Vhmis/DateTime/DateTime.php';
require '../Vhmis/DateTime/Calendar/CalendarInterface.php';
require '../Vhmis/DateTime/Calendar/CalendarAbstract.php';
require '../Vhmis/DateTime/Calendar/SchoolYear.php';

$a = new \Vhmis\DateTime\Calendar\SchoolYear();

$a->setStartDate('2012-08-13')->setEndDate('2013-07-07');

$b = $a->getTotalDay();

$d = $a->getTotalMonth();

$e = $a->getTotalWeek();

$c = $a->getDateTimeInterval();

var_dump($b);

var_dump($d);

var_dump($e);

var_dump($c);

var_dump($a);