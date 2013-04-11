<?php
// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

// PHP 5.1 trở lên hổ trợ thời gian từ 1901 đến 2038 (32 bit t_time on system)
/*
 * $a = mktime(0, 0, 0, 1, 1, 1902); $b = mktime(23, 59, 59, 12, 31, 2037); $c = mktime(0, 0, 0, 1, 1, 1970); $d =
 * pow(2, 31); var_dump($a); var_dump($c); var_dump($b); var_dump($d); echo '<br>';
 * @date_default_timezone_set("ASIA/HO_CHI_MINH"); $c = mktime(0, 0, 0, 1, 1, 1970); $d = gmmktime(0, 0, 0, 1, 1, 1970);
 * var_dump($c); var_dump($d); var_dump(($c - $d) / 3600); echo '<br>'; var_dump(date("e", time())); var_dump(date("I",
 * time())); var_dump(date("O", time())); var_dump(date("P", time())); var_dump(date("T", time())); var_dump(date("Z",
 * time())); echo '<br>'; var_dump(date('l jS \of F Y h:i:s A', strtotime('2012-02-03 08:26:06 +07:00')));
 * var_dump(date('l jS \of F Y h:i:s A', strtotime('2012-02-02 08:26:06 +08:00'))); var_dump(date('l jS \of F Y h:i:s
 * A', strtotime('2012-03-02 08:26:06 +07:00')));
 */

require '../Core/Date.php';
require '../Core/Calendar.php';
require '../Core/Benchmark.php';
require '../Vhmis/I18n/Output/DateTime.php';

/*
 * $mark = new Vhmis_Benchmark(); $mark->timer('a'); $date = new Vhmis_Date(); $mark->timer('b'); echo $mark->time('a',
 * 'b') . '<br />'; echo '<br>'; echo $date . '|'; echo $date->toSQL() . '|'; echo $date->toSQL(false) . '|'; echo
 * var_dump($date); $mark->timer('c'); echo '<br />' . $mark->time('a', 'c') . '<br />'; $date->time('+1 day', 8); echo
 * '<br>'; echo $date . '|'; echo $date->toSQL() . '|'; echo $date->toSQL(false) . '|'; echo var_dump($date);
 * $mark->timer('d'); echo '<br />' . '<br />' . $mark->time('a', 'd') . '<br />'; $date->time('-1 day -3 hour'); echo
 * '<br>'; echo $date->toAgo(); $mark->timer('e'); echo '<br />' . '<br />' . $mark->time('a', 'e') . '<br />'; echo
 * '<br />'; $c = mktime(0, 0, 0, 5, 32, 2012); $d = strtotime('2012-06-01'); echo $c . '.' . $d . '.'; var_dump($c ==
 * $d); echo '<br />'; $now = time(); echo date('Y-m-d', $now); echo '|'; echo date('Y-m-d', strtotime('+2 month',
 * $now)); echo '<br />'; $now = strtotime('2012-02-29'); echo date('Y-m-d', $now); echo '|'; echo date('Y-m-d',
 * strtotime('+12 month', $now)); echo '|'; echo date('Y-m-d', strtotime('+24 month', $now)); echo '|'; echo
 * date('Y-m-d', strtotime('+36 month', $now)); echo '|'; echo date('Y-m-d', strtotime('+48 month', $now)); echo '<br
 * />'; $now = strtotime('2012-05-31'); echo date('Y-m-d', $now); echo '|'; $now = strtotime('+1 month', $now); echo
 * date('Y-m-d', $now); echo '<br />'; echo date('Y-m-01 H:i:s', time()); echo '<br />'; $a = new Vhmis_Date();
 * $a->time('2012-04-11 14:35:00'); $b = $a->daysOfWeekdayInMonth(7, 0); var_dump($b); $b = $a->daysOfWeekdayInMonth(7,
 * 5); var_dump($b); $b = $a->daysOfWeekdayInMonth(7, 4); var_dump($b); $b = $a->daysOfWeekdayInMonth(7, 3);
 * var_dump($b); $b = $a->daysOfWeekdayInMonth(7, 2); var_dump($b); $b = $a->daysOfWeekdayInMonth(7, 1); var_dump($b);
 * $calendar = new Vhmis_Calendar(); print_r($calendar->datesOfMonth('05', '2012'));
 */

$format = new Vhmis\I18n\Output\DateTime();
$string = "2012-11-11";

$format->setLocale('en_US');
echo $format->dateByPattern($string, "{yMMM}");

$a = \DateTime::createFromFormat('Y-m-d H:i:s', '2012-01-01 10:56:31');

$formatter = new \IntlDateFormatter('it', \IntlDateFormatter::FULL, \IntlDateFormatter::FULL);

echo "\ndate: " . date('d-m-Y H:i:s', $a->getTimestamp()) . "\n";
echo "formatter: " . $formatter->format($a->getTimestamp()) . "\n\n";