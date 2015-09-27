<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Library\Marc;

/**
 * Bibliographic record
 */
class Biblio
{

    /**
     * Leader
     *
     * @var string
     */
    protected $leader;

    /**
     * Field data
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Add field
     *
     * @param string $code
     *
     * @return \Vhmis\Library\Marc\Biblio
     */
    public function addField($code)
    {
        if (!isset($this->fields[$code])) {
            $this->fields[$code] = [];
        }

        return $this;
    }

    /**
     *
     * @param string $code
     * @param string $i1
     * @param string $i2
     *
     * @return \Vhmis\Library\Marc\Biblio
     */
    public function setFieldIndicators($code, $i1, $i2)
    {
        $this->addField($code);
        $this->fields[$code]['i1'] = $i1;
        $this->fields[$code]['i2'] = $i2;

        return $this;
    }

    /**
     * Add sub field.
     *
     * @param string $field_code
     * @param string $code
     * @param string $value
     *
     * @return \Vhmis\Library\Marc\Biblio
     */
    public function addSubField($field_code, $code, $value)
    {
        $this->addField($field_code);
        $this->fields[$field_code]['sub'][$code][] = $value;

        return $this;
    }

    /**
     * Get field data
     *
     * @param string $code
     *
     * @return array|null
     */
    public function getField($code)
    {
        if (!isset($this->fields[$code])) {
            return null;
        }

        return $this->fields[$code];
    }

    /**
     * Get 1st field indicator
     *
     * @param string $code
     * @return string
     */
    public function getFirstFieldIndicator($code)
    {
        if (!isset($this->fields[$code]['i1'])) {
            return null;
        }

        return $this->fields[$code]['i1'];
    }

    /**
     * Get 2nd field indicator
     *
     * @param string $code
     * @return string
     */
    public function getSecondFieldIndicator($code)
    {
        if (!isset($this->fields[$code]['i2'])) {
            return null;
        }

        return $this->fields[$code]['i2'];
    }

    /**
     * Get subfield code value.
     *
     * @param string $field_code
     * @param string $code
     *
     * @return array
     */
    public function getSubFieldCodeValue($field_code, $code)
    {
        return $this->fields[$field_code]['sub'][$code];
    }

    /**
     * Data in array
     *
     * @return type
     */
    public function toArray()
    {
        return $this->fields;
    }
}
