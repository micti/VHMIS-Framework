<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

/**
 * DateTime validator.
 */
class Upload extends ValidatorAbstract
{

    /**
     * Error code : Not valid for datetime.
     */
    const E_NOT_DATETIME = 'validator_datetime_not_datetime';

    /**
     * Error code : Not valid for datetime.
     */
    const E_NOT_VALID_TYPE = 'validator_datetime_not_valid_type';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::E_NOT_DATETIME => 'The given value is not valid for datetime.',
        self::E_NOT_VALID_TYPE => 'The given value is not valid type.'
    ];

    /**
     * Validate.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        $this->checkMissingOptions();

        if (!$this->isValidUploadFile($value['tpm_name'])) {
            return false;
        }

        if (!$this->isValidSize($value['size'])) {
            return false;
        }

        if (!$this->isValidFileType($value['ext'], $value['type'])) {
            return false;
        }

        $isImage = $this->isImageFile($value['type'], $value['tpm_name']);

        if ($this->options['file'] === 'image' && !isImage) {
            return false;
        }

        $this->standardValue = [
            'name' => $value['name'],
            'type' => $value['type'],
            'path' => $value['tpm_name'],
            'size' => $value['size'],
            'image' => $isImage
        ];

        return true;
    }

    protected function isValidSize($size)
    {
        if ($this->options['maxsize'] === 0) {
            return true;
        }

        if ($size > $this->options['maxsize']) {
            return false;
        }

        return false;
    }

    protected function isValidUploadFile($filePath)
    {
        if (!is_uploaded_file($filePath)) {
            return false;
        }

        return true;
    }

    protected function isValidFileType($ext, $mine)
    {
        $ext = strtolower($ext);
        $mine = strtolower($mine);

        // Allow all
        if (isset($this->options['type']['*'])) {
            return true;
        }

        // Not valid ext
        if (!isset($this->options['type'][$ext])) {
            return false;
        }

        // Allow all mines
        if ($this->options['type'][$ext] === '*') {
            return true;
        }

        // Not valid mine
        if (strpos($this->options['type'][$ext], $mine) === false) {
            return false;
        }

        return true;
    }

    protected function isImageFile($fileType, $filePath)
    {
        $type = array(
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/jpe',
            'image/pjpeg',
            'img/x-png'
        );

        if (!in_array($fileType, $type)) {
            return false;
        }

        $size = getimagesize($filePath);
        if ($size === false) {
            return false;
        }

        return $size;
    }

    protected function isValidImageSize($width, $height)
    {
        if ($this->options['imageSize']['width'] !== 0 && $size[0] > $this->options['imageSize']['width']) {
            return false;
        }

        if ($this->options['imageSize']['height'] !== 0 && $size[1] > $this->options['imageSize']['height']) {
            return false;
        }

        return true;
    }

    /**
     * Init.
     *
     * Set default options.
     */
    protected function init()
    {
        $this->defaultOptions = [
            'maxsize' => 0,
            'type' => [
                '*' => '*' // allow all
            ],
            'imageSize' => [
                'width' => 0,
                'height' => 0
            ],
            'file' => '*'
        ];
    }
}
