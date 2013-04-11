<?php

/**
 * Vhmis Framework (http://vhmis.viethanit.edu.vn/developer/vhmis)
 *
 * @link http://vhmis.viethanit.edu.vn/developer/vhmis Vhmis Framework
 * @copyright Copyright (c) IT Center - ViethanIt College (http://www.viethanit.edu.vn)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package Vhmis_Event
 * @since Vhmis v2.0
 */
namespace Vhmis\Event;

/**
 * Interface dùng để mô tả các sự kiện
 *
 * @category Vhmis
 * @package Vhmis_Event
 */
interface EventInterface
{

    /**
     * Lấy tên gọi của sự kiện
     *
     * @return string
     */
    public function getName();

    /**
     * Lấy đối tượng xảy ra sự kiện
     *
     * @return mixed
     */
    public function getTarget();

    /**
     * Lấy các tham số truyền theo
     *
     * @return mixed
     */
    public function getParams();

    /**
     * Thiết lập tên gọi của sự kiện
     *
     * @param string $name
     * @return Vhmis\Event\EventInterface
     */
    public function setName($name);

    /**
     * Thiết lập đối tượng xảy ra sự kiện
     *
     * @param mixed $target
     * @return Vhmis\Event\EventInterface
     */
    public function setTarget($target);

    /**
     * Thiết lập các tham số truyền theo
     *
     * @param mixed $params
     * @return Vhmis\Event\EventInterface
     */
    public function setParams($params);

    /**
     * Thiết lập dừng lại
     *
     * @param bool $bool
     */
    public function setStopPropagation($bool = false);

    /**
     * Kiểm tra dừng lại
     *
     * @return bool
     */
    public function isPropagationStopped();
}
