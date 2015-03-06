<?php

/**
 * Vhmis Framework
 *
 * @link http://github.com/micti/VHMIS-Framework for git source repository
 * @copyright Le Nhat Anh (http://lenhatanh.com)
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Vhmis\Validator;

/**
 * DateTime validator.
 */
class Upload extends ValidatorAbstract
{

    /**
     * Error code : PHP upload error code.
     */
    const E_NOT_VALID_TYPE = 'validator_datetime_not_valid_type';
    const E_PHPE_INI_SIZE = 'validator_upload_phpe_ini_size';
    const E_PHPE_FORM_SIZE = 'validator_upload_phpe_form_size';
    const E_PHPE_PARTIAL = 'validator_upload_phpe_partial';
    const E_PHPE_NO_FILE = 'validator_upload_phpe_no_file';
    const E_PHPE_NO_TMP_DIR = 'validator_upload_phpe_no_tmp_dir';
    const E_PHPE_CANT_WRITE = 'validator_upload_phpe_cant_write';
    const E_PHPE_EXTENSION = 'validator_upload_phpe_extension';

    /**
     * Error code : Uknown error
     */
    const E_UNKNOWN = 'validator_upload_unknown';

    /**
     * Error code : Uknown error
     */
    const E_NO_UPLOADED_FILE = 'validator_upload_no_uploaded_file';

    /**
     * Error messages
     *
     * @var array
     */
    protected $messages = [
        self::E_PHPE_INI_SIZE => 'Uploaded file exceeds the defined PHP INI size',
        self::E_PHPE_FORM_SIZE => 'Uploaded file exceeds the defined form size',
        self::E_PHPE_PARTIAL => 'Uploaded file was only partially uploaded',
        self::E_PHPE_NO_FILE => 'Uploaded file was not uploaded',
        self::E_PHPE_NO_TMP_DIR => 'Missing a temporary folder',
        self::E_PHPE_CANT_WRITE => 'Failed to write uploaded file to disk',
        self::E_PHPE_EXTENSION => 'Uploaded file was stopped by extension',
        self::E_UNKNOWN => 'Uknown upload error',
        self::E_NO_UPLOADED_FILE => 'No uploaded file'
    ];

    /**
     * Validate.
     *
     * @param mixed $value
     *
     * @return boolean
     */
    public function isValid($value)
    {
        $this->value = $value;

        if (!$this->isValidUploadFile($value['tpm_name'], $value['error'])) {
            return false;
        }

        if (!$this->isValidSize($value['size'])) {
            return false;
        }

        if (!$this->isValidFileType($value['ext'], $value['type'])) {
            return false;
        }

        $this->standardValue = [
            'name' => $value['name'],
            'type' => $value['type'],
            'path' => $value['tpm_name'],
            'size' => $value['size']
        ];

        return true;
    }

    /**
     * Validate uploaded file.
     * 
     * @param string $path
     * @param int $error
     * 
     * @return boolean
     */
    protected function isValidUploadFile($path, $error)
    {
        $case = [
            '1' => static::E_PHPE_INI_SIZE,
            '2' => static::E_PHPE_FORM_SIZE,
            '3' => static::E_PHPE_PARTIAL,
            '4' => static::E_PHPE_NO_FILE,
            '6' => static::E_PHPE_NO_TMP_DIR,
            '7' => static::E_PHPE_CANT_WRITE,
            '8' => static::E_PHPE_EXTENSION,
        ];

        if (isset($case[$error])) {
            $this->setError($case[$error]);
            return false;
        }

        if (!is_uploaded_file($path)) {
            $this->setError(static::E_NO_UPLOADED_FILE);
            return false;
        }

        return true;
    }

    /**
     * Validate size of uploaded file.
     * 
     * @param int $size
     * 
     * @return boolean
     */
    protected function isValidSize($size)
    {
        if ($this->options['maxsize'] === 0) {
            return true;
        }

        if ($size > $this->options['maxsize']) {
            return false;
        }

        return false;
    }

    protected function isValidFileType($ext, $mine)
    {
        $ext = strtolower($ext);
        $mine = strtolower($mine);

        // Allow all
        if (isset($this->options['type']['*'])) {
            return true;
        }

        // Not valid ext
        if (!isset($this->options['type'][$ext])) {
            return false;
        }

        // Allow all mines
        if ($this->options['type'][$ext] === '*') {
            return true;
        }

        // Not valid mine
        if (strpos($this->options['type'][$ext], $mine) === false) {
            return false;
        }

        return true;
    }

    /**
     * Init.
     *
     * Set default options.
     */
    protected function init()
    {
        $this->defaultOptions = [
            'maxsize' => 0,
            'type' => [
                '*' => '*' // allow all
            ]
        ];
    }
}
