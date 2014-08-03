<?php

namespace Vhmis\File;

class Image
{
    protected $file;
    protected $error = array(
        'code'    => 0,
        'message' => 'Ok'
    );
    protected $newImgSource;
    protected $source;
    protected $width;
    protected $height;
    protected $oWidth;
    protected $oHeight;
    protected $type;
    protected $mime;

    public function __construct($path)
    {
        $this->file = $path;
        $this->init();
    }

    public function init()
    {
        if (!extension_loaded('gd')) {
            $this->error = array(
                'code'    => 1,
                'message' => 'GD2 Lib Not Found or Not Supported'
            );
            return $this;
        }

        if (!is_readable($this->file)) {
            $this->error = array(
                'code'    => 2,
                'message' => 'File Can Not Read'
            );
            return $this;
        }

        if (!$size = @getimagesize($this->file)) {
            $this->error = array(
                'code'    => 3,
                'message' => 'Not Image File'
            );
            return $this;
        }

        if ($size[2] < 1 && $size[2] > 3) {
            $this->error = array(
                'code'    => 4,
                'message' => 'Only support PNG, GIF, JPG'
            );
            return $this;
        }

        $this->width = $this->oWidth = $size[0];
        $this->height = $this->oHeight = $size[1];
        $this->type = $size[2];
        $this->mime = $size['mime'];

        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    public function isError()
    {
        return $this->error['code'] > 0 ? true : false;
    }

    /**
     * Lấy chiều rộng của ảnh chưa chỉnh sửa
     *
     * @return int
     */
    public function getOWidth()
    {
        return (int) $this->oWidth;
    }

    /**
     * Lấy chiều cao của ảnh chưa chỉnh sửa
     *
     * @return int
     */
    public function getOHeight()
    {
        return (int) $this->oHeight;
    }

    /**
     * Lấy chiều rộng của ảnh hiện tại
     *
     * @return int
     */
    public function getWidth()
    {
        return (int) $this->width;
    }

    /**
     * Lấy mine
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Lấy chiều cao của ảnh hiện tại
     *
     * @return int
     */
    public function getHeight()
    {
        return (int) $this->height;
    }

    public function resizeX($size)
    {
        $width = $size;
        $height = (int) $width * $this->height / $this->width;

        return $this->resize(array($width, $height));
    }

    public function resizeY($size)
    {
        $height = $size;
        $width = (int) $height * $this->width / $this->height;

        return $this->resize(array($width, $height));
    }

    public function resizeAuto($size)
    {
        if ($this->width >= $this->height) {
            return $this->resizeY($size);
        }

        return $this->resizeX($size);
    }

    public function resize($size)
    {
        $this->_copy($size, array(0, 0), array($this->width, $this->height));

        return $this;
    }

    public function cropSquare($size, $axis = array(0, 0))
    {
        return $this->crop(array($size, $size), $axis);
    }

    public function crop($size, $axis = array(0, 0))
    {
        $this->_copy($size, $axis, $size);

        return $this;
    }

    public function save($path, $quality = 90, $clear = true)
    {
        if ($this->newImgSource === null) {
            copy($this->file, $path);

            return $this;
        }

        if ($this->type == 1) {
            imagegif($this->newImgSource, $path);
        } elseif ($this->type == 2) {
            imagejpeg($this->newImgSource, $path, $quality);
        } else {
            imagepng($this->newImgSource, $path);
        }

        if ($clear) {
            $this->clear();
        }

        return $this;
    }

    public function output($quality = 90)
    {
        if ($this->newImgSource === null) {
            header('Content-Disposition: filename=abc.jpg;');
            header('Content-Type: ' . $this->mime);

            flush();
            readfile($this->file);

            exit();
        }

        header('Content-Disposition: filename=abc.jpg;');
        header('Content-Type: ' . $this->mime);

        if ($this->type == 1) {
            imagegif($this->newImgSource);
        } elseif ($this->type == 2) {
            imagejpeg($this->newImgSource, '', $quality);
        } else {
            imagepng($this->newImgSource);
        }

        $this->clear();

        exit();
    }

    protected function _copy($size, $axis, $sourceSize)
    {
        if ($this->newImgSource === null) {
            $this->newImgSource = $this->_createImgSource();
        }

        $tempSource = imagecreatetruecolor($size[0], $size[1]);

        imagecopyresampled($tempSource, $this->newImgSource, 0, 0, $axis[0], $axis[1], $size[0], $size[1], $sourceSize[0], $sourceSize[1]);

        imagedestroy($this->newImgSource);
        $this->newImgSource = $tempSource;
        $this->width = $size[0];
        $this->height = $size[1];
    }

    protected function _createImgSource()
    {
        $type = $this->type;

        if ($type == 1) {
            return imagecreatefromgif($this->file);
        } elseif ($type == 2) {
            return imagecreatefromjpeg($this->file);
        } else {
            return imagecreatefrompng($this->file);
        }
    }

    /**
     * Clear image source
     */
    public function clear()
    {
        imagedestroy($this->newImgSource);
        $this->newImgSource = null;
        $this->init();

        return $this;
    }
}
