<?php

require '../Core/Date.php';
require '../Core/Date/Repeat.php';

$dateRepeat = new Vhmis_Date_Repeat('2012-04-05 08:00:00', '2012-04-11 09:00:00', '2012-05-03 07:00:00');

$a = $dateRepeat->calculateWeeklyRepeat(array('freq' => 1, 'weekday' => array(3, 1, 7, 5)));

print_r($a);

$dateRepeat2 = new Vhmis_Date_Repeat('2012-04-24 08:00:00', '2012-04-11 09:00:00', '2013-04-24 07:00:00');

$a = $dateRepeat2->calculateMonthlyRepeat(array('freq' => 1, 'type' => 0, 'monthday' => array(24, 9)));

print_r($a);

$a = $dateRepeat2->calculateMonthlyRepeat(array('freq' => 1, 'type' => 1, 'weekday' => 3, 'weekday_position' => 5));

print_r($a);

$dateRepeat3 = new Vhmis_Date_Repeat('2012-04-24 08:00:00', '2012-04-11 09:00:00', '2016-04-24 07:00:00');

$a = $dateRepeat3->calculateYearlyRepeat(array('freq' => 1, 'type' => 0, 'month' => array(4, 6, 2), 'monthday' => array(24)));

print_r($a);