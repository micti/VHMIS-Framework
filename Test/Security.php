<?php

// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

require '../booter.php';
require '../Core/Security.php';

$security = new Vhmis_Security();

$a1 = '<a href="http://yahoo.com"><IMG SRC=javascript:alert(&quot;XSS&quot;)></a>';

echo $security->xssClean($a1);