<?php

namespace Vhmis\File;

class Upload
{
    /**
     * Allowed file extensions
     *
     * @var array
     */
    protected $allowTypes = array(
        // Document
        'doc'  => 'application/msword',
        'docx' => 'application/msword application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'ppt'  => 'application/powerpoint',
        'pptx' => 'application/powerpoint application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'xls'  => 'application/excel application/vnd.ms-excel',
        'xlsx' => 'application/excel application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'txt'  => 'text/plain',
        'odp'  => 'application/vnd.oasis.opendocument.presentation',
        'ods'  => 'application/vnd.oasis.opendocument.spreadsheet',
        'odt'  => 'application/vnd.oasis.opendocument.text',
        'pdf'  => 'application/pdf application/x-msdownload application/x-download',
        // Image
        'jpe'  => 'image/jpeg image/pjpeg',
        'jpeg' => 'image/jpeg image/pjpeg',
        'jpg'  => 'image/jpeg image/pjpeg',
        'png'  => 'image/png image/x-png',
        'gif'  => 'image/gif',
        // Zip, rar
        'zip' => 'application/x-zip application/zip application/x-zip-compressed',
        'rar' => 'application/x-rar application/rar application/x-rar-compressed',
    );

    /**
     * Max size of file (in byte)
     * 0 : No limit
     *
     * @var int
     */
    protected $maxSize = 0;

    /**
     * Upload result
     *
     * @var array
     */
    protected $result = array();

    public function __construct()
    {

    }

    /**
     * Set max size of file
     *
     * @param int $byte
     *
     * @return \Vhmis\File\Upload
     */
    public function setMaxSize($byte)
    {
        $this->maxSize = (int) $byte;

        return $this;
    }

    /**
     * Set allowed file extensions
     *
     * @param array $types
     *
     * @return \Vhmis\File\Upload
     */
    public function setAllowTypes($types)
    {
        if (is_array($types)) {
            $this->allowTypes = $types;
        }

        return $this;
    }

    /**
     * Do upload
     *
     * @param array  $file     HTTP POST file
     * @param string $dir      Directory
     * @param string $filename Filename
     *
     * @return array
     */
    public function upload($file, $dir, $filename = '')
    {
        $this->result = array();

        // Check upload
        if (!$this->checkUpload($dir, $file)) {
            return $this->getCurrentUploadResult();
        }

        // Fileinfo
        list($filename, $filenameNotExt, $fileext, $clientFileext) = $this->getFileInfo($file['name'], $filename);

        // Check file size
        $filesize = $file['size'];
        if ($this->maxSize != 0) {
            if ($filesize > $this->maxSize) {
                return $this->uploadError(2, 'Kích thước file không được vượt quá ' . $this->maxSize);
            }
        }

        // Exist filename in upload dir
        if (file_exists($dir . D_SPEC . $filename)) {
            $prefix = time() . '_' . rand();
            $filename = $prefix . '_' . $filename;
            $filenameNotExt = $prefix . '_' . $filenameNotExt;
        }
        $fullpath = $dir . D_SPEC . $filename;

        // File type
        $filetype = $this->getFiletype($file);

        // Kiểm tra ext của mine của file
        if (!$this->checkFiletype($clientFileext, $filetype)) {
            return $this->uploadError(8, 'File type không hợp lệ');
        }

        // Không upload được
        if (!@move_uploaded_file($file['tmp_name'], $fullpath)) {
            return $this->uploadError(20, 'Upload không thành công');
        }

        // Image file?
        $this->result['file_image'] = $this->checkImageFile($filetype, $fullpath);

        // Successful result
        $this->result['file_name'] = $filename;
        $this->result['file_name_not_ext'] = $filenameNotExt;
        $this->result['file_path'] = $dir;
        $this->result['file_full_path'] = $fullpath;
        $this->result['file_ext'] = $fileext;
        $this->result['file_type'] = $filetype;
        $this->result['file_size'] = $filesize;
        $this->result['uploaded'] = true;
        $this->result['code'] = 0;
        $this->result['message'] = 'Upload thành công';

        return $this->result;
    }

    /**
     * Get current upload result
     *
     * @return array
     */
    public function getCurrentUploadResult()
    {
        return $this->result;
    }

    /**
     * Check upload
     *
     * @param string $dir
     * @param array  $file HTTP POST file
     *
     * @return array
     */
    protected function checkUpload($dir, $file)
    {
        // Check dir
        if (!$this->checkDir($dir)) {
            $this->uploadError(12, 'Không thể upload vào thư mục ' . $dir);

            return false;
        }

        // Check upload result
        if (!is_uploaded_file($file['tmp_name'])) {
            $error = (!isset($file['error'])) ? 4 : $file['error'];
            $this->uploadError($error, 'Không thể upload file');

            return false;
        }

        return true;
    }

    /**
     * Set upload error info
     *
     * @param int    $code
     * @param string $message
     *
     * @return array
     */
    protected function uploadError($code, $message)
    {
        $this->result['uploaded'] = false;
        $this->result['code'] = $code;
        $this->result['message'] = $message;

        return $this->result;
    }

    protected function getFileInfo($clientFilename, $filename)
    {
        $clientFilename = $this->cleanFilename($clientFilename);
        if ($filename == '') {
            $filename = $clientFilename;
        }

        // Lấy phần mở rộng của file
        $clientFileext = explode('.', $clientFilename);
        $clientFileext = strtolower(end($clientFileext));

        // Lấy ext của file, trong trường hợp tên file đưa vào không có ext thì
        // lấy ext của tên file từ client
        if (strpos($filename, '.') === false) {
            $fileext = $clientFileext;
            $filenameNotExt = $filename;
            $filename .= '.' . $clientFileext;
        } else {
            $fileext = explode('.', $filename);
            $ext = strtolower(end($fileext));
            array_pop($fileext);
            $filenameNotExt = implode('.', $fileext);
            $fileext = $ext;
        }

        return array($filename, $filenameNotExt, $fileext, $clientFileext);
    }

    protected function checkImageFile($fileType, $filePath)
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

        if (in_array($fileType, $type)) {
            $size = getimagesize($filePath);
            if ($size !== false) {
                return array(
                    'w' => $size[0],
                    'h' => $size[1]
                );
            }
        }

        return false;
    }

    protected function checkDir($filedir)
    {
        if (!@is_dir($filedir)) {
            return false;
        }

        if (!is_writable($filedir)) {
            return false;
        }

        return true;
    }

    /**
     * Kiểm tra filetype
     *
     * @var string $ext Phần mở rộng của file
     * @var string $type Mine type của file (ex image/png ...)
     * @return Filetype có hợp lệ hay không
     */
    protected function checkFiletype($ext, $type)
    {
        $ext = strtolower($ext);
        $type = strtolower($type);

        // Chấp nhận tất cả
        if (isset($this->allowTypes['*'])) {
            return true;
        }

        // Nếu không có ext
        if (!isset($this->allowTypes[$ext])) {
            return false;
        }

        // Nếu ext chấp nhận tất cả mine
        if ($this->allowTypes[$ext] === '*') {
            return true;
        }

        // Nếu không có mine
        if (strpos($this->allowTypes[$ext], $type) === false) {
            return false;
        }

        return true;
    }

    /**
     * Lấy file type
     *
     * @var array $file
     * @return string
     */
    protected function getFiletype($file)
    {
        /*if ($finfo = new \finfo(FILEINFO_MIME_TYPE)) {
            $type = $finfo->file($file['tmp_name']);
        }*/

        return mime_content_type($file['tmp_name']);

        return $file['type'];
    }

    /**
     * Xóa các ký tự không đẹp ở tên file
     *
     * @var string $filename Tên file
     * @var bool $removesapce Có xóa khoảng trắng thành _ không
     * @return string Tên file đã được xử lý
     */
    protected function cleanFilename($filename, $removespace = true)
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
            "%3d"
        );

        // Mã khoảng trắng
        $space = array(
            "%20"
        );

        $filename = str_replace($bad, '', $filename);
        $filename = str_replace($space, ' ', $filename);

        if ($removespace) {
            $filename = preg_replace('/\s+/u', '_', $filename);
        }

        return $filename;
    }
}
