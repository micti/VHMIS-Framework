<?php

namespace Vhmis\Http;

use Vhmis\Utils\Exception\InvalidArgumentException;

class Stream implements StreamableInterface
{

    /**
     * Underlying resource.
     *
     * @var resource
     */
    protected $resource;

    /**
     * Readable.
     *
     * @var boolean
     */
    protected $readable;

    /**
     * Seekable.
     *
     * @var boolean
     */
    protected $seekable;

    /**
     * Writeable.
     *
     * @var boolean
     */
    protected $writeable;

    /**
     * Current size of resource.
     *
     * @var int
     */
    protected $size;

    /**
     * Construct with a resource.
     *
     * @param resource $stream
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('Stream must be a resource');
        }

        $this->resource = $stream;

        $meta = stream_get_meta_data($this->resource);
        $this->seekable = $meta['seekable'];
        $this->readable = $this->isReadMode($meta['mode']);
        $this->writeable = $this->isWriteMode($meta['mode']);
        $this->size = $this->getSize();
    }

    /**
     * Return all content of stream as string.
     *
     * @return string
     */
    public function __toString()
    {
        if ($this->resource === null) {
            return '';
        }

        $this->seek(0);

        return stream_get_contents($this->resource);
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        if ($this->resource === null) {
            return;
        }

        $resource = $this->detach();
        fclose($resource);
    }

    /**
     *
     * @return resource|null
     */
    public function detach()
    {
        $resource = $this->resource;
        $this->resource = null;
        $this->seekable = $this->writeable = $this->readable = false;
        $this->size = null;

        return $resource;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return boolean
     */
    public function eof()
    {
        return !$this->resource || feof($this->resource);
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->resource ? stream_get_contents($this->resource) : '';
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * @param null|string $key
     *
     * @return array|mixed|null
     */
    public function getMetadata($key = null)
    {
        $metadata = [];

        if ($this->resource) {
            $metadata = stream_get_meta_data($this->resource);
        }

        if ($key === null) {
            return $metadata;
        }

        return isset($metadata[$key]) ? $metadata[$key] : null;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null
     */
    public function getSize()
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if ($this->resource === null) {
            return null;
        }

        $stats = fstat($this->resource);

        if (isset($stats['size'])) {
            $this->size = $stats['size'];
            return $this->size;
        }

        return null;
    }

    public function isReadable()
    {
        return $this->readable;
    }

    public function isSeekable()
    {
        return $this->seekable;
    }

    public function isWritable()
    {
        return $this->writeable;
    }

    public function read($length)
    {
        return $this->readable ? fread($this->resource, $length) : false;
    }

    public function rewind()
    {
        return $this->seek(0);
    }

    public function seek($offset, $whence = SEEK_SET)
    {
        if (!$this->seekable) {
            return false;
        }

        return fseek($this->resource, $offset, $whence) === 0;
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return int|boolean
     */
    public function tell()
    {
        return $this->resource ? ftell($this->resource) : false;
    }

    /**
     * Write string to stream.
     *
     * @param string $string
     *
     * @return int|boolean
     */
    public function write($string)
    {
        $this->size = null;

        return $this->writeable ? fwrite($this->resource, $string) : false;
    }

    protected function isWriteMode($mode)
    {
        /* if (strpos($mode, 'w') !== false) {
          return true;
          }

          if (strpos($mode, '+') !== false) {
          return true;
          }

          if (strpos($mode, 'a') !== false) {
          return true;
          }

          if (strpos($mode, 'x') !== false) {
          return true;
          }

          if (strpos($mode, 'c') !== false) {
          return true;
          }

          return false; */

        $modes = [
            'w', 'a', 'x', 'c', 'rw',
            'w+', 'a+', 'x+', 'c+', 'r+',
            'w+b', 'w+b', 'x+b', 'c+b', 'r+b',
            'w+t', 'w+t', 'x+t', 'c+t', 'r+t',
            'wb'
        ];

        return in_array($mode, $modes);
    }

    protected function isReadMode($mode)
    {
        if (strpos($mode, 'r') !== false) {
            return true;
        }

        if (strpos($mode, '+') !== false) {
            return true;
        }

        return false;
    }
}
