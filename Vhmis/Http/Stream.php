<?php

namespace Vhmis\Http;

class Stream implements StreamableInterface
{
    /**
     * Original resource of stream.
     * 
     * @var resource
     */
    protected $stream;
    
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be a resource');
        }
        
        $this->stream = $stream;
    }
    
    /**
     * Return all content of stream as string.
     * 
     * @return string
     */
    public function __toString()
    {
        if($this->stream === null) {
            return '';
        }
        
        $this->seek(0);
        
        return stream_get_contents($this->stream);
    }

    public function close()
    {
        
    }

    public function detach()
    {
        
    }

    public function eof()
    {
        
    }

    /**
     * Returns the remaining contents in a string.
     * 
     * @return string
     */
    public function getContents()
    {
        return $this->stream ? stream_get_contents($this->stream) : '';
    }

    public function getMetadata($key = null)
    {
        
    }

    public function getSize()
    {
        
    }

    public function isReadable()
    {
        
    }

    public function isSeekable()
    {
        
    }

    public function isWritable()
    {
        
    }

    public function read($length)
    {
        
    }

    public function rewind()
    {
        
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        
    }

    public function tell()
    {
        
    }

    public function write($string)
    {
        
    }

}