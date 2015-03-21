<?php

namespace Vhmis\Http;

use Vhmis\Utils\Exception\InvalidArgumentException;

class Response implements ResponseInterface
{

    use MessageTrait;

    protected $status = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        511 => 'Network Authentication Required',
    ];

    /**
     *
     * @var type 
     */
    protected $statusCode;
    protected $statusPhrase;

    /**
     * 
     * @param integer $statusCode
     * @param array $headers
     * @param StreamableInterface $body
     * 
     * @throws InvalidArgumentException
     */
    public function __construct($statusCode, $headers = [], $body = null)
    {
        $this->checkStatusCode($statusCode);
        $this->statusCode = $statusCode;

        foreach ($headers as $key => $value) {
            $prepareValue = $this->prepareHeader($name, $value);
            $this->headers[$prepareValue[0]] = $prepareValue[1];
        }

        $this->body = $body;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Create a new instance with the specified status code, and optionally reason phrase, for the response.
     * 
     * @param integer $code
     * @param string|null $reasonPhrase
     * 
     * @return self
     * 
     * @throws InvalidArgumentException
     */
    public function withStatus($code, $reasonPhrase = null)
    {
        $this->checkStatusCode($code);
        $new = clone $this;
        $new->statusCode = (int) $code;
        $new->statusPhrase = $reasonPhrase;
        return $new;
    }

    /**
     * Gets the response Reason-Phrase, a short textual description of the Status-Code.
     * 
     * @return string|null
     */
    public function getReasonPhrase()
    {
        if (!$this->statusPhrase) {
            return $this->status[$this->statusCode];
        }

        return $this->statusPhrase;
    }

    /**
     * Check status code.
     *
     * @param string|int $code
     * 
     * @throws InvalidArgumentException
     */
    protected function checkStatusCode($code)
    {
        if (!is_numeric($code)) {
            throw new InvalidArgumentException('Invalid status code');
        }

        if (!isset($this->status[$code])) {
            throw new InvalidArgumentException('Invalid status code');
        }
    }
}
