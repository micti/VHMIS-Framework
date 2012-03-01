<?php


$a = array(
    'anh' => array(
        'hai' => 1,
        'ba' => 1,
    )
);

var_dump(isset($a['anh']['tu']));

$b['anh']['hai'] = 1;
$b['anh']['ba'] = 1;

var_dump(isset($b['anh']['tu']));

$c['anh']['hai'] = 1;
$c['anh']['ba'] = 1;
$c['anh']['tu'] = 0;

var_dump($c['anh']['tu']);
var_dump(isset($c['anh']['tu']));

?>