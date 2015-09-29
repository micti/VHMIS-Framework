<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
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
        $field = $this->getFieldCode('245');
        if ($field === []) {
            return '';
        }

        $field = $field[0];
        $title = '';

        $subfield = $field->getSubFieldCode('a');
        if ($subfield == []) {
            return $title;
        }

        $subfield = $subfield[0];
        $title = $subfield->getValue();

        $subfield = $field->getSubFieldCode('b');
        if ($subfield == []) {
            return $title;
        }

        $subfield = $subfield[0];
        return trim($title . ' ' . $subfield->getValue());
    }
    
    public function getMainAuthor()
    {
        
    }
}
