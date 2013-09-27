<?php

namespace Vhmis\File;

class Upload
{
    /**
     * Loại file (phần mở rộng và mine type) được chấp nhận
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
     * Kích thước tối đa của file (byte)
     * 0 tương ứng với không giới hạn
     *
     * @var int
     */
    protected $maxSize = 0;

    /**
     * Kết quả upload
     *
     * @var array
     */
    protected $result = array();

    public function __construct()
    {

    }

    /**
     * Thiết lập kích thước tối đa cho phép của file
     *
     * @param int $byte
     * @return \Vhmis\File\Upload
     */
    public function setMaxSize($byte)
    {
        $this->maxSize = (int) $byte;

        return $this;
    }

    /**
     * Thiết lập các dạng file được phép upload
     *
     * Thiết lập chấp nhận tất cả các loại file
     * array('*' => '*')
     *
     * Thiết lập chỉ kiểm tra phần mở rộng, bỏ qua mine type
     * array('pdf' => '*', 'doc' => '*')
     *
     * Kiểm tra cả phần mở rộng và mine type
     * array('pdf' => 'application/pdf application/x-download', 'doc' => 'application/msword')
     *
     * @param array $types
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
     * Thực hiện việc upload
     *
     * @var array $file Đối tượng file được up lên
     * @var string $dir Thư mục up lên
     * @var string $filename Tên file sau khi up, nếu để trống thì sẽ sử dụng tên mặc định của file được up
     */
    public function upload($file, $dir, $filename = '')
    {
        // Reset lại thuộc tính chứa kết quả
        $this->result = array();

        // Kiểm tra thư mục upload
        if (!$this->checkDir($dir)) {
            $this->result['uploaded'] = false;
            $this->result['code'] = 12;
            $this->result['message'] = 'Không thể upload vào thư mục ' . $dir;
            return $this->result;
        }

        // Không thể upload
        if (!is_uploaded_file($file['tmp_name'])) {
            $error = (!isset($file['error'])) ? 4 : $file['error'];

            // To do : cần ghi cụ thể message lỗi dựa vào $error
            $this->result['uploaded'] = false;
            $this->result['code'] = $error;
            $this->result['message'] = 'Không thể upload file';

            return $this->result;
        }

        // Lấy tên file
        $clientFilename = $this->cleanFilename($file['name']);
        if ($filename == '') {
            $filename = $clientFilename;
        }

        // Lấy phần mở rộng của file
        $clientFileext = explode('.', $clientFilename);
        $clientFileext = end($clientFileext);

        // Lấy ext của file, trong trường hợp tên file đưa vào không có ext thì
        // lấy ext của tên file từ client
        if (strpos($filename, '.') === false) {
            $fileext = $clientFileext;
            $filename .= '.' . $clientFileext;
        } else {
            $fileext = explode('.', $filename);
            $fileext = end($fileext);
        }

        $filesize = $file['size'];

        // Kiểm tra kích thước file
        if ($this->maxSize != 0) {
            if ($filesize > $this->maxSize) {
                $this->result['uploaded'] = false;
                $this->result['code'] = 2;
                $this->result['message'] = 'Kích thước file không được vượt quá ' . $this->maxSize;

                return $this->result;
            }
        }

        // Kiểm tra xem đã tồn tại file tại thư mục upload chưa
        if (file_exists($dir . D_SPEC . $filename)) {
            $filename = time() . '_' . $filename;
        }

        // File type
        $filetype = $this->getFiletype($file);

        // Set thông tin
        $this->result['file_name'] = $filename;
        $this->result['file_name_not_ext'] = $filenameNotExt;
        $this->result['file_path'] = $dir;
        $this->result['file_full_path'] = $dir . D_SPEC . $filename;
        $this->result['file_ext'] = $fileext;
        $this->result['file_type'] = $filetype;
        $this->result['file_size'] = $filesize;

        // Kiểm tra ext của mine của file
        if (!$this->checkFiletype($clientFileext, $filetype)) {
            $this->result['uploaded'] = false;
            $this->result['code'] = 8;
            $this->result['message'] = 'File type không hợp lệ';
            return $this->result;
        }

        // Không upload được
        if (!@move_uploaded_file($file['tmp_name'], $this->result['file_full_path'])) {
            $this->result['uploaded'] = false;
            $this->result['code'] = 20;
            $this->result['message'] = 'Upload không thành công';
            return $this->result;
        }

        // Kiểm tra file ảnh
        if (in_array($filetype, array(
                'image/gif',
                'image/jpeg',
                'image/png',
                'image/jpg',
                'image/jpe',
                'image/pjpeg',
                'img/x-png'
            ))) {
            if ($size = getimagesize($this->result['file_full_path'])) {
                $this->result['file_image'] = array(
                    'w' => $size[0],
                    'h' => $size[1]
                );
            }
        }

        $this->result['uploaded'] = true;
        $this->result['code'] = 0;
        $this->result['message'] = 'Upload thành công';
        return $this->result;
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
        if (isset($this->allowTypes['*']))
            return true;

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
        /*if($finfo = new \finfo(FILEINFO_MIME_TYPE)) {
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