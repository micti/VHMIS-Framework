<?php

class Vhmis_File_Image_Gd2
{

    protected $_error = array();

    protected $_newImgSource = null;

    public function __construct($path)
    {
        $this->_error = array('code' => 0, 'message' => 'Ok');
        
        if (! extension_loaded('gd')) {
            $this->_error = array('code' => 1, 'message' => 'GD2 Lib Not Found or Not Supported');
            return $this;
        }
        
        if (! is_readable($path)) {
            $this->_error = array('code' => 2, 'message' => 'File Can Not Read');
            return $this;
        }
        
        $this->_file = $path; // TO DO, fix filename and path ...
        
        if (! $size = @getimagesize($this->_file)) {
            $this->_error = array('code' => 3, 'message' => 'Not Image File');
            return $this;
        }
        
        if ($size[2] < 1 && $size[2] > 3) {
            $this->_error = array('code' => 4, 'message' => 'Only support PNG, GIF, JPG');
            return $this;
        }
        
        $this->_width = $size[0];
        $this->_height = $size[1];
        $this->_type = $size[2];
        $this->_mime = $size['mime'];
    }

    public function getError()
    {
        return $this->_error;
    }

    public function isError()
    {
        return $this->_error['code'] > 0 ? true : false;
    }

    public function crop($size, $axis)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        $this->_copy($size, $axis, $size);
    }

    public function resize($size)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        $this->_copy($size, array(0, 0), array($this->_width, $this->_height));
    }

    public function cropAndResize($size, $axis, $newSize)
    {
        $this->_copy($newSize, $axis, $size);
    }

    public function thumb($type, $size = 100)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        $_type = array('width', 'height', 'square', 'scale');
        if (! in_array($type, $_type)) {
            return false;
        }
        
        $w = $this->_width;
        $h = $this->_height;
        
        $min = min($w, $h);
        if ($size > $min)
            $size = $min;
        
        $thumbWidth = $thumbHeight = $size;
        
        if ($type == 'width')         // Kiểu theo chiều rộng
        {
            $thumbHeight = (int) ($h / $w * $thumbWidth);
            
            $this->_copy(array($thumbWidth, $thumbHeight), array(0, 0), array($w, $h));
        } elseif ($type == 'height')         // Kiểu theo chiều cao
        {
            $thumbWidth = (int) ($w / $h * $thumbHeight);
            
            $this->_copy(array($thumbWidth, $thumbHeight), array(0, 0), array($w, $h));
        } elseif ($type == 'scale')         // Kiểu tỷ lệ
        {
            if ($this->_height > $this->_width) {
                $thumbWidth = (int) ($w / $h * $thumbHeight);
            } else {
                $thumbHeight = (int) ($h / $w * $thumbWidth);
            }
            
            $this->_copy(array($thumbWidth, $thumbHeight), array(0, 0), array($w, $h));
        } else         // Kiểu hình vuông
        {
            $this->_copy(array($thumbWidth, $thumbHeight), array(0, 0), array($min, $min));
        }
        
        return true;
    }

    public function save($path, $clear = true)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        if ($this->_newImgSource == null)
            return false;
        
        if ($this->_type == 1) {
            if (! @imagegif($this->_newImgSource, $path)) {
                return false;
            }
        } elseif ($this->_type == 2) {
            if (! @imagejpeg($this->_newImgSource, $path, 90)) {
                return false;
            }
        } else {
            if (! @imagepng($this->_newImgSource, $path)) {
                return false;
            }
        }
        
        if ($clear)
            $this->_clear();
        
        return true;
    }

    public function output($clear = true)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        if ($this->_newImgSource == null)
            return false;
        
        header('Content-Disposition: filename=abc.jpg;');
        header('Content-Type: ' . $this->_mime);
        
        if ($this->_type == 1) {
            imagegif($this->_newImgSource);
        } elseif ($this->_type == 2) {
            imagejpeg($this->_newImgSource, '', 90);
        } else {
            imagepng($this->_newImgSource);
        }
        
        if ($clear)
            $this->_clear();
        
        exit();
    }

    protected function _createImgSource()
    {
        if ($this->_error['code'] > 0)
            return false;
        
        $type = $this->_type;
        
        if ($type == 1) {
            if (! function_exists('imagecreatefromgif')) {
                $this->_error = array('code' => 5, 'message' => 'GIF not Support');
                return false;
            }
            
            return imagecreatefromgif($this->_file);
        } elseif ($type == 2) {
            if (! function_exists('imagecreatefromjpeg')) {
                $this->_error = array('code' => 6, 'message' => 'JPEG not Support');
                return false;
            }
            
            return imagecreatefromjpeg($this->_file);
        } else {
            if (! function_exists('imagecreatefrompng')) {
                $this->_error = array('code' => 7, 'message' => 'PNG not Support');
                return false;
            }
            
            return imagecreatefrompng($this->_file);
        }
    }

    protected function _copy($size, $axis, $sourceSize)
    {
        if ($this->_error['code'] > 0)
            return false;
        
        if (! $source = $this->_createImgSource()) {
            return false;
        }
        
        $this->_newImgSource = imagecreatetruecolor($size[0], $size[1]);
        
        imagecopyresampled($this->_newImgSource, $source, 0, 0, $axis[0], $axis[1], $size[0], $size[1], $sourceSize[0], $sourceSize[1]);
        
        return true;
    }

    protected function _clear()
    {
        $this->_newImgSource = null;
    }
}