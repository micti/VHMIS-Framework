<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <title>Check Date Time Intl</title>
        <meta charset="utf-8"/>
    </head>
    <body>
<?php

define('D_SPEC', DIRECTORY_SEPARATOR);

require '../Vhmis/I18n/Output/DateTime.php';
require '../Vhmis/I18n/Resource/Resource.php';

$vi = Vhmis\I18n\Resource\Resource::getDateTimePattern('yMMMd', 'Hm', 'vi_VN');
$en = Vhmis\I18n\Resource\Resource::getDateTimePattern('yMMMd', 'Hm', 'en_US');
$ko = Vhmis\I18n\Resource\Resource::getDateTimePattern('yMMMd', 'Hm', 'ko_KR');

$format = new Vhmis\I18n\Output\DateTime();
$string = "2012-11-11 12:12:12";

$format->setLocale('vi_VN');
//echo $format->customPattern($string, $vi);
echo $format->relative(array('e' => -1), $string);
//echo $format->relative(array('w' => 0), $string);
//echo $format->relative(array('w' => 1), $string);
echo '<br>';
$format->setLocale('en_US');
//echo $format->customPattern($string, $en);
echo $format->relative(array('e' => -1), $string);
//echo $format->relative(array('w' => 0), $string);
//echo $format->relative(array('w' => 1), $string);
echo '<br>';
$format->setLocale('ko_KR');
//echo $format->customPattern($string, $ko);
echo $format->relative(array('e' => -1), $string);
//echo $format->relative(array('w' => 0), $string);
//echo $format->relative(array('w' => 1), $string);

?>
    </body>
</html>