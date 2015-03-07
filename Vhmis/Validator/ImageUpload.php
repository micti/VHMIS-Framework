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
 * Image upload validator.
 */
class ImageUpload extends Upload
{

    /**
     * Validate image upload.
     * 
     * @param array $value
     * 
     * @return boolean
     */
    public function isValid($value)
    {
        if (!parent::isValid($value)) {
            return false;
        }

        $image = $this->isImageFile($value['tpm_name']);

        if (!$image) {
            return false;
        }

        if (!$this->isValidImageSize($image['width'], $image['height'])) {
            return false;
        }

        $this->standardValue['image'] = $image;

        return true;
    }

    protected function isImageFile($filePath)
    {
        $size = getimagesize($filePath);
        if ($size === false) {
            return false;
        }

        return [
            'width' => $size[0],
            'height' => $size[1],
        ];
    }

    protected function isValidImageSize($width, $height)
    {
        if ($this->options['imageSize']['width'] !== 0 && $width > $this->options['imageSize']['width']) {
            return false;
        }

        if ($this->options['imageSize']['height'] !== 0 && $height > $this->options['imageSize']['height']) {
            return false;
        }

        return true;
    }

    protected function init()
    {
        parent::init();

        $this->defaultOptions['type'] = [
            'image/gif',
            'image/jpg',
            'image/jpeg',
            'image/jpe',
            'image/pjpeg',
            'image/png',
            'img/x-png'
        ];
    }
}
