<?php
require_once '../Core/Filter.php';
require_once '../Core/Validator.php';

$filter = new Vhmis_Core_Filter();
$validator = new Vhmis_Core_Validator();

$a = '~<:>203854fdjdsfksdk lfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->digit($a);
echo '<br>' . "\n";

$a = '~<:>203854fdjdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a);
echo '<br>' . "\n";

$a = '~<:>203854fdjdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a, true);
echo '<br>' . "\n";

$a = '~<:>203854f' . "\t" . 'djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a, true);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a, false, true);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alnum($a, true, true);
echo '<br>' . "\n";

$a = '~<:>203854fdjdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a);
echo '<br>' . "\n";

$a = '~<:>203854fdjdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a, true);
echo '<br>' . "\n";

$a = '~<:>203854f' . "\t" . 'djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a, true);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a, false, true);
echo '<br>' . "\n";

$a = '~<:>203854f cổng vàng djdsfk sd klfdsiw0454350-395435~<:>';
echo $a . " ->> " . $filter->alpha($a, true, true);
echo '<br>' . "\n";

$a = '          ';
var_dump($validator->notEmpty($a));
echo '<br>' . "\n";

$a = '      s    ';
var_dump($validator->notEmpty($a));
echo '<br>' . "\n";

$a = '203854f cổng vàng djdsfk sd klfdsiw0454350395435';
echo $a . " ->> alnum ->> ";
var_dump($validator->alnum($a));
echo '<br>' . "\n";
echo $a . " ->> alnum ->> ";
var_dump($validator->alnum($a, false, true));
echo '<br>' . "\n";
echo $a . " ->> alnum ->> ";
var_dump($validator->alnum($a, true, true));
echo '<br>' . "\n";
echo $a . " ->> alnum ->> ";
var_dump($validator->alnum($a, true, false));
echo '<br>' . "\n";

$a = 'sfffgfg fhgfjgfjdgfgfjkgf gfjgfkjgfjkgfjk 0405459565';
echo $a . " ->> alnum ->> ";
var_dump($validator->alnum($a, true));
echo '<br>' . "\n";

$a = 'cổng vàng djdsfk sd klfdsi';
echo $a . " ->> alpha ->> ";
var_dump($validator->alpha($a));
echo '<br>' . "\n";
echo $a . " ->> alpha ->> ";
var_dump($validator->alpha($a, false, true));
echo '<br>' . "\n";
echo $a . " ->> alpha ->> ";
var_dump($validator->alpha($a, true, true));
echo '<br>' . "\n";
echo $a . " ->> alpha ->> ";
var_dump($validator->alpha($a, true, false));
echo '<br>' . "\n";

$a = 'sfffgfg fhgfjgfjdgfgfjkgf';
echo $a . " ->> alpha ->> ";
var_dump($validator->alnum($a, true));
echo '<br>' . "\n";

$a = '<b style="font-size:19pt">Anh</b>';
echo htmlentities($a) . " ->> strip HTML ->> " . htmlentities($filter->stripHTML($a));
echo '<br>' . "\n";

echo htmlentities($a) . " ->> strip HTML ->> " . htmlentities($filter->stripHTML($a, array(
    'b'
)));
echo '<br>' . "\n";

$a = '< ahfj jfd fjfjdf djfd jfdjdsfjdsf dsf 19 > 1948 <b style="font-size:19pt">Anh</b>';
echo htmlentities($a) . " ->> strip HTML ->> " . htmlentities($filter->stripHTML($a));
echo '<br>' . "\n";

echo htmlentities($a) . " ->> strip HTML ->> " . htmlentities($filter->stripHTML($a, array(
    'b'
)));
echo '<br>' . "\n";

$a = '< sjfdsjfdjfdsjfdj<b>></b>';
echo htmlentities($a) . " ->> strip HTML ->> " . htmlentities($filter->stripHTML($a, array(
    'b'
)));
echo '<br>' . "\n";

$a = '< sjfdsjfdjfdsjfdj<b>></b>';
echo htmlentities($a) . " ->> strip HTML ->> " . $filter->htmlEntities($a, ENT_QUOTES);
echo '<br>' . "\n";

$a = '< sjfd"""""""\'\'\'\'sjfdjfdsjfdj<b>></b>';
echo htmlentities($a) . " ->> strip HTML ->> " . $filter->htmlEntities($a, ENT_QUOTES);
echo '<br>' . "\n";

$a = '< sjfd""\x8F!!!"""""\'\'\'\'sjfdjfdsjfdj<b>></b>';
echo htmlentities($a) . " ->> strip HTML ->> " . $filter->htmlEntities($a, ENT_QUOTES);
echo '<br>' . "\n";

$a = "\x8F!!!";
echo htmlentities($a) . " ->> strip HTML ->> " . $filter->htmlEntities($a, ENT_QUOTES);
echo '<br>' . "\n";