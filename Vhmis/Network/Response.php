<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Network;

use Vhmis\Config\Configure;

/**
 * Class trả lại kết quả tới client
 *
 * @category Vhmis
 * @package Vhmis_Network
 */
class Response
{

    /**
     * Body content
     *
     * @var string
     */
    protected $body;

    /**
     * Gửi kết quả xử lý tới client
     */
    public function response()
    {
        // header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        // header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
        $this->sendContent();
    }

    /**
     * Thông báo lỗi
     */
    public function reponseError($code)
    {
        if ($code === '404') {
            header('HTTP/1.0 404 Not Found');
            echo 'Page not found 404';
            exit();
        }

        if ($code === '403') {
            header('HTTP/1.0 403 Forbidden');
            echo 'Forbidden!';
            exit();
        }
    }

    /**
     * Thiết lập nội dung trả về
     *
     * @param string nội dung trả về
     */
    public function body($content)
    {
        $this->body = $content;

        return $this;
    }

    /**
     * Download file
     *
     * @param string $path
     * @param string $filename
     * @param string|null $type
     */
    public function download($path, $filename, $type = null)
    {
        // Size
        $size = filesize($path);

        // Type
        if (!is_string($type)) {
            $type = \Vhmis\Utils\File::getFileType($path);
        }

        // Header
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: ' . $type);
        header('Content-Length: ' . $size);

        // Small file
        if ($size < 5242880) {
            flush();
            readfile($path);
            exit();
        }

        // Large file
        $chunkSize = 1024 * 1024;
        $handle = fopen($path, 'rb');

        while (!feof($handle)) {
            $buffer = fread($handle, $chunkSize);
            echo $buffer;
            ob_flush();
            flush();
        }

        fclose($handle);
        exit();
    }

    public function redirect($path)
    {
        header('Location: ' . $path);
        exit();
    }

    /**
     * Gửi nội dung trả về
     *
     * @param string Nội dung trả về
     */
    protected function sendContent()
    {
        echo $this->body;
    }
}
