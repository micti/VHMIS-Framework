<?php

namespace VhmisApp\Nhansu\Model\HrmNhansu;

use \Vhmis\Db\MySQL;

class Entity extends MySQL\Entity
{
    protected $fieldNameMap = array('id' => 'id', 'ma' => 'ma', 'ten' => 'ten', 'ten_ho' => 'tenHo', 'id_chucvu' => 'idChucvu',
        'id_phongban' => 'idPhongban', 'gioi' => 'gioi', 'ngay_sinh' => 'ngaySinh', 'ngay_vao_truong' => 'ngayVaoTruong',
        'id_truonghopvao' => 'idTruonghopvao', 'anh_the' => 'anhThe', 'hoatdong' => 'hoatdong', 'ngay_roi_truong' => 'ngayRoiTruong');

    /**
     * Id
     */
    protected $id;

    /**
     * Mã nhân sự
     */
    protected $ma;

    /**
     * Tên nhân sự
     */
    protected $ten;

    /**
     * Họ lót của tên nhân sự
     */
    protected $tenHo;

    /**
     * Id chức vụ
     */
    protected $idChucvu;

    /**
     * Id chức danh
     */
    protected $idPhongban;

    /**
     * Giới tính 1 là nam, 2 nữ, 0 là không xác định
     */
    protected $gioi;

    /**
     * Ngày tháng năm sinh
     */
    protected $ngaySinh;

    /**
     * Ngày tháng năm vào trường
     */
    protected $ngayVaoTruong;

    /**
     * Id của trường hợp vào cơ quan
     */
    protected $idTruonghopvao;

    /**
     * Tên file ảnh thẻ
     */
    protected $anhThe;

    /**
     * Đang còn làm việc hay hết làm việc
     */
    protected $hoatdong;

    /**
     * Ngày rời trường
     */
    protected $ngayRoiTruong;

    /**
     * Get id
     *
     * Id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * Id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get ma
     *
     * Mã nhân sự
     */
    public function getMa()
    {
        return $this->ma;
    }

    /**
     * Set ma
     *
     * Mã nhân sự
     */
    public function setMa($ma)
    {
        $this->ma = $ma;
        return $this;
    }

    /**
     * Get ten
     *
     * Tên nhân sự
     */
    public function getTen()
    {
        return $this->ten;
    }

    /**
     * Set ten
     *
     * Tên nhân sự
     */
    public function setTen($ten)
    {
        $this->ten = $ten;
        return $this;
    }

    /**
     * Get ten_ho
     *
     * Họ lót của tên nhân sự
     */
    public function getTenHo()
    {
        return $this->tenHo;
    }

    /**
     * Set ten_ho
     *
     * Họ lót của tên nhân sự
     */
    public function setTenHo($tenHo)
    {
        $this->tenHo = $tenHo;
        return $this;
    }

    /**
     * Get id_chucvu
     *
     * Id chức vụ
     */
    public function getIdChucvu()
    {
        return $this->idChucvu;
    }

    /**
     * Set id_chucvu
     *
     * Id chức vụ
     */
    public function setIdChucvu($idChucvu)
    {
        $this->idChucvu = $idChucvu;
        return $this;
    }

    /**
     * Get id_phongban
     *
     * Id chức danh
     */
    public function getIdPhongban()
    {
        return $this->idPhongban;
    }

    /**
     * Set id_phongban
     *
     * Id chức danh
     */
    public function setIdPhongban($idPhongban)
    {
        $this->idPhongban = $idPhongban;
        return $this;
    }

    /**
     * Get gioi
     *
     * Giới tính 1 là nam, 2 nữ, 0 là không xác định
     */
    public function getGioi()
    {
        return $this->gioi;
    }

    /**
     * Set gioi
     *
     * Giới tính 1 là nam, 2 nữ, 0 là không xác định
     */
    public function setGioi($gioi)
    {
        $this->gioi = $gioi;
        return $this;
    }

    /**
     * Get ngay_sinh
     *
     * Ngày tháng năm sinh
     */
    public function getNgaySinh()
    {
        return $this->ngaySinh;
    }

    /**
     * Set ngay_sinh
     *
     * Ngày tháng năm sinh
     */
    public function setNgaySinh($ngaySinh)
    {
        $this->ngaySinh = $ngaySinh;
        return $this;
    }

    /**
     * Get ngay_vao_truong
     *
     * Ngày tháng năm vào trường
     */
    public function getNgayVaoTruong()
    {
        return $this->ngayVaoTruong;
    }

    /**
     * Set ngay_vao_truong
     *
     * Ngày tháng năm vào trường
     */
    public function setNgayVaoTruong($ngayVaoTruong)
    {
        $this->ngayVaoTruong = $ngayVaoTruong;
        return $this;
    }

    /**
     * Get id_truonghopvao
     *
     * Id của trường hợp vào cơ quan
     */
    public function getIdTruonghopvao()
    {
        return $this->idTruonghopvao;
    }

    /**
     * Set id_truonghopvao
     *
     * Id của trường hợp vào cơ quan
     */
    public function setIdTruonghopvao($idTruonghopvao)
    {
        $this->idTruonghopvao = $idTruonghopvao;
        return $this;
    }

    /**
     * Get anh_the
     *
     * Tên file ảnh thẻ
     */
    public function getAnhThe()
    {
        return $this->anhThe;
    }

    /**
     * Set anh_the
     *
     * Tên file ảnh thẻ
     */
    public function setAnhThe($anhThe)
    {
        $this->anhThe = $anhThe;
        return $this;
    }

    /**
     * Get hoatdong
     *
     * Đang còn làm việc hay hết làm việc
     */
    public function getHoatdong()
    {
        return $this->hoatdong;
    }

    /**
     * Set hoatdong
     *
     * Đang còn làm việc hay hết làm việc
     */
    public function setHoatdong($hoatdong)
    {
        $this->hoatdong = $hoatdong;
        return $this;
    }

    /**
     * Get ngay_roi_truong
     *
     * Ngày rời trường
     */
    public function getNgayRoiTruong()
    {
        return $this->ngayRoiTruong;
    }

    /**
     * Set ngay_roi_truong
     *
     * Ngày rời trường
     */
    public function setNgayRoiTruong($ngayRoiTruong)
    {
        $this->ngayRoiTruong = $ngayRoiTruong;
        return $this;
    }
}
