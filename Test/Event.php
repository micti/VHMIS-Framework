<?php

// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

require '../CoreVer2/Event/EventInterface.php';
require '../CoreVer2/Event/Event.php';
require '../CoreVer2/Event/Manager.php';

class G
{
    protected $event;

    public function __construct()
    {
        $this->event = new Vhmis\Event\Manager();
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function noticeSingle()
    {
        echo 'Hey boy, I am single!<br>';
        $this->event->trigger('single', $this, array(
            'age' => 28
        ));
    }
}

class B1
{
    public static function makeADate()
    {
        echo 'Hey girl, Date me, B1!<br>';
    }
}

class B2
{
    public static function makeADate()
    {
        echo 'Hey girl, Date me, B2!<br>';
    }
}

class B3
{
    public static function makeADate()
    {
        echo 'Hey girl, Date me, B3!<br>';
    }
}

$g = new G();

$g->noticeSingle();

echo '------------------<br>';

$g->getEvent()->attach('single', function(Vhmis\Event\Event $e){
    B1::makeADate();
    B2::makeADate();

    $params = $e->getParams();
    if($params['age'] < 29) {
        B3::makeADate();
    }
});

$g->noticeSingle();

echo '------------------<br>';

$g->getEvent()->detach('single');

$g->noticeSingle();