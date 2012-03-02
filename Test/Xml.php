<?php

include '../Core/Xml.php';

$a = array(
    'country' => array(
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        ),
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        )
    )
);

echo Vhmis_Xml::SimpleFromArray($a);

$b = array(
    'country' => array(
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        ),
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        )
    ),
    'city' => array(
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        ),
        array(
            'name' => array(
                'full' => 'Vietnam',
                'short' => 'VN'
            ),
            'area' => '2345.354'
        )
    )
);

echo Vhmis_Xml::SimpleFromArray($b);

?>