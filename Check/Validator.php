<?php

require '../Vhmis/Config/Configure.php';
require '../Vhmis/Validator/ValidatorInterface.php';
require '../Vhmis/Validator/ValidatorAbstract.php';
require '../Vhmis/Validator/Validator.php';
require '../Vhmis/Validator/Arr.php';
require '../Vhmis/Validator/Int.php';
require '../Vhmis/Validator/Float.php';
require '../Vhmis/Validator/Date.php';
require '../Vhmis/I18n/FormatPattern/DateTime.php';
require '../Vhmis/DateTime/DateTime.php';

$validator = new Vhmis\Validator\Validator;

$postn = array('day_in_month', 'day_in_month2', 'day_in_month1', 'day', 'base');
$postv = array(
    'day_in_month' => array(1,2,3),
    'day_in_month2' => array(),
    'day_in_month1' => 'a',
    'day' => '07/12/2013',
    'base' => '11,111.'
);

$validator->fromPost($postn, $postv);
//$validator->addPostAllowEmpty(array('day_in_month1'));
//$validator->addPostValidator('day_in_month', 'Arr')->addPostValidator('day_in_month2', 'Arr')->addPostValidator('day_in_month1', 'Arr');
//$validator->addPostValidator('day', 'Int');
//$validator->addPostValidator('base', 'Float');
$validator->addPostValidator('day', 'Date');

var_dump($validator->isValid());

var_dump($validator);
