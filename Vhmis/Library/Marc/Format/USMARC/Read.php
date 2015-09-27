<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Library\Marc\Format\USMARC;

use Vhmis\Library\Marc\Biblio;

/**
 * Read biblio record from USMARC file (.usm extension)
 */
class Read
{

    /**
     * Spec
     * 
     * @var array 
     */
    protected $spec = [
        'record' => "\x1D",
        'field' => "\x1E",
        'subfield' => "\x1F"
    ];

    /**
     * Biblio records
     * 
     * @var Biblio[]
     */
    protected $records = [];

    /**
     * Read from string
     * 
     * @param string $data
     */
    public function readData($data)
    {
        $records = explode($this->spec['record'], $data);

        foreach ($records as $record) {
            $this->readRecord($record);
        }
    }

    /**
     * Read from file
     * 
     * @param string $file
     * 
     * @return boolean
     */
    public function readFile($file)
    {
        if (is_file($file) && is_readable($file)) {
            $content = file_get_contents($file);
            $this->readData($content);
            return true;
        }

        return false;
    }

    /**
     * Get records
     * 
     * @return Biblio[]
     */
    public function getRecords()
    {
        return $this->records;
    }

    /**
     * Reset result
     * 
     * @return \Vhmis\Library\Marc\Format\USMARC\Read
     */
    public function reset()
    {
        $this->records = [];
        return $this;
    }

    /**
     * Read a record from data
     * 
     * @param string $data
     */
    protected function readRecord($data)
    {
        $len = strlen($data) + 1;
        
        $matches = [];
        $test = preg_match("/^(\d{5})/", $data, $matches);
        if ($test !== 1) {
            return;
        }

        if (0 + $matches[1] !== $len) {
            return;
        }

        //$leader = substr($data, 0, 24);
        $body_start = 0 + substr($data, 12, 5);
        $codes = $this->readDirectory(substr($data, 24, $body_start - 25));
        $fields = $this->readField(substr($data, $body_start));
        $total = count($codes);
        if ($total !== count($fields)) {
            return;
        }

        $biblio = new Biblio;

        for ($i = 0; $i < $total; $i++) {
            $code = $codes[$i];
            $biblio->addField($codes[$i]);
            $biblio->setFieldIndicators($codes[$i], $fields[$i][0], $fields[$i][1]);
            for ($j = 0; $j < count($fields[$i][2]); $j++) {
                $biblio->addSubField($code, $fields[$i][2][$j][0], $fields[$i][2][$j][1]);
            }
        }

        $this->records[] = $biblio;
    }

    protected function readDirectory($data)
    {
        if (strlen($data) % 12 !== 0) {
            return false;
        }

        $directorty = [];
        for ($i = 0; $i < strlen($data); $i += 12) {
            $code = substr($data, $i, 12);
            $directorty[] = substr($code, 0, 3);
        }

        return $directorty;
    }

    protected function readField($data)
    {
        $fields = explode($this->spec['field'], $data);
        $filedData = [];
        foreach ($fields as $field) {
            if (strlen($field) < 2) {
                continue;
            }
            $field_in_1 = substr($field, 0, 1);
            $field_in_2 = substr($field, 1, 1);
            $subfields = substr($field, 3);
            $subFieldData = [];

            $subfields = explode($this->spec['subfield'], $subfields);
            foreach ($subfields as $subfield) {
                if ($subfield === '') {
                    continue;
                }
                $index = $subfield{0};
                $value = substr($subfield, 1);
                $subFieldData[] = [$index, $value];
            }

            $filedData[] = [$field_in_1, $field_in_2, $subFieldData];
        }

        return $filedData;
    }
}
