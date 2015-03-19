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
     * Construct with a resource.
     *
     * @param resource $stream
     *
     * @throws \InvalidArgumentException
     */
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
        if ($this->stream === null) {
            return '';
        }

        $this->seek(0);

        return stream_get_contents($this->stream);
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close()
    {
        if ($this->steam !== null) {
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
        $resource = $this->stream;
        $this->steam = null;
        $this->seekable = $this->writeable = $this->readable = false;
        $this->size = null;

        return $resource;
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

        if ($this->stream) {
            $metadata = stream_get_meta_data($this->stream);
        }

        if ($key === null) {
            return $metadata;
        }

        return isset($metadata[$key]) ? : null;
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

        if ($this->stream === null) {
            return null;
        }

        $stats = fstat($this->stream);

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
        return $this->readable ? fread($this->stream, $length) : false;
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

        return fseek($this->stream, $offset, $whence) === 0;
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return int|boolean
     */
    public function tell()
    {
        return $this->stream ? ftell($this->stream) : false;
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
        return $this->writeable ? fwrite($this->stream, $string) : false;
    }

}
