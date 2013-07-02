<?php
require '../Vhmis/DateTime/DateTime.php';
require '../Vhmis/DateTime/DateRepeat.php';

use \Vhmis\DateTime\DateRepeat;

$repeat = new DateRepeat;
//$repeat->setDateBegin('2013-06-24')->setRepeatType(4)->setRepeatInfo(array('freq' => 4));

//$dates = $repeat->findRepeat('2013-07-04', '2013-09-05');

//print_r($dates);

//$dates = $repeat->setTimesEnd(6)->findRepeat('2013-07-04', '2013-09-05');

//print_r($dates);

//$repeat->setDateEnd('2013-09-29');

//print_r($repeat->findRepeat('2013-06-24', '2013-07-30'));

//print_r($repeat->findRepeat('2013-07-14', '2013-08-31'));

//$repeat->setDateBegin('2013-06-26')->setRepeatType(5)->setRepeatInfo(array('freq' => 1, 'wday' => '4,3,1'));

//$repeat->setTimesEnd(9);

//echo $repeat->timesToDateStop();

//print_r($repeat->findRepeat('2013-07-10', '2013-08-20'));

//$repeat->setDateBegin('2013-07-01')->setRepeatType(6)->setRepeatInfo(array('freq' => 2, 'base' => 1, 'day' => '1,2,3'));

//$repeat->setTimesEnd(10);

//echo $repeat->timesToDateStop();

//print_r($repeat->findRepeat('2013-07-01', '2013-09-20'));

$repeat->reset()->setDateBegin('2013-07-01 00:00:00')->setRepeatType(6)->setRepeatInfo(array('freq' => 1, 'base' => 2, 'wday' => 8, 'wday_position' => 1));

$repeat->setTimesEnd(3);

echo $repeat->timesToDateStop();

print_r($repeat->findRepeat('2013-08-01 00:00:00', '2013-09-30 00:00:00'));

$repeat->reset()->setDateBegin('2013-07-01 00:00:00')->setRepeatType(7)->setRepeatInfo(array('freq' => 2, 'month' => '7,4', 'base' => 2, 'wday' => 2, 'wday_position' => 1));

$repeat->setTimesEnd(3);

echo $repeat->timesToDateStop();

print_r($repeat->findRepeat('2013-09-01 00:00:00', '2015-12-31 23:59:59'));