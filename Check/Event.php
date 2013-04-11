<?php
// Cảnh báo toàn bộ
error_reporting(E_ALL | E_NOTICE);

require '../Vhmis/Event/EventInterface.php';
require '../Vhmis/Event/EventQueue.php';
require '../Vhmis/Event/Event.php';
require '../Vhmis/Event/Result.php';
require '../Vhmis/Event/Manager.php';

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

$g->getEvent()->attach('single', 
    function (Vhmis\Event\Event $e)
    {
        B1::makeADate();
        // $e->setStopPropagation(true);
    });

$g->getEvent()->attach('single', 
    function (Vhmis\Event\Event $e)
    {
        B2::makeADate();
        $e->setStopPropagation(true);
    }, 2);

$g->getEvent()->attach('single', function (Vhmis\Event\Event $e)
{
    B3::makeADate();
});

$g->noticeSingle();

echo '------------------<br>';

$g->getEvent()->detach('single');

$g->noticeSingle();
