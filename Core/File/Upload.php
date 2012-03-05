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
    protected $_options = array();
    protected $_result = array();

    public function __construct()
    {
        $this->options['maxsize'] = 0;
        $this->options['check_type'] = true;
        $this->options['allow_types'] = array('torrent');
        $this->options['file_types'] = ___loadConfig('Mine', false);
        $this->options['file_types'] = isset($this->options['file_types']['minetypes']) ? $this->options['file_types']['minetypes'] : false;
    }

    /**
     * Thực hiện việc upload
     *
     * @var array $name Đối tượng file được up lên ($_FILES[''], nếu sử dụng trong Controller của Vhmis thì $this->post->['_files'][])
     * @var string $filedir Thư mục up lên
     * @var string $filename Tên file sau khi up, nếu để trống thì sẽ sử dụng tên mặc định của file được up
     * @var array $options Các thuộc tính
     */
    public function upload($name, $filedir, $filename = '')
    {
        // Reset lại thuộc tính chứa kết quả
        $this->_result = array();

        // Kiểm tra thư mục upload
        if(!$this->_checkFileDir($filedir))
        {
            $this->_result['uploaded'] = false;
            $this->_result['code'] = 12;
            $this->_result['message'] = 'Không thể upload vào thư mục ' . $filedir;
            return $this->_result;
        }

        // Không thể upload
        if(!is_uploaded_file($name['tmp_name']))
        {
            $error = (!isset($name['error'])) ? 4 : $name['error'];

            // To do : cần ghi cụ thể message lỗi dựa vào $error
            $this->_result['uploaded'] = false;
            $this->_result['code'] = $error;
            $this->_result['message'] = 'Không thể upload file';

            return $this->_result;
        }

        // Lấy tên file
        $filename_client = $this->_cleanFileName($name['name']);
        if($filename == '')
        {
            $filename = $filename_client;
        }

        // Lấy ext của file, trong trường hợp tên file đưa vào không có ext thì lấy ext của tên file từ client
        if(strpos($filename, '.') === false)
        {
            $fileext = explode('.', $filename_client);
            $fileext = end($fileext);
            $filename .= '.' . $fileext;
        }
        else
        {
            $fileext = explode('.', $filename);
            $fileext = end($fileext);
        }

        $filesize = $name['size'];

        // Kiểm tra kích thước file
        if($this->options['maxsize'] != 0)
        {
            if($filesize > $this->options['maxsize'])
            {
                $this->_result['uploaded'] = false;
                $this->_result['code'] = 2;
                $this->_result['message'] = 'Kích thước file không được vượt quá ' . $this->options['maxsize'];

                return $this->_result;
            }
        }

        // Kiểm tra xem đã tồn tại file tại thư mục upload chưa
        if(file_exists($filedir . D_SPEC . $filename))
        {
            // to do : generation thêm uuid cho chắc chắn
            $filename = time() . '_' . $filename;
        }

        // File type
        $filetype = $this->_getFileType($name);

        // Set thông tin
        $this->_result['file_name'] = $filename;
        $this->_result['file_path'] = $filedir;
        $this->_result['file_full_path'] = $filedir . D_SPEC . $filename;
        $this->_result['file_ext'] = $fileext;
        $this->_result['file_type'] = $filetype;
        $this->_result['file_size'] = $filesize;

        // Kiểm tra file type
        if(!$this->_checkFileType($fileext, $filetype, $this->options['check_type']))
        {
            $this->_result['uploaded'] = false;
            $this->_result['code'] = 8;
            $this->_result['message'] = 'File type không hợp lệ';
            return $this->_result;
        }

        // Không upload được
        if(!@move_uploaded_file($name['tmp_name'], $this->_result['file_full_path']))
        {
            $this->_result['uploaded'] = false;
            $this->_result['code'] = 20;
            $this->_result['message'] = 'Upload không thành công';
            return $this->_result;
        }

        $this->_result['uploaded'] = true;
        $this->_result['code'] = 0;
        $this->_result['message'] = 'Upload thành công';
        return $this->_result;
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

    /**
     * Kiểm tra filetype
     *
     * @var string $ext Phần mở rộng của file
     * @var string $type File type
     * @var string $checkmine Có kiểm tra mine của file không
     * @return Filetype có hợp lệ hay không
     */
    protected function _checkFileType($ext, $type, $checkmine = true)
    {
        if($this->options['allow_types'] == '*') return true;

        if(!is_array($this->options['allow_types']) || count($this->options['allow_types']) == 0)
        {
            return false;
        }

        $ext = strtolower($ext);
        $type = strtolower($type);

        if(!in_array(strtolower($ext), $this->options['allow_types']))
        {
            return false;
        }

        if($checkmine == true && is_array($this->options['file_types']))
        {
            if(!isset($this->options['file_types'][$ext]))
            {
                return false;
            }

            if(is_string($this->options['file_types'][$ext]) && $this->options['file_types'][$ext] == $type)
            {
                return true;
            }

            if(is_array($this->options['file_types'][$ext]) && in_array($type, $this->options['file_types'][$ext]))
            {
                return true;
            }

            return false;
        }

        return true;
    }


    /**
     * Lấy file type
     *
     * @var array $file Đối tượng file
     * @return File type
     */
    protected function _getFileType($file)
    {
        if(function_exists('mime_content_type'))
		{
			return @mime_content_type($file['tmp_name']);
		}

		return $file['type'];
    }

    /**
     * Xóa các ký tự không đẹp ở tên file
     *
     * @var string $filename Tên file
     * @var bool $removesapce Có xóa khoảng trắng thành _ không
     * @return Tên file đã được xử lý
     */
    protected function _cleanFileName($filename, $removespace = true)
    {
        // Ký tự không nên có trong filename
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
            "%22", // <
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

        // Mã khoảng trắng
        $space = array(
            "%20"
        );

        $filename = str_replace($bad, '', $filename);
        $filename = str_replace($space, ' ', $filename);

        if($removespace)
        {
			$filename = preg_replace('/\s+/u', '_', $filename);
        }

        return $filename;
    }
}