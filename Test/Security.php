<?php

// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

require '../booter.php';
require '../Core/Security.php';

$security = new Vhmis_Core_Security();

$a1 = "alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//\";alert(String.fromCharCode(88,83,83))//\\\";alert(String.fromCharCode(88,83,83))//--></SCRIPT>\">\'><SCRIPT>alert(String.fromCharCode(88,83,83))</SCRIPT>";

echo $security->xssClean($a1);

echo '<br>';

$a2 = "'';!--\"<XSS>=&{()}";

echo $security->xssClean($a2);

echo '<br>';

$a3 = "http://patft.uspto.gov/netacgi/nph-Parser?TERM1=%3Cscript%3Ealert%28%22XSS%22%29%3C/script%3E&Sect1=PTO1&Sect2=HITOFF&d=PALL&p=1&u=%2Fnetahtml%2FPTO%2Fsrchnum.htm&r=0&f=S&l=50";

echo $security->xssClean($a3);

echo '<br>';

$a4 = "<a href='ahskfkf.com?php=sjfjajfs c r i p t:alert(codmeud)&ajfjd[=sàdjfd]=á>fdfd</a>";

echo $security->xssClean($a4);

echo '<br>';

$a5 = "<a href='ahskfkf.com?php=12345&sfk=sfdfd'>fdfd</a>";

echo $security->xssClean($a5);

echo '<br>';