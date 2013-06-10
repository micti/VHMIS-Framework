<?php

namespace Vhmis\Session;

class Session extends \ArrayObject
{
    /**
     * Tên của session
     *
     * $_SESSION[name][key] => $data
     *
     * @var string
     */
    protected $name;

    /**
     * Khởi tạo name
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);

        session_start();
    }

    public function offsetSet($key, $value)
    {
        $_SESSION[$this->name][$key] = $value;

        return $this;
    }

    public function offsetExists($key)
    {
        return isset($_SESSION[$this->name][$key]);
    }

    public function offsetGet($key)
    {
        if($this->offsetExists($key)) return $_SESSION[$this->name][$key];

        return null;
    }

    public function getIterator()
    {
        return new \ArrayIterator($_SESSION[$this->name]);
    }

    /**
     * Lấy SID hiện tại
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }
}
