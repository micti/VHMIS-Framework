<?php

require_once '../Booter.php';
require_once '../Core/File/Image.php';
require_once '../Core/File/Image/Gd2.php';

$a = new Vhmis_File_Image_Gd2('/WebServer/abc.jpg');

if ($a->isError()) {
    echo 'error';
    exit();
}

// $a->resize(array(500,100));

// $a->crop(array(500,300), array(50,50));

$a->thumb('square', 150);
$a->save('/WebServer/Data/thumb.jpg');

?>