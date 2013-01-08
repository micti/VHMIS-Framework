<?php

namespace Vhmis\Validator;

use Vhmis_Network_Request;

/**
 * Class để kiểm tra các giá trị được nhập vào từ request có hợp lệ không
 *
 * @package Vhmis_Validator
 * @subpackage Input
 */
class Input extends ValidatorAbstract
{

    /**
     * Đối tượng Vhmis_Network_Request
     *
     * @var Vhmis_Network_Request
     */
    protected $_request;

    /**
     * Danh sách các trường cần kiểm tra
     *
     * @var array
     */
    protected $_fields;

    /**
     * Danh sách các đối tượng Validator được tạo ra để kiểm tra
     * @var array
     */
    protected $_validators;

    /**
     * Phương thức truyền dữ liệu của request (get, post)
     *
     * @var string
     */
    protected $_method = 'post';

    /**
     * Khởi tạo với tham số truyền vào là đối tượng Vhmis_Network_Request nằm trong
     * Vhmis_Controller
     *
     * Trong File controller
     * $inputValidator = new Vhmis\Validator\Input($this->request);
     *
     * @param Vhmis_Network_Request $request
     */
    public function __construct(Vhmis_Network_Request $request, $options = array())
    {
        $this->_request = $request;
        $this->_fields = array();

        if (is_array($options)) {
            if (isset($options['method'])) {
                $this->_method = $options['method'] === 'get' ? 'get' : 'post';
            }
        }
    }

    /**
     * Thiết lập phương thức truyền dữ liệu của request
     *
     * @param string $method
     */
    public function setMethodRequest($method)
    {
        if (is_string($method)) {
            $this->_method = $method == 'get' ? 'get' : 'post';
        }

        $this->_method = 'post';
    }

    /**
     * Thêm trường cần kiểm tra
     */
    public function addField($name, $type = null, $params = null, $allowEmpty = true)
    {
        $this->_fields[$name]['validations'][] = array(
            'name' => $name,
            'type' => $type,
            'params' => $params,
        );
        if (isset($this->_fields[$name]['allowEmpty']))
            $this->_fields[$name]['allowEmpty'] = $this->_fields[$name]['allowEmpty'] && $allowEmpty;
        else
            $this->_fields[$name]['allowEmpty'] = $allowEmpty;

        return $this;
    }

    /**
     * Kiếm tra
     */
    public function isValid($value = null, $options = null)
    {
        $method = $this->_method;
        $input = $this->_request->$method;

        foreach ($this->_fields as $name => $field) {
            if (!isset($input[$name])) {
                $this->_setMessage("Không tồn tại", ValidatorAbstract::NOTEXIST, 'notexist');
                return false;
            }

            // Kiểm tra rỗng
            if ($input[$name] === '') {
                if ($field['allowEmpty']) {
                    $this->_standardValue[$name] = '';
                    continue;
                } else {
                    $this->_setMessage('Không được rỗng', ValidatorAbstract::NOTEMPTY, 'notempty');
                    return false;
                }
            }

            $this->_standardValue[$name] = $input[$name];

            foreach ($field['validations'] as $validation) {
                // Nếu không cần kiểm tra bất kỳ tính hợp lệ nào
                if ($validation['type'] === null || $validation['type'] === '') {

                    $this->_standardValue[$name] = $input[$name];
                    continue;
                }

                // Kiểm tra hợp lệ
                $validator = md5($validation['type']);
                if (!isset($this->_validators[$validator])) {
                    $classname = "Vhmis\\Validator\\" . $validation['type'];
                    $this->_validators[$validator] = new $classname;
                }

                if (!$this->_validators[$validator]->isValid($input[$name], $validation['params'])) {
                    $message = $this->_validators[$validator]->getMessages();
                    $this->_setMessage($message['message'], $message['code'], $message['translator']);
                    return false;
                }

                // Dữ liệu chuẩn
                $this->_standardValue[$name] = $this->_validators[$validator]->getStandardValue();
            }
        }

        $this->_setMessage('Hợp lệ', 0, 'noerror');
        return true;
    }

}