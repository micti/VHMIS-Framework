<?php

/**
 * Upload
 *
 * PHP 5
 *
 * VHMIS(tm) : Viethan IT Management Information System
 * Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 *
 * All rights reversed, giữ toàn bộ bản quyền, các thư viện bên ngoài xin xem file thông tin đi kèm
 *
 * @copyright     Copyright 2011, IT Center, Viethan IT College (http://viethanit.edu.vn)
 * @link          https://github.com/VHIT/VHMIS VHMIS(tm) Project
 * @category      VHMIS
 * @package       File
 * @subpackage    Upload
 * @since         1.0.0
 * @license       All rights reversed
 */

/**
 * Lớp chứa các phương thức để thực việc upload file
 *
 * @category      VHMIS
 * @package       File
 * @subpackage    Upload
 */
class Vhmis_File_Upload
{
    protected $_fileDir = '';
    protected $_fileName = '';

    public function __construct()
    {
    }

    public function upload($name, $filedir)
    {
        if(!isset($_FILES[$name]))
        {
            echo 'not file';
            return false;
        }

        $this->_fileDir = $filedir;

        // Kiem tra thu muc upload
        if(!$this->_checkFileDir($this->_fileDir))
        {
            echo 'not dir';
            return false;
        }

        // Khong the upload
        if(!is_uploaded_file($_FILES[$name]['tmp_name']))
        {
            //SU dung $_FILE[$name]['error']
            echo 'not upload';
            return false;
        }

        $this->_fileName = $_FILES[$name]['name'];
        $this->_fileName = $this->_cleanFileName($this->_fileName);

        //
        if(!@move_uploaded_file($_FILES[$name]['tmp_name'], $this->_fileDir . $this->_fileName))
        {
            echo 'not move';
            return false;
        }

        return true;
    }

    protected function _checkFileDir($filedir)
    {
        if(!@is_dir($filedir))
        {
            return false;
        }

        if(!is_writable($filedir))
        {
            return false;
        }

        return true;
    }

    protected function _cleanFileName($filename)
    {
        $bad = array(
            "<!--",
            "-->",
            "'",
            "<",
            ">",
            '"',
            '&',
            '$',
            '=',
            ';',
            '?',
            '/',
            "%20",
            "%22",
            "%3c", // <
            "%253c", // <
            "%3e", // >
            "%0e", // >
            "%28", // (
            "%29", // )
            "%2528", // (
            "%26", // &
            "%24", // $
            "%3f", // ?
            "%3b", // ;
            "%3d" // =
        );

        $filename = str_replace($bad, '', $filename);

        return $filename;
    }
}