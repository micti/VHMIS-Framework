<?php
require '../Vhmis/DateTime/DateTime.php';
require '../Vhmis/DateTime/DateRepeat.php';

use \Vhmis\DateTime\DateRepeat;

$repeat = new DateRepeat;
$repeat->setDateBegin('2013-06-24')->setRepeatType(4)->setRepeatInfo(array('freq' => 4));

//$dates = $repeat->findRepeat('2013-07-04', '2013-09-05');

//print_r($dates);

//$dates = $repeat->setTimesEnd(6)->findRepeat('2013-07-04', '2013-09-05');

//print_r($dates);

$repeat->setDateEnd('2013-09-29');

print_r($repeat->findRepeat('2013-06-24', '2013-07-30'));

print_r($repeat->findRepeat('2013-07-14', '2013-08-31'));