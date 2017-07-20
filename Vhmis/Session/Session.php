<?php

namespace Vhmis\Session;

class Session extends \ArrayObject
{

    /**
     * Session name
     * $_SESSION[name][key] => $data
     *
     * @var string
     */
    protected $name;

    /**
     * Init
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;

        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);

        $this->start();
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
        if ($this->offsetExists($key)) {
            return $_SESSION[$this->name][$key];
        }

        return null;
    }

    public function offsetUnset($key)
    {
        if ($this->offsetExists($key)) {
            unset($_SESSION[$this->name][$key]);
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($_SESSION[$this->name]);
    }

    /**
     * Get SID
     *
     * @return string
     */
    public function getId()
    {
        return session_id();
    }

    /**
     * Start session
     *
     * @return bool
     */
    public function start() {
        return session_start();
    }

    /**
     * Write and close session handler
     */
    public function end() {
        return session_write_close();
    }
}
