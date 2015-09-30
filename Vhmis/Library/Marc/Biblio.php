<?php

/**
 * Vhmis Framework
 *
 * @link      http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license   http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Library\Marc;

use Vhmis\Library\Marc\Structure\Field;

/**
 * Bibliographic record
 */
class Biblio
{

    /**
     * Field data
     *
     * @var Field[][]
     */
    protected $fields = [];

    /**
     *
     * @param Field $field
     *
     * @return \Vhmis\Library\Marc\Biblio
     */
    public function addField($field)
    {
        $this->fields[$field->getCode()][] = $field;

        return;
    }

    public function removeField($field)
    {
        $code = $field->code;
        if (isset($this->fields[$code])) {
            foreach ($this->fields[$code] as $key => $value) {
                if ($value === $field) {
                    unset($this->fields[$code][$key]);
                    $this->fields[$code] = array_values($this->fields[$code]);
                    return true;
                }
            }
        }

        return false;
    }

    public function removeFieldCode($code)
    {
        $this->fields[$code] = [];

        return $this;
    }

    public function getFieldCode($code)
    {
        if (isset($this->fields[$code])) {
            return $this->fields[$code];
        }

        return [];
    }

    /**
     *
     * @return Field[][]
     */
    public function getFields()
    {
        return $this->fields;
    }

    public function getFullTitle()
    {
        $value1 = $this->getValue('245', 'a');
        $value2 = $this->getValue('245', 'b');
        //$value3 = $this->getValue('245', 'c');

        return implode(' ', [$value1, $value2]);
    }

    public function getMainAuthor()
    {
        $data1 = $this->getValue('100', 'a');
        $data2 = $this->getValue('110', 'a');
        $data3 = $this->getValue('120', 'a');

        // Trick!?
        return $data1 . $data2 . $data3;
    }

    public function getOtherAuthors()
    {
        $data = $this->getValues('700', 'a');

        return implode(';', $data);
    }

    public function getKeywords()
    {
        $data1 = $this->getValues('600', 'a');
        $data2 = $this->getValues('610', 'a');
        $data3 = $this->getValues('630', 'a');
        $data4 = $this->getValues('648', 'a');
        $data5 = $this->getValues('650', 'a');
        $data6 = $this->getValues('651', 'a');

        $data = array_merge($data1, $data2, $data3, $data4, $data5, $data6);

        return implode(';', $data);
    }

    public function getValue($field, $subfield)
    {
        $fields = $this->getFieldCode($field);
        if ($fields === []) {
            return '';
        }

        $subfields = $fields[0]->getSubFieldCode($subfield);
        if ($subfields === []) {
            return '';
        }

        return $subfields[0]->getValue();
    }

    public function getValues($field, $subfield)
    {
        $fields = $this->getFieldCode($field);
        $data = [];
        foreach ($fields as $field) {
            $subfields = $field->getSubFieldCode($subfield);

            if ($subfields !== []) {
                $data[] = $subfields[0]->getValue();
            }
        }

        return $data;
    }
}
