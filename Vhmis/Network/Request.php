<?php
/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @since Vhmis v2.0
 */

namespace Vhmis\Network;

/**
 * Xứ lý yêu câu (request) gửi đến
 */
class Request
{
    /**
     * Uri
     *
     * @var Vhmis\Network\Uri
     */
    protected $uri;

    /**
     * Mảng chứa dữ liệu của POST.
     *
     * @var array
     */
    public $post = array();

    /**
     * Mảng chứa dữ liệu của GET (querystring).
     *
     * @var array
     */
    public $get = array();

    /**
     * Thông tin ứng dụng của request
     *
     * @var mixed
     */
    public $app;

    /**
     * Request code;
     *
     * @var string
     */
    public $responeCode;

    /**
     * Router
     *
     * @var \Vhmis\Network\Router
     */
    protected $router;

    /**
     * Khởi tạo!
     *
     * @param string $url Địa chỉ url.
     */
    public function __construct(Router $router = null)
    {
        if ($router != null) {
            $this->router = $router;
        }
    }

    /**
     * Thiết lập Router
     *
     * @param \Vhmis\Request\Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Xử lý request
     *
     * @param string $url
     */
    public function process($url = null)
    {
        $this->responeCode = '200';

        if (empty($url)) {
            $url = $this->getUrl();
        }

        $this->uri = new Uri($url);

        if ($this->uri->getHost() === '') {
            $this->responeCode = '403';

            return;
        }

        $result = $this->router->check($this->uri->getPath());

        if ($result['match'] == false) {
            $this->responeCode = '404';

            return;
        }

        $this->app = $result;

        $this->getPostData();
        $this->getGetData();
        $this->getFileData();
        $this->delData();
    }

    /**
     * Lấy địa chỉ ip
     *
     * @return string
     */
    public function getIp()
    {
        $ip = '0.0.0.0';

        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP'] != '') {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] != '') {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (($commaPos = strpos($ip, ',')) > 0) {
            $ip = substr($ip, 0, ($commaPos - 1));
        }

        return $ip;
    }

    /**
     * Lấy địa chỉ url hiện tại.
     *
     * @return string
     */
    public function getUrl()
    {
        $url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" .
            $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

        return urldecode($url);
    }

    /**
     * Lấy địa chỉ referrer
     *
     * @return string
     */
    public function getReferrer()
    {
        $referrer = $_SERVER['HTTP_REFERER'];
        $forward = $_SERVER['HTTP_X_FORWARDED_HOST'];

        if ($forward) {
            return $forward;
        }

        return $referrer;
    }

    /**
     * Lấy giá trị post
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getPost($name = null)
    {
        if ($name === null) {
            return $this->post;
        }

        if (isset($this->post[$name])) {
            return $this->post[$name];
        }

        return null;
    }

    /**
     * Lấy giá trị get
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getGet($name = null)
    {
        if ($name === null) {
            return $this->get;
        }

        if (isset($this->get[$name])) {
            return $this->get[$name];
        }

        return null;
    }

    /**
     * Lấy tất cả kiểu accept có thể trả về
     *
     * @return array
     */
    public function getAcceptType()
    {
        if (isset($_SERVER['HTTP_ACCEPT'])) {
            $accepts = explode(';', $_SERVER['HTTP_ACCEPT']);
            $accepts = explode(',', $accepts[0]);

            // Trim
            foreach ($accepts as &$accept) {
                $accept = trim($accept);
            }

            return $accepts;
        }

        return array();
    }

    /**
     * Kiểm tra xem có phải là Ajax/XMLHttpRequest request không
     *
     * Đúng với một số thư viện như jQuery, Mootools, Prototype ...
     *
     * @return boolean
     */
    public function isAjaxRequest()
    {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        }

        return false;
    }

    /**
     * Tìm kiểu trả về thích hợp nhất cho ajax
     *
     * html|json|text|xml
     *
     * @return string
     */
    public function findAjaxReponseContentType()
    {
        if ($this->isAjaxRequest()) {
            $accepts = $this->getAcceptType();
            if (!empty($accepts)) {
                if ($accepts[0] === 'application/json') {
                    return 'json';
                } elseif ($accepts[0] === 'text/xml') {
                    return 'xml';
                } else {
                    return 'text';
                }
            }
        }

        return 'html';
    }
    
    public function getPostUpload()
    {
        $post = $this->post;
        $post += $this->post['_files'];
        
        unset($post['_files']);
        
        return $post;
    }

    /**
     * Lấy dữ liệu của phương thức POST.
     * Dữ liệu sau khi được lấy được lưu và truy xuất thuộc tính $data
     */
    protected function getPostData()
    {
        $this->post = $_POST;

        // Loại bỏ ký tự \
        if (ini_get('magic_quotes_gpc') === '1') {
            $this->post = $this->stripSlashes($this->post);
        }
    }

    /**
     * Lấy dữ liệu của phương thức GET (querystring).
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở thuộc tính $query.
     */
    protected function getGetData()
    {
        $this->get = $_GET;

        // Loại bỏ ký tự \
        if (ini_get('magic_quotes_gpc') === '1') {
            $this->get = $this->stripSlashes($this->get);
        }
    }

    /**
     * Lấy dữ liệu của $_FILE.
     * Dữ liệu sau khi được lấy được lưu và truy xuất ở $post['_files'].
     */
    protected function getFileData()
    {
        if (isset($_FILES) && is_array($_FILES)) {
            foreach ($_FILES as $name => $data) {
                // sắp xếp lại dữ liệu nếu là multi upload, xem thêm
                // http://php.net/manual/en/features.file-upload.multiple.php
                if (is_array($data['name'])) {
                    $new = array();
                    foreach ($data as $key => $all) {
                        foreach ($all as $i => $val) {
                            $new[$i][$key] = $val;
                        }
                    }
                    $data = $new;
                }

                $this->post['_files'][$name] = $data;
            }
        }
    }

    /**
     * Xóa dữ liệu các biến GLOBAL
     */
    protected function delData()
    {
        unset($_POST);
        unset($_FILES);
        unset($_GET);
        unset($_REQUEST);
    }

    /**
     * Strip slashes for array or string data
     *
     * @param type $values
     *
     * @return type
     */
    protected function stripSlashes($values)
    {
        if (is_array($values)) {
            foreach ($values as $key => $value) {
                $values[$key] = $this->stripSlashes($value);
            }
        } else {
            $values = stripslashes($values);
        }

        return $values;
    }
}
